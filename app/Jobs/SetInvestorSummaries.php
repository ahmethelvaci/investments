<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Investor;
use App\Models\InvestorSummary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SetInvestorSummaries implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->setInvestors();
        } catch (\Throwable $th) {
            Log::warning(
                $th->getMessage() . " " . $th->getFile() . ":" .
                $th->getLine() . "\n" . $th->getTraceAsString()
            );

            throw $th;
        }
    }

    public function setInvestors()
    {
        $investors = Investor::all();

        foreach ($investors as $investor) {
            $amount_tl = 0.0;
            $amount_usd = 0.0;
            $cost_tl = 0.0;
            $cost_usd = 0.0;

            $accounts = Account::where('investor_id', $investor->id)
                ->with(['summary'])
                ->get();

            foreach ($accounts as $account) {
                $amount_tl += $account->summary->amount_tl;
                $amount_usd += $account->summary->amount_usd;
                $cost_tl += $account->summary->cost_tl;
                $cost_usd += $account->summary->cost_usd;
            }

            InvestorSummary::where('investor_id', $investor->id)
                ->update(['active' => 0]);

            $summary = new InvestorSummary();
            $summary->investor_id = $investor->id;

            $summary->amount_tl = $amount_tl;
            $summary->amount_usd = $amount_usd;

            $summary->cost_tl = $cost_tl;
            $summary->cost_usd = $cost_usd;

            $summary->save();
        }
    }
}
