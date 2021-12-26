<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;

class InvestorList extends Component
{
    public $investors;

    public $day = null;
    public $nextDay = null;
    public $prevDay = null;

    public function mount(Request $request)
    {
        $this->day = now();
        $this->prevDay = now()->subDay();

        $this->investors = $request->user()->investors()
            ->with(['summary'])
            ->get();
    }

    public function changeDate($day)
    {
        // $this->investors = request()->user()->investors()
        //     ->with(['summary'])
        //     // ->where('id', 2)
        //     // ->with(['summary' => function ($query) use ($day) {
        //     //     $query->orWhere('investor_id', 1);
        //     // }])
        //     ->get();

        // dd($this->investors);

        $this->day = Carbon::createFromFormat('Y-m-d', $day); //now()->subDay();
        // dump($this->day);
        $this->prevDay = $this->day->copy()->subDay();
        // dump($this->day);
        $this->nextDay = $this->day->copy()->addDay();
        // dump($this->day);
        // $this->day = $this->investors->first()->summary->created_at;
        // $this->prevDay = $this->day->subDay();
        // $this->nextDay = $this->day->addDay();

        // $this->reset('investors');
    }

    public function render()
    {
        return view('livewire.investor-list');
    }
}
