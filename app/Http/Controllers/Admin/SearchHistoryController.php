<?php

namespace App\Http\Controllers\Admin;


use DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use JavaScript;
use Carbon\Carbon;
use App\User;
use App\SearchHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $chart_state = $part_state = $vehicle_state = $company_state = $stock_state = '';
        $search_sort =  '';
        $order_sort = "";
        $stock_sort = 'desc';

        // filter values from form
        if (isset($_GET['chart_state'])) {
            $chart_state = $_GET['chart_state'];
        }

        if (isset($_GET['part_state'])) {
            $part_state = $_GET['part_state'];
        }

        if (isset($_GET['vehicle_state'])) {
            $vehicle_state = $_GET['vehicle_state'];
        }

        if (isset($_GET['company_state'])) {
            $company_state = $_GET['company_state'];
        }

        if (isset($_GET['stock_state'])) {
            $stock_state = $_GET['stock_state'];
        }

        // filter values from form
        if (isset($_GET['search_sort'])) {
            $search_sort = $_GET['search_sort'];
        }

        if (isset($_GET['order_sort']) && !empty($_GET['order_sort'])) {
            $order_sort = $_GET['order_sort'];
            $search_sort = "";
        }
        if (isset($_GET['stock_sort'])) {
            $stock_sort = $_GET['stock_sort'];
        }

        /* Chart related logic starts here */

        $chart_date = date('Y-01-01');
        $chart_date_end = date('Y-12-31');
        $start = Carbon::parse($chart_date)->startOfDay();
        $end = Carbon::parse($chart_date_end)->endOfDay();


        $prev_start_date = date("d-m-Y", strtotime("-1 year", strtotime($chart_date)));
        $prev_end_date = date("d-m-Y", strtotime("-1 year", strtotime($chart_date_end)));

        $startPrev = Carbon::parse($prev_start_date)->startOfDay();
        $endPrev = Carbon::parse($prev_end_date)->endOfDay();

        // GET ORDER TOTAL FOR SELECTED TIME PERIOD
        $orderTotal = DB::table('orders');
        $orderTotal->select(DB::raw('MONTH(orders.created_at) order_date'), DB::raw('SUM(orders.total) as order_total'));
        $orderTotal->join('users', 'users.id', '=', 'orders.user_id');
        $orderTotal->groupBy(DB::raw('MONTH(orders.created_at)'));
        $orderTotal->orderBy(DB::raw('MONTH(orders.created_at)'), 'ASC');
        $orderTotal->whereBetween('orders.created_at', [$start, $end]);

        // if (!empty($start) && !empty($end)) {
        //     $orderTotal->whereBetween('orders.created_at', [$start, $end]);
        // } else if (!empty($start)) {
        //     $orderTotal->where('orders.created_at', '>=', $start);
        // } else if (!empty($end)) {
        //     $orderTotal->where('orders.created_at', '<=', $end);
        // }

        if (!empty($chart_state)) {
            $orderTotal->where('users.state', '=', $chart_state);
        }

        $current_orders = $orderTotal->get();


        // GET ORDER TOTAL FOR PREVIOUS TIME PERIOD
        $orderTotalPrev = DB::table('orders');
        $orderTotalPrev->select(DB::raw('MONTH(orders.created_at) order_date'), DB::raw('SUM(orders.total) as order_total'));
        $orderTotalPrev->join('users', 'users.id', '=', 'orders.user_id');
        $orderTotalPrev->groupBy(DB::raw('MONTH(orders.created_at)'));
        $orderTotalPrev->orderBy(DB::raw('MONTH(orders.created_at)'), 'ASC');
        $orderTotalPrev->whereBetween('orders.created_at', [$startPrev, $endPrev]);

        // if (!empty($startPrev) && !empty($endPrev)) {
        //     $orderTotalPrev->whereBetween('orders.created_at', [$startPrev, $endPrev]);
        // } else if (!empty($startPrev)) {
        //     $orderTotalPrev->where('orders.created_at', '>=', $startPrev);
        // } else if (!empty($endPrev)) {
        //     $orderTotalPrev->where('orders.created_at', '<=', $endPrev);
        // }

        if (!empty($chart_state)) {
            $orderTotalPrev->where('users.state', '=', $chart_state);
        }

        $previous_orders = $orderTotalPrev->get();

        $array_orders_list = [];
        $current_orders_list = [];
        $previous_orders_list = [];


        foreach ($current_orders as $key => $value) {
            $current_orders_list[$value->order_date] = $value->order_total;
        }
        foreach ($previous_orders as $key1 => $value1) {
            $previous_orders_list[$value1->order_date] = $value1->order_total;
        }

        $startTime = strtotime($start);
        $endTime = strtotime($end);

        // Loop between timestamps, 24 hours at a time
        for ($i = 1; $i <= 12; $i++) {

            $date = '01-' . $i . '-01';
            $monthName = date("F", strtotime($date));
            $array_key = [$monthName];


            if (array_key_exists($i, $current_orders_list)) {
                array_push($array_key, $current_orders_list[$i]);
            } else {
                array_push($array_key, 0);
            }

            if (array_key_exists($i, $previous_orders_list)) {
                array_push($array_key, $previous_orders_list[$i]);
            } else {
                array_push($array_key, 0);
            }

            $array_orders_list[] = $array_key;
        }

        $current_order_total = round(array_sum($current_orders_list), 2);
        $previous_order_total = round(array_sum($previous_orders_list), 2);

        $current_order_total = number_format($current_order_total, 2, ".", ",");
        $previous_order_total = number_format($previous_order_total, 2, ".", ",");

        $data['array_orders'] = json_encode($array_orders_list);
        JavaScript::put([
            'current_order_total' => $current_order_total,
            'previous_order_total' => $previous_order_total,
        ]);


        /* Chart related logic ends here */

        // get default date values
        $chart_date_end = date('d-m-Y');
        $chart_date = date('d-m-Y', strtotime("-30 days", strtotime($chart_date_end)));

        if (isset($_GET['chart_date'])) {
            $chart_date = $_GET['chart_date'];
        }

        if (isset($_GET['chart_date_end'])) {
            $chart_date_end = $_GET['chart_date_end'];
        }

        // $start_date = $chart_date;
        // $end_date = $chart_date_end;

        $data_start = Carbon::parse($chart_date)->startOfDay();
        $data_end = Carbon::parse($chart_date_end)->endOfDay();

        $data['chart_date'] = $chart_date;
        $data['chart_date_end'] = $chart_date_end;

        // get part number search from database
        $partSearch = DB::table('search_histories');
        $partSearch->join('users', 'search_histories.user_id', '=', 'users.id');
        $partSearch->select('search_histories.part_number', DB::raw('count(search_histories.id) as part_number_count'));
        $partSearch->where('search_histories.search_type', '=', 'part');
        if (!empty($part_state)) {
            $partSearch->where('search_histories.state', '=', $part_state);
        }

        if (!empty($data_start) && !empty($data_end)) {
            $partSearch->whereBetween('search_histories.created_at', [$data_start, $data_end]);
        }
        $partSearch->groupBy('search_histories.part_number');
        $partSearch->orderBy('part_number_count', 'DESC');
        $part_result = $partSearch->get();

        $part_number_count = 0;
        foreach ($part_result as $key => $value) {
            $part_number_count += $value->part_number_count;
        }


        // get vehicle search from database
        $vehicleSearch = DB::table('search_histories');
        $vehicleSearch->join('users', 'search_histories.user_id', '=', 'users.id');
        $vehicleSearch->join('makes', 'search_histories.make_id', '=', 'makes.id');
        $vehicleSearch->join('models', 'search_histories.model_id', '=', 'models.id');
        $vehicleSearch->select('search_histories.year', 'search_histories.make_id', 'search_histories.model_id', 'makes.name as make_name', 'models.name  as model_name', DB::raw('count(*) as vehicle_count'), DB::raw('CONCAT(search_histories.make_id, search_histories.model_id, search_histories.year) AS searchCombined'));
        $vehicleSearch->where('search_histories.search_type', '=', 'vehicle');
        if (!empty($vehicle_state)) {
            $vehicleSearch->where('search_histories.state', '=', $vehicle_state);
        }

        if (!empty($data_start) && !empty($data_end)) {
            $vehicleSearch->whereBetween('search_histories.created_at', [$data_start, $data_end]);
        }
        $vehicleSearch->groupBy('searchCombined');
        $vehicleSearch->orderBy('vehicle_count', 'DESC');
        $vehicle_result = $vehicleSearch->get();

        $vehicle_number_count = 0;
        foreach ($vehicle_result as $key1 => $value1) {
            $vehicle_number_count += $value1->vehicle_count;
        }


        // company searches versus orders
        $companySearch = DB::table('search_histories');
        $companySearch->join('users', 'search_histories.user_id', '=', 'users.id');
        $companySearch->select('users.company_name', 'search_histories.state', DB::raw('count(search_histories.id) as company_count'));
        if (!empty($company_state)) {
            $companySearch->where('search_histories.state', '=', $company_state);
        }

        if (!empty($data_start) && !empty($data_end)) {
            $companySearch->whereBetween('search_histories.created_at', [$data_start, $data_end]);
        }
        $companySearch->groupBy('users.company_name');
        $companySearch->groupBy('search_histories.state');
        if (!empty($search_sort)) {
            $companySearch->orderBy('company_count', $search_sort);
        } else {
            $companySearch->orderBy('company_count', 'desc');
        }

        $company_result = $companySearch->get();

        // company orders
        $orderSearch = DB::table('orders');
        $orderSearch->join('users', 'users.id', '=', 'orders.user_id');
        // $orderSearch->join('search_histories', 'users.id', '=', 'search_histories.user_id');
        $orderSearch->select('users.company_name', 'users.state', DB::raw('count(orders.id) as order_count'));
        if (!empty($company_state)) {
            $orderSearch->where('users.state', '=', $company_state);
        }

        if (!empty($data_start) && !empty($data_end)) {
            $orderSearch->whereBetween('orders.created_at', [$data_start, $data_end]);
        }
        $orderSearch->groupBy('users.company_name');
        $orderSearch->groupBy('users.state');
        // $orderSearch->orderBy('users.company_name', 'ASC');
        if (!empty($order_sort)) {
            $orderSearch->orderBy('order_count', $order_sort);
        }
        $order_result = $orderSearch->get();

        $array_company_search = [];
        $array_company_search_order = [];
        $array_company_order = [];
        foreach ($company_result as $key => $value) {
            $array_company_search[$value->company_name][$value->state]['search'] = $value->company_count;
        }

        foreach ($order_result as $key1 => $value1) {
            $array_company_order[$value1->company_name][$value1->state]['order'] = $value1->order_count;
        }


        if (isset($search_sort) && !empty($search_sort)) {
            foreach ($array_company_search as $company_name => $search_data) {
                if (array_key_exists($company_name, $array_company_order)) {
                    foreach ($search_data as $state => $searchCnt) {
                        if (array_key_exists($state, $array_company_order[$company_name])) {
                            $array_company_search_order[$company_name][$state]['search'] = $searchCnt['search'];
                            $array_company_search_order[$company_name][$state]['order'] = $array_company_order[$company_name][$state]['order'];
                        }
                    }
                }
            }
        } else {
            $array_company_search_order = array_merge_recursive($array_company_order, $array_company_search);

            foreach ($array_company_search_order as $company_name => $search_data) {
                foreach ($search_data as $state => $searchCnt) {
                    if (count($searchCnt) < 2) {
                        unset($array_company_search_order[$company_name][$state]);
                    }
                }
                if (empty($array_company_search_order[$company_name])) {
                    unset($array_company_search_order[$company_name]);
                }
            }
        }


        $stockSearch = DB::table('search_histories');
        $stockSearch->leftJoin('makes', 'search_histories.make_id', '=', 'makes.id');
        $stockSearch->leftJoin('models', 'search_histories.model_id', '=', 'models.id');
        $stockSearch->select('search_histories.part_number', 'search_histories.search_type', 'search_histories.year', 'search_histories.make_id', 'search_histories.model_id', 'makes.name as make_name', 'models.name  as model_name', DB::raw('count(search_histories.id) as no_stock_count'));
        $stockSearch->where('in_stock', '=', 0);
        if (!empty($stock_state)) {
            $stockSearch->where('search_histories.state', '=', $stock_state);
        }

        if (!empty($data_start) && !empty($data_end)) {
            $stockSearch->whereBetween('search_histories.created_at', [$data_start, $data_end]);
        }
        $stockSearch->groupBy('part_number');
        $stockSearch->groupBy('year');
        $stockSearch->groupBy('make_id');
        $stockSearch->groupBy('model_id');
        if (!empty($stock_sort)) {
            $stockSearch->orderBy('no_stock_count', $stock_sort);
        } else {
            $stockSearch->orderBy('no_stock_count', 'desc');
        }
        $stock_result = $stockSearch->get();



        // GET ORDER TOTAL FOR SELECTED TIME PERIOD
        $orderTotalDate = DB::table('orders');
        $orderTotalDate->select("orders.*");
        $orderTotalDate->join('users', 'users.id', '=', 'orders.user_id');
        // $orderTotalDate->groupBy(DB::raw('MONTH(orders.created_at)'));
        // $orderTotalDate->orderBy(DB::raw('MONTH(orders.created_at)'), 'ASC');
        // $orderTotalDate->whereBetween('orders.created_at', [$start, $end]);

        if (!empty($data_start) && !empty($data_end)) {
            $orderTotalDate->whereBetween('orders.created_at', [$data_start, $data_end]);
        }

        // if (!empty($start) && !empty($end)) {
        //     $orderTotal->whereBetween('orders.created_at', [$start, $end]);
        // } else if (!empty($start)) {
        //     $orderTotal->where('orders.created_at', '>=', $start);
        // } else if (!empty($end)) {
        //     $orderTotal->where('orders.created_at', '<=', $end);
        // }

        if (!empty($chart_state)) {
            // $orderTotalDate->where('users.state', '=', $chart_state);
        }

        $current_orders_date = $orderTotalDate->get();

        $order_total_amount = 0;

        foreach ($current_orders_date as $key_order => $value_order) {
            $order_total_amount += $value_order->total;
        }

        $data['search_sort'] = $search_sort;
        $data['order_sort'] = $order_sort;
        $data['stock_sort'] = $stock_sort;

        $data['part_searches'] = $part_result;
        $data['part_max'] = !$part_result->isEmpty() ? $part_result[0]->part_number_count : NULL;
        $data['vehicle_searches'] = $vehicle_result;
        $data['vehicle_max'] = !$vehicle_result->isEmpty() ? $vehicle_result[0]->vehicle_count : NULL;

        $data['company_searches'] = $array_company_search_order;
        $data['stock_searches'] = $stock_result;
        $data['chart_state'] = $chart_state;
        $data['part_state'] = $part_state;
        $data['vehicle_state'] = $vehicle_state;
        $data['company_state'] = $company_state;
        $data['stock_state'] = $stock_state;
        $data['empty_company_search'] = !empty($array_company_search_order) ? TRUE : FALSE;
        $data['previous_order_total'] = $previous_order_total;
        $data['current_order_total'] = $current_order_total;

        $data['total_search'] = $part_number_count + $vehicle_number_count;
        $data['order_total_amount'] = $order_total_amount;
        $data['order_total_count'] = count($current_orders_date);

        return view('analytics-dashboard.analytics-dashboard', $data);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function show(SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SearchHistory $searchHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SearchHistory  $searchHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SearchHistory $searchHistory)
    {
        //
    }
}
