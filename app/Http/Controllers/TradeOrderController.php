<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTradeOrderRequest;
use App\Http\Requests\UpdateTradeOrderRequest;
use App\Models\TradeOrder;

class TradeOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreTradeOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTradeOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TradeOrder  $tradeOrder
     * @return \Illuminate\Http\Response
     */
    public function show(TradeOrder $tradeOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TradeOrder  $tradeOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(TradeOrder $tradeOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTradeOrderRequest  $request
     * @param  \App\Models\TradeOrder  $tradeOrder
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTradeOrderRequest $request, TradeOrder $tradeOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TradeOrder  $tradeOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(TradeOrder $tradeOrder)
    {
        //
    }
}
