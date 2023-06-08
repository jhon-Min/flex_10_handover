<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Product;
use App\ProductQuantity;
use App\Branch;
use App\ProductImportStatus;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Repositories\ProductsRepository;
use Redirect, Response, File;
use App\Document;
use App\CustomerUser;
use DB;
use App\Jobs\ImportProductStock;
use App\Jobs\ImportProductPrice;
use DateTime;

class ProductController extends Controller
{
	public function uploadProductCsv(Request $request)
	{
		ini_set('max_execution_time', '0');
		request()->validate([
			'fileToUpload'  => 'required|mimes:txt,csv',
		]);

		if ($files = $request->file('fileToUpload')) {
			$filename = $files->getClientOriginalName();
			$temp = explode(".", $filename);
			$location = 'uploads/products-stock';
			$files->move($location, $filename);
			$filepath = public_path($location . "/" . $filename);
			$this->dispatch(new ImportProductStock($filepath, $temp[0]));
		}
		return Response()->json([
			"success" => true,
			"message" => "stock imported"
		]);
	}

	public function importProductPriceCsv(Request $request)
	{

		ini_set('max_execution_time', '0');
		request()->validate([
			'fileToUploadPrice'  => 'required|mimes:txt,csv',
			'fileToUploadPrice1'  => 'required|mimes:txt,csv',
		]);

		$files1 = $request->file('fileToUploadPrice1');

		if ($files = $request->file('fileToUploadPrice')) {
			$filename = $files->getClientOriginalName();
			$temp = explode(".", $filename);
			$location = 'uploads/products-price';
			$files->move($location, $filename);
			$filepath = public_path($location . "/" . $filename);

			// // File1.	
			$filepath1 = '';
			$temp1[0] = '';
			$filename1 = $files1->getClientOriginalName();
			$temp1 = explode(".", $filename1);
			$location1 = 'uploads/products-price';
			$files1->move($location1, $filename1);
			$filepath1 = public_path($location1 . "/" . $filename1);

			// custom validation for csv file data customer category
			$array_customer_header = ['Customer', 'Company name', 'Category'];
			$array_customer_csv = [];
			$csv_data = fopen($filepath, "r");
			while (($filedata = fgetcsv($csv_data, 1000, ",")) !== FALSE) {

				$array_customer_csv = $filedata;
				break;
			}

			if ($array_customer_csv !== $array_customer_header) {
				return Response()->json([
					"success" => false,
					"message" => "Customer CSV is not correct!"
				]);
			}

			// custom validation for csv file data customer category
			$array_price_header = ['PART NUMBER', 'GROUP', 'CUST PRICE', 'RET PRICE', 'CATEGORY'];
			$array_price_csv = [];
			$csv_data1 = fopen($filepath1, "r");
			while (($filedata1 = fgetcsv($csv_data1, 1000, ",")) !== FALSE) {

				$array_price_csv = $filedata1;
				break;
			}

			if ($array_price_csv !== $array_price_header) {
				return Response()->json([
					"success" => false,
					"message" => "Price CSV is not correct!"
				]);
			}

			$userQuery =  CustomerUser::with(['user']);
			$users = $userQuery->get();

			$user_list = [];
			foreach ($users as $user) {
				if (!empty($user->account_code)) {
					$user_list[$user->id] = $user->account_code;
				}
			}
			$this->dispatch(new ImportProductPrice($filepath, $temp[0], $filepath1, $temp1[0], $user_list));
		}
		return Response()->json([
			"success" => true,
			"message" => "price imported"
		]);
	}

	public function checkImportStatus(Request $request)
	{
		$curentImportStatus = ProductImportStatus::where('import_type', '=', 'stock')->orderBy('start_time', 'DESC')->first();
		$errorFilePath = "";
		$totalStockRecord = 0;
		$successStockRecord = 0;
		$importProcessDur = "";
		$startTimeP = "";
		$endTimeP = "";
		$error_file_name = "";
		$uploadedStockFile = "";
		if ($curentImportStatus->status == "Error") {
			$error_file_name = basename($curentImportStatus->error_file_path);
			$errorFilePath = "product/download/" . basename($curentImportStatus->error_file_path);
			$totalStockRecord = $curentImportStatus->total_records;
			$failedStockRecord = $curentImportStatus->error_records;
			$successStockRecord = $totalStockRecord - $failedStockRecord;
		} else if ($curentImportStatus->status == "Success") {
			$totalStockRecord = $curentImportStatus->total_records;
			$successStockRecord = $totalStockRecord;
		}

		if (in_array($curentImportStatus->status, array("Error", "Success"))) {
			$importProcessDur = $this->getTimeIntervalDiff($curentImportStatus->start_time, $curentImportStatus->end_time);
			$endTimeP = $curentImportStatus->end_time;
		}
		$startTimeP = $curentImportStatus->start_time;
		$uploadedStockFile = basename($curentImportStatus->file_path);
		return Response()->json([
			"success" => true,
			"status_msg" => $curentImportStatus->status,
			"error_file_path" => $errorFilePath,
			"totalStockRecord" => $totalStockRecord,
			"successStockRecord" => $successStockRecord,
			"importProcessDur" => $importProcessDur,
			"end_time" => $endTimeP,
			"start_time" => $startTimeP,
			"uploadedStockFile" => $uploadedStockFile,
		]);
	}

	public function checkPriceImportStatus(Request $request)
	{
		$curentImportStatus = ProductImportStatus::where('import_type', '=', 'price')->orderBy('start_time', 'DESC')->first();
		$errorFilePath = "";
		$totalStockRecord = 0;
		$successStockRecord = 0;
		$importProcessDur = "";
		$startTimeP = "";
		$endTimeP = "";
		$error_file_name = "";
		$uploadedStockFile = "";
		if ($curentImportStatus->status == "Error") {
			$error_file_name = basename($curentImportStatus->error_file_path);
			$errorFilePath = "product/download/" . basename($curentImportStatus->error_file_path);
			$totalStockRecord = $curentImportStatus->total_records;
			$failedStockRecord = $curentImportStatus->error_records;
			$successStockRecord = $totalStockRecord - $failedStockRecord;
		} else if ($curentImportStatus->status == "Success") {
			$totalStockRecord = $curentImportStatus->total_records;
			$successStockRecord = $totalStockRecord;
		}

		if (in_array($curentImportStatus->status, array("Error", "Success"))) {
			$importProcessDur = $this->getTimeIntervalDiff($curentImportStatus->start_time, $curentImportStatus->end_time);
			$endTimeP = $curentImportStatus->end_time;
		}
		$startTimeP = $curentImportStatus->start_time;
		$uploadedStockFile = basename($curentImportStatus->file_path);
		$uploadedStockFile .= ', ' . basename($curentImportStatus->file_path1);
		return Response()->json([
			"success" => true,
			"status_msg" => $curentImportStatus->status,
			"error_file_path" => $errorFilePath,
			"totalStockRecord" => $totalStockRecord,
			"successStockRecord" => $successStockRecord,
			"importProcessDur" => $importProcessDur,
			"end_time" => $endTimeP,
			"start_time" => $startTimeP,
			"uploadedPriceFile" => $uploadedStockFile,
		]);
	}

	private function getTimeIntervalDiff($startTime, $endTime)
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
