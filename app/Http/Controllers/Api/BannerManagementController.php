<?php

namespace App\Http\Controllers\Api;

use Config;
use Helper;
use Validator;
use App\BannerManagement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class BannerManagementController extends BaseController
{
    /**
     * @group Banner Managemant
     * Banner
     * @queryParam page (Integer) page number
     * @queryParam per_page (Integer) items per page
     * @queryParam paginate (bool) whather data arranged by pagination or not
     * @response {
     *       "success": true,
     *       "data": {
     *           "current_page": 1,
     *           "data": [
     *               {
     *                   "id": 13,
     *                   "image": "Screenshot_from_2019-11-01_11-15-48_1572944697.jpg",
     *                   "created_at": "2019-11-05 09:04:57",
     *                   "image_url": "http://flexibledrive.localhost.com/storage/banners/Screenshot_1572944697.jpg"
     *               }
     *           ],
     *           "first_page_url": "http://flexibledrive.localhost.com/api/banner?page=1",
     *           "from": 1,
     *           "last_page": 1,
     *           "last_page_url": "http://flexibledrive.localhost.com/api/banner?page=1",
     *           "next_page_url": null,
     *           "path": "http://flexibledrive.localhost.com/api/banner",
     *           "per_page": 15,
     *           "prev_page_url": null,
     *           "to": 3,
     *           "total": 3
     *       },
     *       "message": "Banners"
     *   }
     * 
     * 
     * 
     **/
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
            if ($paginate) {
                $banners = BannerManagement::orderBy('id', 'DESC')->paginate($per_page);
            } else {
                $banners = BannerManagement::orderBy('id', 'DESC')->get();
            }
            return $this->sendResponse($banners, "Banners");
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
