<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreMarketIntelRequest;
use App\Http\Requests\UpdateMarketIntelRequest;
use App\Models\MarketIntel;
use App\Http\Controllers\Controller;
use App\Services\Admin\MarketIntelService;

class MarketIntelController extends Controller
{
    protected $marketIntelService;

    public function __construct(MarketIntelService $marketIntelService)
    {
        $this->marketIntelService = $marketIntelService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('marketintel.market_intel');
    }

    public function getMarketIntelDatatable()
    {
        return $this->marketIntelService->tableList();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('marketintel.market_intel_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMarketIntelRequest $request)
    {
        return $this->marketIntelService->marketStore($request);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MarketIntel  $marketIntel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return $this->marketIntelService->marketEdit($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MarketIntel  $marketIntel
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMarketIntelRequest $request, MarketIntel $marketIntel)
    {
        return $this->marketIntelService->marketUpdate($request, $marketIntel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MarketIntel  $marketIntel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->marketIntelService->marketDestroy($id);
    }
}
