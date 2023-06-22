<?php

namespace App\Services\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Make;
use App\Models\Models;
use App\Models\Vehicle;
use App\Models\ProductImportStatus;
use DateTime;


class HomeService
{
    public function dashboardList()
    {
        $data = [];
        $data['total_brands'] = Brand::count();
        $data['total_categories'] = Category::count();
        $data['total_products'] = Product::count();
        $data['total_orders'] = Order::count();
        $data['total_users'] =  User::whereHas('roles', function ($query) {
            $query->whereNotIn('name', ['Super Admin', 'Admin']);
        })->count();
        $data['total_makes'] = Make::count();
        $data['total_models'] = Models::count();
        $data['total_vehicles'] = Vehicle::count();

        $curentImportStatus = ProductImportStatus::where('import_type', '=', 'stock')->orderBy('start_time', 'DESC')->first();
        $errorFilePath = "";
        $totalStockRecord = 0;
        $successStockRecord = 0;
        $importProcessDur = "";

        if (!empty($curentImportStatus)) {
            if ($curentImportStatus->status == "Error") {
                $error_file_name = basename($curentImportStatus->error_file_path);
                $errorFilePath = "/product/download/" . basename($curentImportStatus->error_file_path);
                $totalStockRecord = $curentImportStatus->total_records;
                $failedStockRecord = $curentImportStatus->error_records;
                $successStockRecord = $totalStockRecord - $failedStockRecord;
            } else if ($curentImportStatus->status == "Success") {
                $totalStockRecord = $curentImportStatus->total_records;
                $successStockRecord = $totalStockRecord;
            }
            if (in_array($curentImportStatus->status, array("Error", "Success"))) {
                $importProcessDur = $this->timeIntervalDiff($curentImportStatus->start_time, $curentImportStatus->end_time);
            }
        }

        $data['stock_import_status'] = $curentImportStatus;
        $data['errorFilePath'] = $errorFilePath;
        $data['totalStockRecord'] = $totalStockRecord;
        $data['successStockRecord'] = $successStockRecord;
        $data['importProcessDur'] = $importProcessDur;


        // price upload data
        $curentImportStatus = ProductImportStatus::where('import_type', '=', 'price')->orderBy('start_time', 'DESC')->first();
        $errorFilePath = "";
        $totalStockRecord = 0;
        $successStockRecord = 0;
        $importProcessDur = "";
        if (!empty($curentImportStatus)) {

            if ($curentImportStatus->status == "Error") {
                $error_file_name = basename($curentImportStatus->error_file_path);
                $errorFilePath = "/product/download/" . basename($curentImportStatus->error_file_path);
                $totalStockRecord = $curentImportStatus->total_records;
                $failedStockRecord = $curentImportStatus->error_records;
                $successStockRecord = $totalStockRecord - $failedStockRecord;
            } else if ($curentImportStatus->status == "Success") {
                $totalStockRecord = $curentImportStatus->total_records;
                $successStockRecord = $totalStockRecord;
            }
            if (in_array($curentImportStatus->status, array("Error", "Success"))) {
                $importProcessDur = $this->timeIntervalDiff($curentImportStatus->start_time, $curentImportStatus->end_time);
            }
        }

        $data['price_import_status'] = $curentImportStatus;
        $data['errorPriceFilePath'] = $errorFilePath;
        $data['totalPriceRecord'] = $totalStockRecord;
        $data['successPriceRecord'] = $successStockRecord;
        $data['importPriceProcessDur'] = $importProcessDur;

        return view('dashboard', $data);
    }

    public function timeIntervalDiff($startTime, $endTime)
    {
        $end_timeP = new DateTime($endTime);
        $start_timeP = new DateTime($startTime);
        $interval = date_diff($end_timeP, $start_timeP);
        $returnString = "";
        if (!empty($interval->h)) {
            $stringH = "Hour";
            if ($interval->h > 1) {
                $stringH = "Hours";
            }
            $returnString .= $interval->h . " " . $stringH . " ";
        }
        if (!empty($interval->i)) {
            $stringM = "Minute";
            if ($interval->i > 1) {
                $stringM = "Minutes";
            }
            $returnString .= $interval->i . " " . $stringM . " ";
        }
        if (!empty($interval->s)) {
            $stringS = "Second";
            if ($interval->s > 1) {
                $stringS = "Seconds";
            }
            $returnString .= $interval->s . " " . $stringS;
        }
        if (empty($returnString)) {
            $returnString = "2 Seconds";
        }
        return $returnString;
    }
}
