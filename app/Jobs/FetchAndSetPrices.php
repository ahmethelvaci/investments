<?php

namespace App\Jobs;

use App\Models\Asset;
use App\Models\Price;
use FluentDOM;
use FluentDOM\Loader\Options;
use GuzzleHttp\Client;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FetchAndSetPrices
{
    use Dispatchable;

    protected $asset;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Asset $asset = null)
    {
        $this->asset = $asset;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (is_null($this->asset)) {
            $this->setAllAssets();
        } else {
            $this->setAsset($this->asset);
        }

        SetInvestorSummaries::dispatch();
    }

    public function setAllAssets()
    {

        $assets = Asset::all();

        foreach ($assets as $asset) {
            try {

                $this->setAsset($asset);

            } catch (\Throwable $th) {
                Log::warning(
                    $th->getMessage() . " " . $th->getFile() . ":" .
                    $th->getLine() . "\n" . print_r($th->getTrace(), 1),
                    $asset->toArray()
                );
            }
            sleep(5);
        }
    }

    public function setAsset(Asset $asset)
    {
        $this->setHtml($asset);
        // dump($this->formatPrice($asset, $this->getPrice($asset)));
        $newPrice = new Price();
        $newPrice->asset_id = $asset->id;
        $newPrice->price = $this->formatPrice($asset, $this->getPrice($asset));
        $newPrice->save();

        SetSummaries::dispatch($newPrice);
    }

    private function setHtml(Asset $asset)
    {
        if (is_null($asset->web_address) || $asset->web_address == '') {
            throw new Exception('web adresi boş', 1);
        }

        $client = new Client();
        $res = $client->request('GET', $asset->web_address, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36',
                'Accept'     => 'text/html',
            ],
        ]);

        if ($res->getStatusCode() == 200) {
            Storage::put('price_control_' . $asset->id . '.html', $res->getBody());
        } else {
            throw new Exception('cekilemedi', 1);
        }
    }

    public function getHtml(Asset $asset)
    {
        return Storage::get('price_control_' . $asset->id . '.html');
    }

    private function getPrice(Asset $asset)
    {
        $htmlText = (string) $this->getHtml($asset);

        $document = FluentDOM::load($htmlText, 'html', [Options::ALLOW_FILE => TRUE]);
        $price = $document($asset->price_control);

        return $price->count() == 1 ? $price->item(0)->nodeValue : 0;
    }

    private function formatPrice(Asset $asset, $price)
    {
        if (
            Str::startsWith($asset->web_address, 'https://www.tefas.gov.tr')
        ) {
            return floatval(Str::replace(',', '.', $price));
        } else if (
            Str::startsWith($asset->web_address, 'https://bigpara.hurriyet.com.tr') ||
            Str::startsWith($asset->web_address, 'https://www.kuveytturk.com.tr')
        ) {
            $price = Str::replace('.', '', $price);
            return floatval(Str::replace(',', '.', $price));
        }
        return floatval($price);
    }
}
