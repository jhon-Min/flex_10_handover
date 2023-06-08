<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;
use App\Models\MarketIntel;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\FacadesValidator;
use Illuminate\Support\Str;

class MarketIntelController extends Controller
{
    public $MARKET_INTEL_IMAGE_PATH;

    public function __construct()
    {
        $this->MARKET_INTEL_IMAGE_PATH = Config::get('constant.MARKET_INTEL_IMAGE_PATH');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['market_intels'] = MarketIntel::orderBy('id', 'DESC')->get();
        return view('marketintel.market_intel', $data);
    }

    public function getMarketIntelDatatable(Request $requestl)
    {
        $market_intels = MarketIntel::orderBy('id', 'DESC')->get();
        return Datatables::of($market_intels)
            ->editColumn('image', function ($data) {
                return "<a class=\"image-popup-link\" href='" . $data->image_url . "'><img  src='" . $data->image_url . "' class=\"image-table-cell\"></a>";
                //return "<img  src='" . $data->image_url . "' class=\"image-table-cell\">";
            })
            ->editColumn('title', function ($data) {
                return Str::limit($data->title, 100);
            })
            ->editColumn('short_description', function ($data) {
                return Str::limit($data->short_description, 100);
            })
            ->editColumn('url', function ($data) {
                return Str::limit($data->url, 100);
            })
            ->editColumn('created_at', function ($data) {
                return date('d/m/Y', strtotime($data->created_at));
            })
            ->editColumn('action', function ($data) {
                $url_delete = route('market-intel.delete', ['id' => $data->id]);
                $url_edit = route('market-intel.edit', ['id' => $data->id]);
                return "<a href='" . $url_edit . "' class=\"badge badge-info color-white\"><i class=\"la la-edit\"></i></a>
                <a href=\"javascript:void(0);\" title=\"Delete\" onclick=\"confirmation_alert('Order','Delete','" . $url_delete . "')\" class=\"badge badge-danger color-white\"><i class=\"la la-trash\"></i></a>";
            })
            ->rawColumns(['image', 'title', 'description', 'url', 'created_at', 'action'])
            ->make(true);
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
    public function store(Request $request)
    {
        try {
            $rules = [
                "title" => "required|min:2",
                "description" => "required|min:2",
                "image" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:width=265,height=200",
                "short_description" => "required|min:2|max:100",
                //"image" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            ];
            if (isset($request->url) && !empty($request->url)) {
                $rules['url'] =  "url";
            }
            $validator = Validator::make($request->all(), $rules, ['image.dimensions' => 'Please upload image with required dimensions', 'image.max' => 'The image may not be greater than 5MB']);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $market_itel = [
                'title' => $request->title,
                'url' => $request->url,
                'short_description' => $request->short_description,
                'description' => $request->description,
            ];

            if ($request->hasFile('image')) {

                $file_attribue = [
                    'file' => $request->file('image'),
                    'path' => $this->MARKET_INTEL_IMAGE_PATH,
                ];
                $market_itel['image'] = Helper::fileUpload($file_attribue);
            }

            $save = MarketIntel::create($market_itel);

            if ($save->id > 0) {
                return redirect()->route('market-intels')->with(['message' => 'Market Intel Saved', 'alert-type' => 'success']);
            } else {
                return Redirect::back()->with(['message' => 'Something went wrong. try angain', 'alert-type' => 'error'])->withInput($request->all());
            }
        } catch (\Exception $e) {

            return Redirect::back()->with(['message' => $e->getMessage(), 'alert-type' => 'error'])->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MarketIntel  $marketIntel
     * @return \Illuminate\Http\Response
     */
    public function show(MarketIntel $marketIntel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MarketIntel  $marketIntel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                "id" => "required|integer|exists:market_intels,id",
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $data['market_intel'] = MarketIntel::find($id);
            return view('marketintel.market_intel_add', $data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MarketIntel  $marketIntel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarketIntel $marketIntel)
    {
        try {
            //print_r($request->all());
            //exit;

            $rules = [
                "market_intel" => "required|exists:market_intels,id",
                "title" => "required|min:2",
                "description" => "required|min:2",
                "short_description" => "required|min:2|max:100",
            ];
            if (isset($request->url) && !empty($request->url)) {
                $rules['url'] =  "url";
            }
            if ($request->hasFile('image')) {
                $rules['image'] =  "image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=265,max_height=200";
                //$rules['image'] =  "image|mimes:jpeg,png,jpg,gif,svg|max:2048";
            }

            $validator = Validator::make($request->all(), $rules, ['image.dimensions' => 'Please upload image with required dimensions', 'image.max' => 'The image may not be greater than 5MB']);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            $market_intel = MarketIntel::find($request->market_intel);
            $market_intel->title = $request->title;
            $market_intel->url = $request->url;
            $market_intel->short_description = $request->short_description;
            $market_intel->description = $request->description;
            if ($request->hasFile('image')) {
                $file_attribue = [
                    'file' => $request->file('image'),
                    'path' => $this->MARKET_INTEL_IMAGE_PATH,
                    'old_file' => $market_intel->image,
                ];
                $market_intel->image = Helper::fileUpload($file_attribue);
            }
            $save =  $market_intel->save();
            if ($save > 0) {
                return Redirect::back()->with(['message' => 'Market Intel Updated', 'alert-type' => 'success']);
            } else {
                return Redirect::back()->with(['message' => 'Something went wrong. try angain', 'alert-type' => 'error'])->withInput($request->all());
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['message' => $e->getMessage(), 'alert-type' => 'error'])->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MarketIntel  $marketIntel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                "id" => "required|integer|exists:market_intels,id",
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => implode('-', $validator->errors()->all())], 401);
            }
            $market_intels = MarketIntel::find($id);

            $is_delete = $market_intels->delete();
            if ($is_delete > 0) {
                $response = [
                    'success' => '1',
                    'message' => 'MarketIntel has been Deleted',
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
