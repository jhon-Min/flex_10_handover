@extends('layouts.app')
@section('page-title','FlexibleDrive | Banner Management')
@push('custom-css')
<link rel="stylesheet" href="{{asset('assets/css/magnific-popup.css')}}">
@endpush
@section('content')
<div class="content">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator">Banner Management</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Banner Management</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <section class="page-content container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header"><a href="{{route('banner.add')}}" class="btn btn-primary pull-right">Add Banner</a></h5>
                    <div class="card-body">
                        <table id="banner-table" class="table table-striped table-bordered table-data" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="90%">Image</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @endsection

    @push('custom-scripts')
    <script src="{{asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
    <script>
        $(function() {
            function initLigbox() {
                $('.image-popup-link').magnificPopup({
                    type: 'image',
                    closeOnContentClick: false,
                    mainClass: 'mfp-img-mobile',
                    image: {
                        verticalFit: true
                    }
                });
            }
            $("#banner-table").DataTable({
                processing: true,
                serverSide: true,
                searching: false,
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
                    url: "{{ url('banner-management/datatable') }}",
                    type: "POST",
                    "data": function(d) {
                        d._token = "{{csrf_token()}}";
                    }
                },
                columns: [{
                    data: "image",
                    name: "image"
                }, {
                    data: "action",
                    name: "action"
                }],
                "fnInitComplete": function(oSettings, json) {
                    initLigbox();
                }
            });
        });
    </script>
    @endpush