<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\MarketIntel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class MarketIntelController extends BaseController
{

    /**
     * @group Market Intel
     * Maket Intel 
     * @queryParam page (Integer) page number
     * @queryParam per_page (Integer) items per page
     * @queryParam paginate (bool) whather data arranged by pagination or not
     * @response {
     *    "success": true,
     *   "data": {
     *       "current_page": 1,
     *       "data": [
     *           {
     *               "id": 1,
     *               "title": "test blog demo test",
     *               "blog_slug": "test-blog-demo-test",
     *               "description": "<p>test</p>",
     *               "url": null,
     *               "image": "maxresdefault_1572864536.jpg",
     *               "created_at": "2019-11-04 10:48:56",
     *               "updated_at": "2019-11-04 10:48:56",
     *               "deleted_at": null,
     *               "image_url": "http://flexibledrive.localhost.com/storage/marketintel/maxresdefault_1572864536.jpg"
     *           }
     *       ],
     *       "first_page_url": "http://flexibledrive.localhost.com/api/market-intel?page=1",
     *       "from": 1,
     *       "last_page": 1,
     *       "last_page_url": "http://flexibledrive.localhost.com/api/market-intel?page=1",
     *       "next_page_url": null,
     *       "path": "http://flexibledrive.localhost.com/api/market-intel",
     *       "per_page": 15,
     *       "prev_page_url": null,
     *       "to": 1,
     *       "total": 1
     *   },
     *   "message": "Market intels"
     * }
     * 
     */
    public function index(Request $request)
    {
        try {
            $paginate = $request->input('paginate', true);
            $validator = Validator::make($request->all(), [
                "page" => "sometimes|required|integer",
                "per_page" => "sometimes|required|integer",
            ]);
            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }
            $per_page = ($request->per_page) ? $request->per_page : 15;
            if($paginate) {
                $market_intels = MarketIntel::orderBy('id', 'DESC')->paginate($per_page);
            } else {
                $market_intels = MarketIntel::orderBy('id', 'DESC')->get();
            }
            return $this->sendResponse($market_intels, "Market intels");
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }


    /**
     * @group Market Intel
     * Market Intel Detail
     * @queryParam slug required (String) slug of wich stored in blog_slug column  (Table:market_intels)
     * @response {
     *       "success": true,
     *       "data": {
     *           "id": 1,
     *           "title": "test blog demo test",
     *           "blog_slug": "test-blog-demo-test",
     *           "description": "<p>test</p>",
     *           "url": null,
     *           "image": "maxresdefault_1572864536.jpg",
     *           "created_at": "2019-11-04 10:48:56",
     *           "updated_at": "2019-11-04 10:48:56",
     *           "deleted_at": null,
     *           "image_url": "http://flexibledrive.localhost.com/storage/marketintel/maxresdefault_1572864536.jpg"
     *       },
     *       "message": "Market intel detail"
     * }
     */
    public function show($slug)
    {
        try {
            $market_intels = MarketIntel::findBySlug($slug);
            return $this->sendResponse($market_intels, "Market intel detail");
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
