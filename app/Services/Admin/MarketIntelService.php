<?php

namespace App\Services\Admin;

use App\Helpers\Helper;
use App\Models\MarketIntel;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class MarketIntelService
{
    public $MARKET_INTEL_IMAGE_PATH;

    public function __construct()
    {
        $this->MARKET_INTEL_IMAGE_PATH = Config::get('constant.MARKET_INTEL_IMAGE_PATH');
    }

    public function tableList()
    {
        $market_intels = MarketIntel::query()->orderBy('id', 'DESC');
        return Datatables::of($market_intels)
            ->addColumn('image', function ($data) {
                return "<a class=\"image-popup-link\" href='" . $data->image_url . "'><img  src='" . $data->image_url . "' class=\"image-table-cell\"></a>";
                //return "<img  src='" . $data->image_url . "' class=\"image-table-cell\">";
            })
            ->editColumn('title', function ($data) {
                return Str::limit($data->title, 100);
            })
            ->addColumn('short_description', function ($data) {
                return Str::limit($data->short_description, 100);
            })
            ->editColumn('url', function ($data) {
                return Str::limit($data->url, 100);
            })
            ->editColumn('created_at', function ($data) {
                return date('d/m/Y', strtotime($data->created_at));
            })
            ->addColumn('action', function ($data) {
                $url_delete = route('market-intel.delete', ['id' => $data->id]);
                $url_edit = route('market-intel.edit', ['id' => $data->id]);
                return "<a href='" . $url_edit . "' class=\"badge badge-info color-white\"><i class=\"la la-edit\"></i></a>
                <a href=\"javascript:void(0);\" title=\"Delete\" onclick=\"confirmation_alert('Order','Delete','" . $url_delete . "')\" class=\"badge badge-danger color-white\"><i class=\"la la-trash\"></i></a>";
            })
            ->rawColumns(['image', 'title', 'description', 'url', 'created_at', 'action'])
            ->only(['image', 'title', 'description', 'url', 'created_at', 'action', 'short_description'])
            ->make(true);
    }

    public function marketStore($request)
    {
        try {

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

    public function marketEdit($id)
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

    public function marketUpdate($request, $marketIntel)
    {
        try {
            //print_r($request->all());
            //exit;
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

    public function marketDestroy($id)
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
