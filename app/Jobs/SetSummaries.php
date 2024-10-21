<?php

namespace App\Jobs;

use App\Models\AccountSummary;
use App\Models\Asset;
use App\Models\AssetSummary;
use App\Models\Price;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class SetSummaries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $price;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Price $price)
    {
        $this->price = $price;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $asset = Asset::where('id', $this->price->asset_id)
            ->with(['accounts'])
            ->first();

        $assetPrices = $this->getAssetPrices($asset);
        [$assets, $accounts] = $this->getPrices($asset);
        $this->setAsset($assets, $assetPrices);
        $this->setAccounts($accounts);
    }

    public function getAssetPrices($asset)
    {
        $date = now()->subDay()->format('Y-m-d');
        $daily_tl = $this->assetPriceOnDate($asset->id, $date)->price;
        $daily_usd = $this->tl2Usd($daily_tl, $date);
        $date = now()->subWeek()->format('Y-m-d');
        $weekly_tl = $this->assetPriceOnDate($asset->id, $date)->price;
        $weekly_usd = $this->tl2Usd($weekly_tl, $date);
        $date = now()->subMonth()->format('Y-m-d');
        $monthly_tl = $this->assetPriceOnDate($asset->id, $date)->price;
        $monthly_usd = $this->tl2Usd($monthly_tl, $date);
        $date = now()->subMonths(3)->format('Y-m-d');
        $quarterly_tl = $this->assetPriceOnDate($asset->id, $date)->price;
        $quarterly_usd = $this->tl2Usd($quarterly_tl, $date);
        $date = now()->subMonths(6)->format('Y-m-d');
        $semiannually_tl = $this->assetPriceOnDate($asset->id, $date)->price;
        $semiannually_usd = $this->tl2Usd($semiannually_tl, $date);
        $date = now()->subYear()->format('Y-m-d');
        $yearly_tl = $this->assetPriceOnDate($asset->id, $date)->price;
        $yearly_usd = $this->tl2Usd($yearly_tl, $date);

        return [
            'daily_tl' => $daily_tl,
            'daily_usd' => $daily_usd,
            'weekly_tl' => $weekly_tl,
            'weekly_usd' => $weekly_usd,
            'monthly_tl' => $monthly_tl,
            'monthly_usd' => $monthly_usd,
            'quarterly_tl' => $quarterly_tl,
            'quarterly_usd' => $quarterly_usd,
            'semiannually_tl' => $semiannually_tl,
            'semiannually_usd' => $semiannually_usd,
            'yearly_tl' => $yearly_tl,
            'yearly_usd' => $yearly_usd
        ];
    }

    public function getPrices($asset)
    {
        $accounts = $asset->accounts;

        $assetData = [];
        $accountData = [];

        $quantity = 0.0;
        $amount_tl = 0.0;
        $amount_usd = 0.0;
        $price_tl = Price::lastPrice($asset->id)->price;
        $price_usd = (Price::lastPrice($asset->id)->price / Price::lastPrice(1)->price);
        $cost_tl = 0.0;
        $cost_usd = 0.0;

        foreach ($accounts as $account) {
            $tradeOrders = $account->tradeOrders;

            $buying_tl = 0.0;
            $buying_usd = 0.0;
            $selling_tl = 0.0;
            $selling_usd = 0.0;

            foreach ($tradeOrders as $order) {
                $orderDate = $order->created_at->format('Y-m-d');
                if ($order->buyer_account_id == $account->id) {
                    $buying_usd += $order->quantity * $this->tl2Usd($order->price, $orderDate) + $this->tl2Usd($order->transaction_fee, $orderDate);
                    $buying_tl += $order->quantity * $order->price + $order->transaction_fee;
                }
                if ($order->seller_account_id == $account->id) {
                    $selling_usd += $order->quantity * $this->tl2Usd($order->price, $orderDate) + $this->tl2Usd($order->transaction_fee, $orderDate);;
                    $selling_tl += $order->quantity * $order->price + $order->transaction_fee;
                }
            }

            $_quantity = $account->quantity;
            $_amount_tl = $_quantity * $price_tl;
            $_amount_usd = $_quantity * $price_usd;
            $_cost_tl = $buying_tl - $selling_tl;
            $_cost_usd = $buying_usd - $selling_usd;

            $accountData[$account->id]['quantity'] = $_quantity;
            $accountData[$account->id]['amount_tl'] = $_amount_tl;
            $accountData[$account->id]['amount_usd'] = $_amount_usd;
            $accountData[$account->id]['price_tl'] = $price_tl;
            $accountData[$account->id]['price_usd'] = $price_usd;
            $accountData[$account->id]['cost_tl'] = $_cost_tl;
            $accountData[$account->id]['cost_usd'] = $_cost_usd;

            $quantity += $_quantity;
            $amount_tl += $_amount_tl;
            $amount_usd += $_amount_usd;
            $cost_tl += $_cost_tl;
            $cost_usd += $_cost_usd;
        }

        $assetData[$asset->id]['quantity'] = $quantity;
        $assetData[$asset->id]['amount_tl'] = $amount_tl;
        $assetData[$asset->id]['amount_usd'] = $amount_usd;
        $assetData[$asset->id]['price_tl'] = $price_tl;
        $assetData[$asset->id]['price_usd'] = $price_usd;
        $assetData[$asset->id]['cost_tl'] = $cost_tl;
        $assetData[$asset->id]['cost_usd'] = $cost_usd;

        return [
            $assetData,
            $accountData,
        ];
    }

    public function setAsset($assets, $prices)
    {
        foreach ($assets as $asset_id => $asset) {
            AssetSummary::where('asset_id', $asset_id)
                ->update(['active' => 0]);

            $summary = new AssetSummary();
            $summary->asset_id = $asset_id;

            $summary->quantity = $asset['quantity'];
            $summary->amount_tl = $asset['amount_tl'];
            $summary->amount_usd = $asset['amount_usd'];
            $summary->price_tl = $asset['price_tl'];
            $summary->price_usd = $asset['price_usd'];

            $summary->cost_tl = $asset['cost_tl'];
            $summary->cost_usd = $asset['cost_usd'];
            $summary->profit_tl = $asset['amount_tl'] - $asset['cost_tl'];
            $summary->profit_usd = $asset['amount_usd'] - $asset['cost_usd'];
            $summary->profit_percent_tl = $asset['cost_tl'] > 0 ? ($summary->profit_tl / $asset['cost_tl']) * 100 : 0;
            $summary->profit_percent_usd = $asset['cost_usd'] > 0 ? ($summary->profit_usd / $asset['cost_usd']) * 100 : 0;

            $summary->daily_price_tl = $prices['daily_tl'];
            $summary->daily_price_usd = $prices['daily_usd'];
            $summary->daily_profit_tl = $asset['price_tl'] - $prices['daily_tl'];
            $summary->daily_profit_usd = $asset['price_usd'] - $prices['daily_usd'];
            $summary->daily_profit_percent_tl = $prices['daily_tl'] > 0 ? ($summary->daily_profit_tl / $prices['daily_tl']) * 100 : 0;
            $summary->daily_profit_percent_usd = $prices['daily_usd'] > 0 ? ($summary->daily_profit_usd / $prices['daily_usd']) * 100 : 0;

            $summary->weekly_price_tl = $prices['weekly_tl'];
            $summary->weekly_price_usd = $prices['weekly_usd'];
            $summary->weekly_profit_tl = $asset['price_tl'] - $prices['weekly_tl'];
            $summary->weekly_profit_usd = $asset['price_usd'] - $prices['weekly_usd'];
            $summary->weekly_profit_percent_tl = $prices['weekly_tl'] > 0 ? ($summary->weekly_profit_tl / $prices['weekly_tl']) * 100 : 0;
            $summary->weekly_profit_percent_usd = $prices['weekly_usd'] > 0 ? ($summary->weekly_profit_usd / $prices['weekly_usd']) * 100 : 0;

            $summary->monthly_price_tl = $prices['monthly_tl'];
            $summary->monthly_price_usd = $prices['monthly_usd'];
            $summary->monthly_profit_tl = $asset['price_tl'] - $prices['monthly_tl'];
            $summary->monthly_profit_usd = $asset['price_usd'] - $prices['monthly_usd'];
            $summary->monthly_profit_percent_tl = $prices['monthly_tl'] > 0 ? ($summary->monthly_profit_tl / $prices['monthly_tl']) * 100 : 0;
            $summary->monthly_profit_percent_usd = $prices['monthly_usd'] > 0 ? ($summary->monthly_profit_usd / $prices['monthly_usd']) * 100 : 0;

            $summary->quarterly_price_tl = $prices['quarterly_tl'];
            $summary->quarterly_price_usd = $prices['quarterly_usd'];
            $summary->quarterly_profit_tl = $asset['price_tl'] - $prices['quarterly_tl'];
            $summary->quarterly_profit_usd = $asset['price_usd'] - $prices['quarterly_usd'];
            $summary->quarterly_profit_percent_tl = $prices['quarterly_tl'] > 0 ? ($summary->quarterly_profit_tl / $prices['quarterly_tl']) * 100 : 0;
            $summary->quarterly_profit_percent_usd = $prices['quarterly_usd'] > 0 ? ($summary->quarterly_profit_usd / $prices['quarterly_usd']) * 100 : 0;

            $summary->semiannually_price_tl = $prices['semiannually_tl'];
            $summary->semiannually_price_usd = $prices['semiannually_usd'];
            $summary->semiannually_profit_tl = $asset['price_tl'] - $prices['semiannually_tl'];
            $summary->semiannually_profit_usd = $asset['price_usd'] - $prices['semiannually_usd'];
            $summary->semiannually_profit_percent_tl = $prices['semiannually_tl'] > 0 ? ($summary->semiannually_profit_tl / $prices['semiannually_tl']) * 100 : 0;
            $summary->semiannually_profit_percent_usd = $prices['semiannually_usd'] > 0 ? ($summary->semiannually_profit_usd / $prices['semiannually_usd']) * 100 : 0;

            $summary->yearly_price_tl = $prices['yearly_tl'];
            $summary->yearly_price_usd = $prices['yearly_usd'];
            $summary->yearly_profit_tl = $asset['price_tl'] - $prices['yearly_tl'];
            $summary->yearly_profit_usd = $asset['price_usd'] - $prices['yearly_usd'];
            $summary->yearly_profit_percent_tl = $prices['yearly_tl'] > 0 ? ($summary->yearly_profit_tl / $prices['yearly_tl']) * 100 : 0;
            $summary->yearly_profit_percent_usd = $prices['yearly_usd'] > 0 ? ($summary->yearly_profit_usd / $prices['yearly_usd']) * 100 : 0;

            $summary->save();
        }
    }

    public function setAccounts($accounts)
    {
        foreach ($accounts as $account_id => $account) {
            AccountSummary::where('account_id', $account_id)
                ->update(['active' => 0]);

            $summary = new AccountSummary();
            $summary->account_id = $account_id;

            $summary->quantity = $account['quantity'];
            $summary->amount_tl = $account['amount_tl'];
            $summary->amount_usd = $account['amount_usd'];
            $summary->price_tl = $account['price_tl'];
            $summary->price_usd = $account['price_usd'];

            $summary->cost_tl = $account['cost_tl'];
            $summary->cost_usd = $account['cost_usd'];
            $summary->profit_tl = $account['amount_tl'] - $account['cost_tl'];
            $summary->profit_usd = $account['amount_usd'] - $account['cost_usd'];
            $summary->profit_percent_tl = $account['cost_tl'] > 0 ? ($summary->profit_tl / $account['cost_tl']) * 100 : 0;
            $summary->profit_percent_usd = $account['cost_usd'] > 0 ? ($summary->profit_usd / $account['cost_usd']) * 100 : 0;

            $summary->save();
        }
    }

    private function tl2Usd($priceTl, $date)
    {
        if ($priceTl == 0) {
            return 0;
        }

        $usdPrice = Cache::rememberForever('usd_price.' . $date, function () use ($date) {
            return Price::where('asset_id', 1)
                ->where('created_at', '>=', $date)
                ->oldest()
                ->first();
        });

        $priceUsd = $usdPrice->price;

        return ($priceUsd ?? 0) > 0 ? $priceTl / $priceUsd : 0;
    }

    private function assetPriceOnDate($assetId, $date)
    {
        return Price::where('asset_id', $assetId)
            ->where('created_at', '>=', $date)
            ->oldest()
            ->first();
    }
}
