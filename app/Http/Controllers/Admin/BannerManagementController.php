<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerManagementReequest;
use App\Services\Admin\BannerManagementService;

class BannerManagementController extends Controller
{
    protected $bannerManagementService;

    public function __construct(BannerManagementService $bannerManagementService)
    {
        $this->bannerManagementService = $bannerManagementService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('banner.banner');
    }
    public function getBannerDatatable()
    {
        return $this->bannerManagementService->tableLists();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('banner.banner_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBannerManagementReequest $request)
    {
        return $this->bannerManagementService->bannerStore($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->bannerManagementService->bannerDestroy($id);
    }
}
