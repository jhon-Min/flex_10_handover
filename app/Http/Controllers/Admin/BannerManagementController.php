<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Config;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Redirect;
use App\Models\BannerManagement;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerManagementReequest;
use Illuminate\Support\Facades\Validator;

class BannerManagementController extends Controller
{
    public $BANNER_IMAGE_PATH;

    public function __construct()
    {
        $this->BANNER_IMAGE_PATH = Config::get('constant.BANNER_IMAGE_PATH');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('banner.banner');
    }
    public function getBannerDatatable(Request $requestl)
    {
        $banners = BannerManagement::orderBy('id', 'DESC')->get();
        return Datatables::of($banners)
            ->editColumn('image', function ($data) {
                return "<a class=\"image-popup-link\" href='" . $data->image_url . "'><img  src='" . $data->image_url . "' class=\"img-responsive\"></a>";
                //return "<img  src='" . $data->image_url . "' class=\"image-table-cell\">";
            })
            ->editColumn('action', function ($data) {
                $url_delete = route('banner.delete', ['id' => $data->id]);
                return "<a href=\"javascript:void(0);\" title=\"Delete\" onclick=\"confirmation_alert('Order','Delete','" . $url_delete . "')\" class=\"badge badge-danger color-white\"><i class=\"la la-trash\"></i></a>";
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
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
        try {
            if ($request->hasFile('image')) {

                $file_attribue = [
                    'file' => $request->file('image'),
                    'path' => $this->BANNER_IMAGE_PATH,
                ];
                $image = Helper::fileUpload($file_attribue);
                $save = BannerManagement::create(['image' => $image]);
                if ($save->id > 0) {
                    return redirect()->route('banners')->with(['message' => 'Banner Saved', 'alert-type' => 'success']);
                }
            }
            return Redirect::back()->withErrors('Something went wrong. try angain')->withInput($request->all());
        } catch (\Exception $e) {

            return Redirect::back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                "id" => "required|integer|exists:banner_managements,id",
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => implode('-', $validator->errors()->all())], 401);
            }
            $banner = BannerManagement::find($id);

            $is_delete = $banner->delete();
            if ($is_delete > 0) {
                $response = [
                    'success' => '1',
                    'message' => 'Banner has been Deleted',
                ];
                return response()->json($response, 200);
            } else {
                return response()->json(['message' => 'some thing went wrong. Try again!'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
