@extends('layouts.app')

@section('content')
    <div class="content">
        <header class="page-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </header>
        <section class="page-content container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="row m-0 col-border-xl">
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <div class="card-body">
                                    <div class="icon-rounded icon-rounded-primary float-left m-r-20">
                                        <i class="zmdi zmdi-car zmdi-hc-fw"></i>
                                    </div>
                                    <h5 class="card-title m-b-5 counter" data-count="{{ $total_makes }}">0</h5>
                                    <h6 class="text-muted m-t-10">
                                        Total Makes
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <div class="card-body">
                                    <div class="icon-rounded icon-rounded-accent float-left m-r-20">
                                        <i class="zmdi zmdi-car zmdi-hc-fw"></i>
                                    </div>
                                    <h5 class="card-title m-b-5 counter" data-count="{{ $total_models }}">0</h5>
                                    <h6 class="text-muted m-t-10">
                                        Total Models
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <div class="card-body">
                                    <div class="icon-rounded icon-rounded-info float-left m-r-20">
                                        <i class="zmdi zmdi-car zmdi-hc-fw"></i>
                                    </div>
                                    <h5 class="card-title m-b-5 counter" data-count="{{ $total_vehicles }}">0</h5>
                                    <h6 class="text-muted m-t-10">
                                        Total Vehicles
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <div class="card-body">
                                    <div class="icon-rounded icon-rounded-success float-left m-r-20">
                                        <i class="la la-cogs"></i>
                                    </div>
                                    <h5 class="card-title m-b-5 counter" data-count="{{ $total_products }}">0</h5>
                                    <h6 class="text-muted m-t-10">
                                        Total Products
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="row m-0 col-border-xl">
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <div class="card-body">
                                    <div class="icon-rounded icon-rounded-primary float-left m-r-20">
                                        <i class="la la-certificate"></i>
                                    </div>
                                    <h5 class="card-title m-b-5 counter" data-count="{{ $total_brands }}">0</h5>
                                    <h6 class="text-muted m-t-10">
                                        Total Brands
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <div class="card-body">
                                    <div class="icon-rounded icon-rounded-accent float-left m-r-20">
                                        <i class="zmdi zmdi-wrench zmdi-hc-fw"></i>
                                    </div>
                                    <h5 class="card-title m-b-5 counter" data-count="{{ $total_categories }}">0</h5>
                                    <h6 class="text-muted m-t-10">
                                        Total Categories
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <div class="card-body">
                                    <div class="icon-rounded icon-rounded-info float-left m-r-20">
                                        <i class="zmdi zmdi-shopping-cart zmdi-hc-fw"></i>
                                    </div>
                                    <h5 class="card-title m-b-5 counter" data-count="{{ $total_orders }}">0</h5>
                                    <h6 class="text-muted m-t-10">
                                        Total Orders
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-6 col-xl-3">
                                <div class="card-body">
                                    <div class="icon-rounded icon-rounded-success float-left m-r-20">
                                        <i class="zmdi zmdi-account-circle zmdi-hc-fw"></i>
                                    </div>
                                    <h5 class="card-title m-b-5 counter" data-count="{{ $total_users }}">0</h5>
                                    <h6 class="text-muted m-t-10">
                                        Total Users
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h1>Bulk Upload</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-xl-6 col-md-12">
                    <div class="card">
                        <div class="row m-0 col-border-xl">
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <div class="card-body">
                                    <h3>Stock</h3>
                                    <form method="post" enctype="multipart/form-data" id="productStockUploadForm"
                                        style="margin:20px 0;border: solid 2px;padding: 10px;">
                                        @if (@$stock_import_status->status == 'In Progress')
                                            <input type="file" accept=".csv" name="fileToUpload" id="csvToUpload"
                                                disabled>
                                            <div style="width:100%;margin-top:10px;">
                                                <button disabled style="display: inline-block;" id="uplad-button"
                                                    type="submit" class="btn btn-primary">Submit</button>
                                                <button id="status-button" type="button" class="btn btn-primary">Check
                                                    Import Status</button>
                                            </div>
                                        @else
                                            <input type="file" accept=".csv" name="fileToUpload" id="csvToUpload">
                                            <div style="width:100%;margin-top:10px;">
                                                <button style="display: inline-block;" id="uplad-button" type="submit"
                                                    class="btn btn-primary">Submit</button>
                                                <button style="display: none;" id="status-button" type="button"
                                                    class="btn btn-primary">Check Import Status</button>
                                            </div>
                                        @endif
                                    </form>
                                    <div style="display: none;" id="inProgressAlertFirst" class="alert alert-primary"
                                        role="alert">
                                        <strong>Upload process started…</strong>
                                    </div>
                                    <div style="{{ @$stock_import_status->status == 'In Progress' ? '' : 'display: none;' }}"
                                        id="inProgressAlert" class="alert alert-primary" role="alert">
                                        <strong>Upload process started…</strong>
                                        <br>File: <span
                                            class="stockUFileName">{{ basename(@$stock_import_status->file_path) }}</span>
                                        <br>Start time: <span
                                            class="processStartTime">{{ @$stock_import_status->start_time }}</span> UTC
                                    </div>
                                    <div class="alert alert-success" role="alert"
                                        style="{{ @$stock_import_status->status == 'Success' ? '' : 'display: none;' }}"
                                        id="successAlert">
                                        <strong>Upload is completed. </strong>
                                        <br>File: <span
                                            class="stockUFileName">{{ basename(@$stock_import_status->file_path) }}</span>
                                        <br>Start time: <span
                                            class="processStartTime">{{ @$stock_import_status->start_time }}</span> UTC
                                        <br>End time: <span class="processEndTime">
                                            {{ @$stock_import_status->end_time }}</span> UTC
                                        <br>Total run time: <span class="processTotTime">
                                            {{ @$importProcessDur }}</span>
                                        <br><span class="successStockRecord">{{ @$successStockRecord }}</span> of <span
                                            class="totalStockRecord">{{ @$totalStockRecord }}</span> records successfully
                                        processed.
                                        <br><span>No errors found.</span>
                                    </div>
                                    <div class="alert alert-danger" role="alert"
                                        style="{{ @$stock_import_status->status == 'Error' ? '' : 'display: none;' }}"
                                        id="errorAlert">
                                        <strong>Upload is completed. </strong>
                                        <br>File: <span
                                            class="stockUFileName">{{ basename(@$stock_import_status->file_path) }}</span>
                                        <br>Start time: <span
                                            class="processStartTime">{{ @$stock_import_status->start_time }}</span> UTC
                                        <br>End time: <span class="processEndTime">
                                            {{ @$stock_import_status->end_time }}</span> UTC
                                        <br>Total run time: <span class="processTotTime">
                                            {{ @$importProcessDur }}</span>
                                        <br><span class="successStockRecord">{{ @$successStockRecord }}</span> of <span
                                            class="totalStockRecord">{{ @$totalStockRecord }}</span> records successfully
                                        processed. <br>Errors found in <a id="errFilePathh"
                                            href="{{ URL::to($errorFilePath) }}" target="_blank">these records.</a>
                                        Please correct the issue and re-import these records.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-xl-6 col-md-12">
                    <div class="card">
                        <div class="row m-0 col-border-xl">
                            <div class="col-md-12 col-lg-12 col-xl-12">
                                <div class="card-body">
                                    <h3>Price</h3>
                                    <form method="post" enctype="multipart/form-data" id="productPriceUploadForm"
                                        style="margin:20px 0;border: solid 2px;padding: 10px;">
                                        @if (@$price_import_status->status == 'In Progress')
                                            <label for="csvToUploadPrice" style="min-width: 160px;">Select Customer
                                                file:</label>
                                            <input type="file" accept=".csv" name="fileToUploadPrice"
                                                id="csvToUploadPrice" disabled>
                                            <br />
                                            <label for="csvToUploadPrice1" style="min-width: 160px;">Select Price
                                                file:</label>
                                            <input type="file" accept=".csv" name="fileToUploadPrice1"
                                                id="csvToUploadPrice1" disabled>
                                            <div style="width:100%;margin-top:10px;">
                                                <button disabled style="display: inline-block;" id="uplad-button-price"
                                                    type="submit" class="btn btn-primary">Submit</button>
                                                <button id="status-button-price" type="button"
                                                    class="btn btn-primary">Check Import Status</button>
                                            </div>
                                        @else
                                            <label for="csvToUploadPrice" style="min-width: 160px;">Select Customer
                                                file:</label>
                                            <input required type="file" accept=".csv" name="fileToUploadPrice"
                                                id="csvToUploadPrice"><br />
                                            <label for="csvToUploadPrice1" style="min-width: 160px;">Select Price
                                                file:</label>
                                            <input required type="file" accept=".csv" name="fileToUploadPrice1"
                                                id="csvToUploadPrice1">
                                            <div style="width:100%;margin-top:10px;">
                                                <button style="display: inline-block;" id="uplad-button-price"
                                                    type="submit" class="btn btn-primary">Submit</button>
                                                <button style="display: none;" id="status-button-price" type="button"
                                                    class="btn btn-primary">Check Import Status</button>
                                            </div>
                                        @endif
                                    </form>
                                    <div style="display: none;" id="inProgressAlertFirstPrice"
                                        class="alert alert-primary" role="alert">
                                        <strong>Upload process started… This process takes approximately 30 - 60 minutes to
                                            complete.</strong>
                                    </div>
                                    <div style="{{ @$price_import_status->status == 'In Progress' ? '' : 'display: none;' }}"
                                        id="inProgressAlertPrice" class="alert alert-primary" role="alert">
                                        <strong>Upload process started…</strong>
                                        <br>File: <span
                                            class="priceUFileName">{{ basename(@$price_import_status->file_path) }},
                                            {{ basename(@$price_import_status->file_path1) }}</span>
                                        <br>Start time: <span
                                            class="processStartTimePrice">{{ @$price_import_status->start_time }}</span>
                                        UTC
                                    </div>
                                    <div class="alert alert-success" role="alert"
                                        style="{{ @$price_import_status->status == 'Success' ? '' : 'display: none;' }}"
                                        id="successAlertPrice">
                                        <strong>Upload is completed. </strong>
                                        <br>File: <span
                                            class="priceUFileName">{{ basename(@$price_import_status->file_path) }},
                                            {{ basename(@$price_import_status->file_path1) }}</span>
                                        <br>Start time: <span
                                            class="processStartTimePrice">{{ @$price_import_status->start_time }}</span>
                                        UTC
                                        <br>End time: <span class="processEndTimePrice">
                                            {{ @$price_import_status->end_time }}</span> UTC
                                        <br>Total run time: <span class="processTotTimePricePrice">
                                            {{ @$importPriceProcessDur }}</span>
                                        <br><span class="successPriceRecord">{{ @$successPriceRecord }}</span> of <span
                                            class="totalPriceRecord">{{ @$totalPriceRecord }}</span> records successfully
                                        processed.
                                        <br><span>No errors found.</span>
                                    </div>
                                    <div class="alert alert-danger" role="alert"
                                        style="{{ @$price_import_status->status == 'Error' ? '' : 'display: none;' }}"
                                        id="errorAlertPrice">
                                        <strong>Upload is completed. </strong>
                                        <br>File: <span
                                            class="priceUFileName">{{ basename(@$price_import_status->file_path) }},
                                            {{ basename(@$price_import_status->file_path1) }}</span>
                                        <br>Start time: <span
                                            class="processStartTimePrice">{{ @$price_import_status->start_time }}</span>
                                        UTC
                                        <br>End time: <span class="processEndTimePrice">
                                            {{ @$price_import_status->end_time }}</span> UTC
                                        <br>Total run time: <span class="processTotTimePrice">
                                            {{ @$importPriceProcessDur }}</span>
                                        <br><span class="successPriceRecord">{{ @$successPriceRecord }}</span> of <span
                                            class="totalPriceRecord">{{ @$totalPriceRecord }}</span> records successfully
                                        processed. <br>Errors found in <a id="errFilePathhPrice"
                                            href="{{ URL::to($errorPriceFilePath) }}" target="_blank">these records.</a>
                                        Please correct the issue and re-import these records.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('custom-scripts')
    <script type="text/javascript">
        $(document).ready(function(e) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#productStockUploadForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('product/upload-stock') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#successAlert").hide();
                        $("#uplad-button").prop("disabled", true);
                        $("#csvToUpload").prop("disabled", true);
                        $("#inProgressAlert").hide();
                        $("#successAlert").hide();
                        $("#errorAlert").hide();
                        setTimeout(function() {
                            $("#status-button").show();
                            $("#inProgressAlertFirst").show();
                        }, 5000);
                    },
                    success: (data) => {

                        //this.reset();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
            $("#status-button").click(function(e) {
                e.preventDefault();
                $("#inProgressAlertFirst").hide();
                getStatusForImport();
            });

            function getStatusForImport() {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('product/check-import-status') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        if (data['status_msg'] == "In Progress") {
                            $("#inProgressAlert").show();
                            $("#successAlert").hide();
                            $("#errorAlert").hide();
                            $(".processStartTime").text(data['start_time']);
                            $(".stockUFileName").text(data['uploadedStockFile']);
                        } else if (data['status_msg'] == "Error") {
                            $("#errFilePathh").attr("href", data['error_file_path']);
                            $(".totalStockRecord").text(data['totalStockRecord']);
                            $(".successStockRecord").text(data['successStockRecord']);
                            $("#inProgressAlert").hide();
                            $("#successAlert").hide();
                            $("#errorAlert").show();
                            $("#status-button").hide();
                            $("#csvToUpload").attr("disabled", false);
                            $("#uplad-button").attr("disabled", false);
                            $(".stockUFileName").text(data['uploadedStockFile']);
                            $(".processStartTime").text(data['start_time']);
                            $(".processTotTime").text(data['importProcessDur']);
                            $(".processEndTime").text(data['end_time']);
                        } else if (data['status_msg'] == "Success") {
                            $("#inProgressAlert").hide();
                            $("#successAlert").show();
                            $("#errorAlert").hide();
                            $("#status-button").hide();
                            $(".totalStockRecord").text(data['totalStockRecord']);
                            $(".successStockRecord").text(data['successStockRecord']);
                            $("#csvToUpload").attr("disabled", false);
                            $("#uplad-button").attr("disabled", false);
                            $(".stockUFileName").text(data['uploadedStockFile']);
                            $(".processStartTime").text(data['start_time']);
                            $(".processTotTime").text(data['importProcessDur']);
                            $(".processEndTime").text(data['end_time']);
                        }


                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }

            // Product Price import.
            $('#productPriceUploadForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                console.log(formData);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('product/upload-price') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#successAlertPrice").hide();
                        $("#uplad-button-price").prop("disabled", true);
                        $("#csvToUploadPrice").prop("disabled", true);
                        $("#csvToUploadPrice1").prop("disabled", true);
                        $("#inProgressAlertPrice").hide();
                        $("#successAlertPrice").hide();
                        $("#errorAlertPrice").hide();
                        setTimeout(function() {
                            $("#status-button-price").show();
                            $("#inProgressAlertFirstPrice").show();
                        }, 5000);
                    },
                    success: (data) => {
                        if (!data.success) {
                            $("#inProgressAlertFirstPrice").text(data.message);
                            $("#status-button-price").hide();
                            $("#inProgressAlertFirstPrice").show();
                            $("#uplad-button-price").prop("disabled", false);
                            $("#csvToUploadPrice").prop("disabled", false);
                            $("#csvToUploadPrice1").prop("disabled", false);
                        } else {
                            $("#inProgressAlertFirstPrice").text(
                                'Upload process started… Usually it takes around 15-20 mins.'
                                );
                            $("#status-button-price").show();
                            $("#inProgressAlertFirstPrice").show();
                        }
                        console.log(data.success);
                        //this.reset();
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
            $("#status-button-price").click(function(e) {
                e.preventDefault();
                $("#inProgressAlertFirstPrice").hide();
                getPriceStatusForImport();
            });

            function getPriceStatusForImport() {
                $.ajax({
                    type: 'GET',
                    url: "{{ url('product/check-price-import-status') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        if (data['status_msg'] == "In Progress") {
                            $("#inProgressAlertPrice").show();
                            $("#successAlertPrice").hide();
                            $("#errorAlertPrice").hide();
                            $(".processStartTimePrice").text(data['start_time']);
                            $(".priceUFileName").text(data['uploadedPriceFile']);
                        } else if (data['status_msg'] == "Error") {
                            $("#errFilePathhPrice").attr("href", data['error_file_path']);
                            $(".totalPriceRecord").text(data['totalPriceRecord']);
                            $(".successPriceRecord").text(data['successPriceRecord']);
                            $("#inProgressAlertPrice").hide();
                            $("#successAlertPrice").hide();
                            $("#errorAlertPrice").show();
                            $("#status-button-price").hide();
                            $("#csvToUploadPrice").attr("disabled", false);
                            $("#csvToUploadPrice1").attr("disabled", false);
                            $("#uplad-button-price").attr("disabled", false);
                            $(".priceUFileName").text(data['uploadedPriceFile']);
                            $(".processStartTimePrice").text(data['start_time']);
                            $(".processTotTimePrice").text(data['importProcessDur']);
                            $(".processEndTimePrice").text(data['end_time']);
                        } else if (data['status_msg'] == "Success") {
                            $("#inProgressAlertPrice").hide();
                            $("#successAlertPrice").show();
                            $("#errorAlertPrice").hide();
                            $("#status-button-price").hide();
                            $(".totalPriceRecord").text(data['totalPriceRecord']);
                            $(".successPriceRecord").text(data['successPriceRecord']);
                            $("#csvToUploadPrice").attr("disabled", false);
                            $("#csvToUploadPrice1").attr("disabled", false);
                            $("#uplad-button-price").attr("disabled", false);
                            $(".priceUFileName").text(data['uploadedPriceFile']);
                            $(".processStartTimePrice").text(data['start_time']);
                            $(".processTotTimePrice").text(data['importProcessDur']);
                            $(".processEndTimePrice").text(data['end_time']);
                        }


                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            }
        });
    </script>
@endpush
