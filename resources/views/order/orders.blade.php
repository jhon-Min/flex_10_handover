@extends('layouts.app')
@section('page-title','FlexibleDrive | Orders')
@section('content')
<div class="content">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator">Orders</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Orders</li>
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
                                    <th>Order No.</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Delivery</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr id="order_{{$order->id}}">
                                    <td>{{$order->user->name}}</td>
                                    <td>{{$order->user->email}}</td>
                                    <td>{{$order->order_number}}</td>
                                    <td id="badge_{{$order->id}}">{!!$order->status_badge!!}</td>
                                    <td>${{number_format((float) $order->total, 2, '.', '')}}</td>
                                    <td>{!!$order->delivery_type!!}</td>
                                    <td>{{date('d/m/Y',strtotime($order->created_at))}}</td>
                                    <td>
                                        @php
                                        $url_delete = route('order.delete',['id' => $order->id]);
                                        @endphp
                                        <a href="javascript:void(0);" onclick="orderSatusModal('{{$order->id}}','{{$order->order_number}}','{{$order->status}}')" class="badge badge-info color-white"><i class="la la-edit"></i></a>
                                        <a href="javascript:void(0);" title="Delete" onclick="confirmation_alert('Order','Delete','{{$url_delete}}')" class="badge badge-danger color-white"><i class="la la-trash"></i></a>
										<a href="javascript:void(0);" title="View Invoice" class="badge badge-warning color-white" ><i class="la la-eye"></i></a>
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
    <div class="modal fade" id="orderStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel9">Update Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="zmdi zmdi-close"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 id="order_number">Order Number : <span></span></h3>
                    <div class="form-group">
                        <label for="defaultSelect">Order Status</label>
                        <select id="order_status" required class="form-control">
                            <option value="">Select Order Status</option>
                            @foreach($status as $key => $order_status)
                            <option value="{{ $key}}">{{$order_status}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-outline" data-dismiss="modal">Close</button>
                    <button type="button" onclick="update_status()" class="btn btn-primary">Save changes</button>
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
                url: "{{ url('order/datatable') }}",
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
                    data: "order_number",
                    name: "order_number"
                },
                {
                    data: "order_status",
                    name: "order_status"
                },
                {
                    data: "order_total",
                    name: "order_total"
                },
                {
                    data: "delivery_method",
                    name: "delivery_method"
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

    function orderSatusModal(order, data, current_status) {
        $('#orderStatusModal').modal('show');
        $('#order_number span').html(data);
        $('#order_status').val(current_status);
        $('#order_status').attr('data-roder', order);
    }

    function update_status() {
        $.ajax({
            'method': 'POST',
            'url': "{{route('order.update')}}",
            headers: {
                'X-CSRF-Token': '{{csrf_token()}}'
            },
            'data': {
                'order': $('#order_status').attr('data-roder'),
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
        $('#orderStatusModal').modal('toggle');
    }
</script>
@endpush