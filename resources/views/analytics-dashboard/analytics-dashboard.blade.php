@extends('layouts.app')
@section('page-title','FlexibleDrive | Analytics')
@section('content')

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<script type = "text/javascript" src = "https://www.gstatic.com/charts/loader.js">
</script>
<script type = "text/javascript">
   google.charts.load('current', {packages: ['corechart','line']});  
</script>

<script language = "JavaScript">
   function drawChart() {
    // Define the chart to be drawn.
    var data = new google.visualization.DataTable();
    var visitor = <?php echo $array_orders; ?>;

    // var data = google.visualization.arrayToDataTable(visitor);
    data.addColumn('string', 'Month');
    data.addColumn('number', 'Current time period');
    data.addColumn('number', 'Previous time period');
    data.addRows(
        visitor
        );

    var current_order_total = '<?php echo $current_order_total; ?>'; 
    var previous_order_total = '<?php echo $previous_order_total; ?>';
    

    // Set chart options
    var options = {
        // title : 'Total Orders AUD$' +  current_order_total + ', \n Previous time period total orders: AUD$' + previous_order_total,
        // subtitle : 'Average Temperatures of Cities',
        hAxis: {
          title: 'Months',
          textStyle :{
            fontSize : 10
        }
    },
    vAxis: {
      title: 'Amount',
      viewWindow: {
        min:0
    }
},   
        // 'width':1600,
        'height':600,
        curveType: 'function',
        // pointSize: 20,
        // legend: { position: 'bottom' },
        legend: { position: 'bottom', alignment: 'end' },
        pointsVisible: true,
        colors: ['#de8164', '#74a0c7'],
        chartArea:{
            left:30,
            top: 20,
            width: '95%',
            // height: '600',
        }
    };

    // Instantiate and draw the chart.
    var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
<div class="content page-analytics">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator">Analytics</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <section class="page-content container-fluid">
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="GET" id="analytics-form">
            <!-- Dashboard Chart Starts -->
            <div class="row" id="chart_container">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-right">
                                <div class="row">
                                    <!-- <div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="input-group">
                                            <input type="text" name="chart_date" id="chart_date" class="form-control datepicker-picker" autocomplete="off" value="{{ $chart_date }}" />
                                            <div class="input-group-addon">
                                                <i class="icon dripicons-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="input-group">
                                            <input type="text" name="chart_date_end" id="chart_date_end" class="form-control datepicker-picker"  autocomplete="off" value="{{ $chart_date_end }}">
                                            <div class="input-group-addon">
                                                <i class="icon dripicons-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="input-group">
                                            <input type="submit" name="Submit" value="Apply" class="btn btn-primary">
                                        </div>
                                    </div> -->
                                    <div class="col-sm-8 col-md-8 col-lg-8 text-left">
                                        <div class="chart-main-text">Total Orders: AUD${{ $current_order_total }}</div>
                                        <div class="chart-sub-text">Previous time period total orders: AUD${{$previous_order_total}}</div>
                                    </div>
                                    <div class="col-sm-4 col-md-4 col-lg-4 text-right">
                                        <select name="chart_state" onchange="javascript: submitAnalyticsForm(this.form, 'chart_container');">
                                            <option value="">All states</option>
                                            <option value="NSW" @if($chart_state == "NSW") ? selected : null @endif>NSW</option>
                                            <option value="QLD" @if($chart_state == "QLD") ? selected : null @endif>QLD</option>
                                            <option value="VIC" @if($chart_state == "VIC") ? selected : null @endif>VIC</option>
                                            <option value="SA" @if($chart_state == "SA") ? selected : null @endif>SA</option>
                                            <option value="TAS" @if($chart_state == "TAS") ? selected : null @endif>TAS</option>
                                            <option value="WA" @if($chart_state == "WA") ? selected : null @endif>WA</option>
                                            <option value="ACT" @if($chart_state == "ACT") ? selected : null @endif>ACT</option>
                                        </select>
                                    </div>
                                </div>

                                
                            </div>
                        </div>
                        <div class="card-body scrolling-wrapper">
                            <div id="chart_div"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Dashboard Chart Ends -->

            <!-- <div class="card-header">
                <div class="text-right"> -->
                    <div class="row" id="date-container">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="card">

                                <h5 class="card-header">


                                    <div class="row">
                                        <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                                            <div class="input-group form-group">
                                                <label for="chart_date">From</label>
                                                <input type="text" name="chart_date" id="chart_date" class="form-control datepicker-picker" autocomplete="off" value="{{ $chart_date }}" />
                                                <div class="input-group-addon">
                                                    <i class="icon dripicons-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                                            <div class="input-group form-group">
                                                <label for="chart_date_end">To</label>
                                                <input type="text" name="chart_date_end" id="chart_date_end" class="form-control datepicker-picker"  autocomplete="off" value="{{ $chart_date_end }}">
                                                <div class="input-group-addon">
                                                    <i class="icon dripicons-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                                            <div class="input-group form-group">
                                                <input type="button" name="Submit" value="Apply" class="btn btn-primary" onClick="javascript: submitAnalyticsForm(this.form, 'date-container');">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="card">

                                                <div class="card-header custom-bold">
                                                    <div class="row">
                                                        <div class="col-12"><label>Total orders amount</label>: ${{$order_total_amount}}</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12"><label>Total orders</label>: {{$order_total_count}}</div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-12"><label>Total searches</label>: {{$total_search}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </h5>
                            </div>
                        </div>
                <!-- <div class="col-sm-6 col-md-6 col-lg-6">
                    <select name="chart_state" onchange="this.form.submit()">
                        <option value="">All states</option>
                        <option value="NSW" @if($chart_state == "NSW") ? selected : null @endif>NSW</option>
                        <option value="QLD" @if($chart_state == "QLD") ? selected : null @endif>QLD</option>
                        <option value="VIC" @if($chart_state == "VIC") ? selected : null @endif>VIC</option>
                        <option value="SA" @if($chart_state == "SA") ? selected : null @endif>SA</option>
                        <option value="TAS" @if($chart_state == "TAS") ? selected : null @endif>TAS</option>
                        <option value="WA" @if($chart_state == "WA") ? selected : null @endif>WA</option>
                        <option value="ACT" @if($chart_state == "ACT") ? selected : null @endif>ACT</option>
                    </select>
                </div> -->
            </div>
                <!-- </div>
                </div> -->

                <!-- Part Search / Vehicle Search  Start -->
                <div class="row" id="part_search">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <div class="card">

                            <h5 class="card-header">


                                <div class="row">
                                    <div class="col-8">Part Searches</div>
                                    <div class="col-4">
                                        <select name="part_state" onchange="javascript: submitAnalyticsForm(this.form, 'part_search');">
                                            <option value="">All states</option>
                                            <option value="NSW" @if($part_state == "NSW") ? selected : null @endif>NSW</option>
                                            <option value="QLD" @if($part_state == "QLD") ? selected : null @endif>QLD</option>
                                            <option value="VIC" @if($part_state == "VIC") ? selected : null @endif>VIC</option>
                                            <option value="SA" @if($part_state == "SA") ? selected : null @endif>SA</option>
                                            <option value="TAS" @if($part_state == "TAS") ? selected : null @endif>TAS</option>
                                            <option value="WA" @if($part_state == "WA") ? selected : null @endif>WA</option>
                                            <option value="ACT" @if($part_state == "ACT") ? selected : null @endif>ACT</option>
                                        </select>
                                    </div>
                                </div>
                            </h5>
                            <div class="card-body" style="overflow-y: scroll; max-height: 317px;">
                                @if(!$part_searches->isEmpty())
                                <div class="row">
                                    <div class="col-8">Part Number</div>
                                    <div class="col-4 text-right">Searches</div>
                                </div>
                                <hr>

                                <div class="card-text">
                                    @foreach($part_searches as $part_search)
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="progress">
                                              <div class="progress-bar text-left" role="progressbar" style="padding-left:15px; width: {{ ($part_search->part_number_count / $part_max) * 100 }}%;" aria-valuenow="{{($part_search->part_number_count / $part_max) * 100}}" aria-valuemin="0" aria-valuemax="100">{{ $part_search->part_number }}</div>
                                          </div>
                                      </div>
                                      <div class="col-2 text-right">{{$part_search->part_number_count}}</div>
                                  </div>
                                  @endforeach
                              </div>
                              @else
                              <div class="empty-part-text">No part searches found.</div>
                              @endif
                          </div>
                      </div>
                  </div>

                  <div class="col-sm-12 col-md-12 col-lg-6">
                    <div class="card">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col-8">Vehicle Searches</div>
                                <div class="col-4">
                                    <select name="vehicle_state" onchange="javascript: submitAnalyticsForm(this.form, 'part_search');">
                                        <option value="">All states</option>
                                        <option value="NSW" @if($vehicle_state == "NSW") ? selected : null @endif>NSW</option>
                                        <option value="QLD" @if($vehicle_state == "QLD") ? selected : null @endif>QLD</option>
                                        <option value="VIC" @if($vehicle_state == "VIC") ? selected : null @endif>VIC</option>
                                        <option value="SA" @if($vehicle_state == "SA") ? selected : null @endif>SA</option>
                                        <option value="TAS" @if($vehicle_state == "TAS") ? selected : null @endif>TAS</option>
                                        <option value="WA" @if($vehicle_state == "WA") ? selected : null @endif>WA</option>
                                        <option value="ACT" @if($vehicle_state == "ACT") ? selected : null @endif>ACT</option>
                                    </select>
                                </div>
                            </div>
                        </h5>
                        <div class="card-body" style="overflow-y: scroll; max-height: 317px;">
                            @if(!$vehicle_searches->isEmpty())
                            <div class="row">
                                <div class="col-8">Vehicles</div>
                                <div class="col-4 text-right">Searches</div>
                            </div>
                            <hr>

                            <div class="card-text">
                                @foreach($vehicle_searches as $vehicle_search)
                                <div class="row">
                                    <div class="col-10">
                                        <div class="progress">
                                          <div class="progress-bar text-left" role="progressbar" style="padding-left:15px; width: {{ ($vehicle_search->vehicle_count / $vehicle_max) * 100 }}%;" aria-valuenow="{{($vehicle_search->vehicle_count / $vehicle_max) * 100}}" aria-valuemin="0" aria-valuemax="100">{{ $vehicle_search->year . ' ' . $vehicle_search->make_name . ' ' . $vehicle_search->model_name }}</div>
                                      </div>
                                  </div>
                                  <div class="col-2 text-right">{{$vehicle_search->vehicle_count}}</div>
                              </div>
                              @endforeach
                          </div>
                          @else
                          <div class="empty-vehicle-text">No vehicles searches found.</div>
                          @endif

                      </div>
                  </div>
              </div>
          </div>
          <!-- Part Search / Vehicle Search  Ends -->

          <!-- Company Order/Search Start -->
          <div class="row" id="company_search">
            <div class="col-12">
                <div class="card">

                    <h5 class="card-header">
                        <div class="row">
                            <div class="col-8">Company orders/searches</div>
                            <div class="col-4">
                                <select name="company_state" onchange="javascript: submitAnalyticsForm(this.form, 'company_search');">
                                    <option value="">All states</option>
                                    <option value="NSW" @if($company_state == "NSW") ? selected : null @endif>NSW</option>
                                    <option value="QLD" @if($company_state == "QLD") ? selected : null @endif>QLD</option>
                                    <option value="VIC" @if($company_state == "VIC") ? selected : null @endif>VIC</option>
                                    <option value="SA" @if($company_state == "SA") ? selected : null @endif>SA</option>
                                    <option value="TAS" @if($company_state == "TAS") ? selected : null @endif>TAS</option>
                                    <option value="WA" @if($company_state == "WA") ? selected : null @endif>WA</option>
                                    <option value="ACT" @if($company_state == "ACT") ? selected : null @endif>ACT</option>
                                </select>
                            </div>
                        </h5>
                        <div class="card-body" style="overflow-y: scroll; max-height: 317px;">
                            @if($empty_company_search)
                            <div class="row">
                                <div class="col-3">Company name</div>
                                <div class="col-3">State</div>
                                <div class="col-3"><div data-id="search_sort" class="search-asc-desc {{$search_sort}}">Searches</div></div>
                                <div class="col-3"><div data-id="order_sort" class="search-asc-desc {{$order_sort}}">Orders</div></div>
                            </div>
                            <hr>

                            <div class="card-text">
                                @foreach($company_searches as $company_name => $company_search)
                                @foreach($company_search as $state => $search_data)
                                <div class="row">
                                    <div class="col-3">{{ $company_name }}</div>
                                    <div class="col-3">{{ $state }}</div>
                                    <div class="col-3">{{ $search_data['search'] }}</div>
                                    <div class="col-3">{{ $search_data['order'] }}</div>
                                </div>
                                @endforeach
                                @endforeach
                            </div>
                            @else
                            <div class="empty-company-text">No company searches found.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Company Order/Search Start -->

            <!-- No stock list Start -->
            <div class="row" id="stock_search">
                <div class="col-12">
                    <div class="card">
                        <h5 class="card-header">
                            <div class="row">
                                <div class="col-8">No stock list</div>
                                <div class="col-4">
                                    <select name="stock_state" onchange="javascript: submitAnalyticsForm(this.form, 'stock_search');">
                                        <option value="">All states</option>
                                        <option value="NSW" @if($stock_state == "NSW") ? selected : null @endif>NSW</option>
                                        <option value="QLD" @if($stock_state == "QLD") ? selected : null @endif>QLD</option>
                                        <option value="VIC" @if($stock_state == "VIC") ? selected : null @endif>VIC</option>
                                        <option value="SA" @if($stock_state == "SA") ? selected : null @endif>SA</option>
                                        <option value="TAS" @if($stock_state == "TAS") ? selected : null @endif>TAS</option>
                                        <option value="WA" @if($stock_state == "WA") ? selected : null @endif>WA</option>
                                        <option value="ACT" @if($stock_state == "ACT") ? selected : null @endif>ACT</option>
                                    </select>
                                </div>
                            </div>
                        </h5>
                        <div class="card-body" style="overflow-y: scroll; max-height: 317px;">
                            @if(!$stock_searches->isEmpty())
                            <div class="row">
                                <div class="col-4">Part Number</div>
                                <div class="col-4">Vehicle</div>
                                <div class="col-4"><div data-id="stock_sort" class="search-asc-desc {{$stock_sort}}">Searches</div></div>
                            </div>
                            <hr>

                            <div class="card-text">
                                @foreach($stock_searches as $stock_search)
                                <div class="row">
                                    @if ($stock_search->search_type != 'vehicle') 
                                    <div class="col-4">{{ $stock_search->part_number }}</div>
                                    @else 
                                    <div class="col-4">N/A</div>
                                    @endif

                                    @if ($stock_search->search_type != 'part') 
                                    <div class="col-4">{{ $stock_search->year . ' ' . $stock_search->make_name . ' ' . $stock_search->model_name }}</div>
                                    @else 
                                    <div class="col-4">N/A</div>
                                    @endif

                                    <div class="col-4">{{ $stock_search->no_stock_count }}</div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="empty-stock-text">No stock searches found.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="landing_section" id="landing_section" value="" />
            <input type="hidden" name="search_sort" id="search_sort"value="{{$search_sort}}" />
            <input type="hidden" name="order_sort" id="order_sort" value="{{$order_sort}}" />
            <input type="hidden" name="stock_sort" id="stock_sort" value="{{$stock_sort}}" />
            <!-- No stock list End -->
        </form>

    </section>
</div>
@endsection

@push('custom-scripts')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>

    $(function() {
      var urlParams = new URLSearchParams(window.location.search);
      var landing_section = urlParams.get('landing_section');

      if (landing_section !== null) {
        setTimeout(function(){ 
          $('html, body').animate({
            scrollTop: $("#" + landing_section).offset().top
        }, 200);
      }, 200);

    }
});
    $(function () {
        $( ".datepicker-picker" ).datepicker({
            todayHighlight: true,
            autoclose: true,
            // format: 'dd-mm-yyyy'
            format: 'dd-mm-yyyy'
        });


        $('.search-asc-desc').click(function() {
          var selected_sort = $(this).attr('data-id');
          var selected_sort_value = $("#" + selected_sort).val();

          if (selected_sort_value == null || selected_sort_value == '') {
            $("#" + selected_sort).val('asc');
        }
        else if (selected_sort_value == 'desc') {
            $("#" + selected_sort).val('asc');
        }
        else {
            $("#" + selected_sort).val('desc');
        }

        if (selected_sort == 'search_sort') {
            $("#order_sort").val('');
        }

        if (selected_sort == 'order_sort') {
            $("#search_sort").val('');
        }


        document.getElementById('analytics-form').submit();
    });
    });

</script>

@endpush


<script type="text/javascript">
  function submitAnalyticsForm(formObj, containerID) {
    $('#landing_section').val(containerID);
    formObj.submit();
}

</script>