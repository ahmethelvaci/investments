<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;
use App\Jobs\FetchAndSetPrices;
use App\Jobs\SetInvestorSummaries;
use App\Jobs\SetSummaries;
use App\Models\Price;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // SetSummaries::dispatchSync(Price::lastPrice(1));
        // SetSummaries::dispatchSync(Price::lastPrice(2));
        // SetSummaries::dispatchSync(Price::lastPrice(3));
        // SetSummaries::dispatchSync(Price::lastPrice(4));
        // SetSummaries::dispatchSync(Price::lastPrice(5));
        // SetSummaries::dispatchSync(Price::lastPrice(6));
        // SetSummaries::dispatchSync(Price::lastPrice(7));
        // SetSummaries::dispatchSync(Price::lastPrice(8));
        // SetSummaries::dispatchSync(Price::lastPrice(9));
        // SetSummaries::dispatchSync(Price::lastPrice(10));
        // SetSummaries::dispatchSync(Price::lastPrice(11));
        // SetSummaries::dispatchSync(Price::lastPrice(12));
        // SetSummaries::dispatchSync(Price::lastPrice(13));
        // SetInvestorSummaries::dispatchSync();
        /*
        SELECT `p1`.*
        FROM `prices` p1
        RIGHT JOIN (
            SELECT `asset_id`, max(`created_at`) `created_at`
            FROM `prices`
            WHERE `created_at` <= '2021-11-28 23:59:59'
            GROUP BY `asset_id`
        ) p2 ON `p1`.`asset_id` = `p2`.`asset_id` AND `p1`.`created_at` = `p2`.`created_at` */
        // dispatch(new FetchAndSetPrices);
        return view('pages.prices.index');
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
     * @param  \App\Http\Requests\StorePriceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePriceRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show(Price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePriceRequest  $request
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePriceRequest $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        //
    }
}
