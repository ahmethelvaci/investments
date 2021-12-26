<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvestorRequest;
use App\Http\Requests\UpdateInvestorRequest;
use App\Models\Account;
use App\Models\Investor;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $investors = $request->user()->investors()
        //     ->with(['summary'])
        //     ->get();

        return view('pages.investors.index'
            // , [
            //     'investors' => $investors
            // ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreInvestorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvestorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Investor  $investor
     * @return \Illuminate\Http\Response
     */
    public function show(Investor $investor)
    {
        $investor->load(['summary']);
        $accounts = Account::where('investor_id', $investor->id)
            ->leftJoin('assets', 'accounts.asset_id', '=', 'assets.id')
            ->select('accounts.*')
            ->orderBy('assets.name')
            ->with([
                'asset.summary',
                'summary'
            ])
            ->get();

        return view('pages.investors.show', [
            'investor' => $investor,
            'accounts' => $accounts,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Investor  $investor
     * @return \Illuminate\Http\Response
     */
    public function edit(Investor $investor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInvestorRequest  $request
     * @param  \App\Models\Investor  $investor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvestorRequest $request, Investor $investor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Investor  $investor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Investor $investor)
    {
        //
    }
}
