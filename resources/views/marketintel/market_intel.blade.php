@extends('layouts.app')
@section('page-title','FlexibleDrive | Maket Intel')
@push('custom-css')
<link rel="stylesheet" href="{{asset('assets/css/magnific-popup.css')}}">
@endpush
@section('content')
<div class="content">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator">Maket Intel</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Maket Intel</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <section class="page-content container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <h5 class="card-header"><a href="{{route('market-intel.add')}}" class="btn btn-primary pull-right">Add Market Intel</a></h5>
                    <div class="card-body">
                        <table id="market-intel-table" class="table table-striped table-bordered table-data" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Short Description</th>
                                    <th>Url</th>
                                    <th>Created at</th>
                                    <th>Action</th>
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
            $("#market-intel-table").DataTable({
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
                    url: "{{ url('market-intel/datatable') }}",
                    type: "POST",
                    "data": function(d) {
                        d._token = "{{csrf_token()}}";
                    }
                },
                columns: [{
                    data: "image",
                    name: "image"
                }, {
                    data: "title",
                    name: "title"
                }, {
                    data: "short_description",
                    name: "short_description"
                }, {
                    data: "url",
                    name: "url"
                }, {
                    data: "created_at",
                    name: "created_at"
                }, {
                    data: "action",
                    name: "action"
                }],
                "fnInitComplete": function(oSettings, json) {
                    initLigbox();
                }
            });
        });
    </script> @endpush