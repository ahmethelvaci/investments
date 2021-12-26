<?php

namespace App\Http\Livewire;

use App\Models\InvestorSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;

class InvestorList extends Component
{
    public $investors;

    public $day = null;
    public $nextDay = null;
    public $prevDay = null;

    public function mount()
    {
        $this->day = now();
        $this->prevDay = now()->subDay();
        $this->nextDay = now();

        $this->investors = auth()->user()->investors()
            ->with(['summary'])
            ->get();
    }

    public function changeDate($day)
    {
        $this->investors = auth()->user()->investors;
        $this->day = Carbon::createFromFormat('Y-m-d', $day);
        $this->prevDay = $this->day->copy()->subDay();
        $this->nextDay = $this->day->copy()->addDay();

        $summaries = InvestorSummary::whereDate('created_at', $this->day->format('Y-m-d'))
            ->whereIn('investor_id', $this->investors->pluck('id')->all())
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($this->investors as $investor) {
            $summary = $summaries->where('investor_id', $investor->id)->first();
            $investor->setRelation('summary', $summary);
        }
    }

    public function render()
    {
        return view('livewire.investor-list');
    }
}
