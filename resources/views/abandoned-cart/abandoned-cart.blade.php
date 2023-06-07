@extends('layouts.app')
@section('page-title','FlexibleDrive | Abandoned Orders')
@section('content')
<div class="content">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator">Abandoned Orders</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Abandoned Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <section class="page-content container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <!-- <h5 class="card-header"></h5> -->
                    <div class="card-body">
                        <table id="orders-table" class="table table-striped table-bordered table-data" style="width:100%">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr id="order_{{$order->id}}">
                                    <td>{{$order->user->name}}</td>
                                    <td>{{$order->user->email}}</td>
                                    <td>{{date('d/m/Y',strtotime($order->created_at))}}</td>
                                    <td>
                                        @php
                                        $url_delete = route('order.delete',['id' => $order->id]);
                                        @endphp
                                        <a href="javascript:void(0);" title="View Invoice" onclick="cartItemsModal('{{$order->user_id}}')" class="badge badge-warning color-white" ><i class="la la-eye"></i></a>
                                        <a href="javascript:void(0);" title="Delete" onclick="confirmation_alert('Order','Delete','{{$url_delete}}')" class="badge badge-danger color-white"><i class="la la-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="cartItemsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel9">Cart</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="zmdi zmdi-close"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <h3 id="order_number">Order Number : <span></span></h3> -->
                    <div class="form-group">
                        <div id="cart_details"></div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $(function() {
        $("#orders-table").DataTable({
            processing: true,
            serverSide: true,
            bSortCellsTop: true,
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000],
                [10, 25, 50, 100, 500, 1000]
            ],
            columnDefs: [{
                orderable: false,
                targets: [0]
            }],
            ajax: {
                url: "{{ url('abandoned-cart/datatable') }}",
                type: "POST",
                "data": function(d) {
                    d._token = "{{csrf_token()}}";
                }
            },
            columns: [{
                    data: "name",
                    name: "name"
                },
                {
                    data: "email",
                    name: "email"
                },
                {
                    data: "order_date",
                    name: "order_date"
                },
                {
                    data: "action",
                    name: "action"
                }
            ],

        });
    });

    function cartItemsModal(user_id) {
        $.ajax({
            'method': 'GET',
            'url': "{{route('cart.get')}}",
            headers: {
                'X-CSRF-Token': '{{csrf_token()}}'
            },
            'data': {
                'user_id': user_id,
                // 'order_status': $('#order_status').val()
            },
            success: function(result) {
                var order_total = 0;
                var order_response = JSON.parse(result);
                var order_product = order_response.products;
                var order_user = order_response.user;
                var str_table = '<div class="cart-header-user"><span>User : ' + order_user.name + '</span><br/><span>Email : ' + order_user.email + '</span><br /><span>Date : ' + order_user.date + '</span></div>';
                str_table += '<table class="table table-striped table-bordered table-data no-footer">';
                    str_table += '<tr><th></th><th>Part Number</th><th style="text-align:right;">Price</th><th style="text-align:right;">Quantity</th><th style="text-align:right;">Sub Total</th></tr>';
                for(var i = 0; i < order_product.length; i++) {
                    str_table += '<tr>';
                    var obj = order_product[i];

                    str_table += '<td>';
                    str_table += '<img style="width:80px; max-height:80px;" src="' + obj.image_url + '"/>';
                    str_table += '</td>';

                    str_table += '<td>';
                    str_table += obj.part_numer;
                    str_table += '</td>';

                    str_table += '<td style="text-align:right;">$';
                    str_table += obj.price;
                    str_table += '</td>';

                    str_table += '<td style="text-align:right;">';
                    str_table += obj.qty;
                    str_table += '</td>';

                    str_table += '<td style="text-align:right;">$';
                    str_table += obj.total;
                    str_table += '</td>';

                    order_total += parseFloat(obj.total);

                    str_table += '</tr>';
                }
                str_table += '<tr><td colspan="3"></td><td colspan="3" style="text-align:right;"><strong>Order Total: $' + order_total.toFixed(2) + '</strong></td></tr>';
                str_table += '</table>';

                // console.log(order_product);
                $('#cart_details').html(str_table);
                $('#cartItemsModal').modal('show');

            },
            error: function(error) {
                toastr.error(error.responseJSON.message);
            }
        });

        // $('#order_number span').html(data);
        // $('#order_status').val(current_status);
        // $('#order_status').attr('data-roder', order);
    }

    function update_status() {
        $.ajax({
            'method': 'GET',
            'url': "{{route('cart.get')}}",
            headers: {
                'X-CSRF-Token': '{{csrf_token()}}'
            },
            'data': {
                'order_id': $('#order_status').attr('data-roder'),
                'order_status': $('#order_status').val()
            },
            success: function(success) {
                toastr.success(success.message);
                $('.table-data').DataTable().ajax.reload();

            },
            error: function(error) {
                toastr.error(error.responseJSON.message);
            }
        });
        $('#cartItemsModal').modal('toggle');
    }
</script>
@endpush