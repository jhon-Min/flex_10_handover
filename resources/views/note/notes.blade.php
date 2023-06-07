@extends('layouts.app')
@section('page-title','FlexibleDrive | Product Notes')
@section('content')
<div class="content">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator">Product Notes</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product Notes</li>
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
                        <table id="notes-table" class="table table-striped table-bordered table-data" style="width:100%">
                            <thead>
                                <tr>
                                    <th>date_string</th>
                                    <th>Date</th>
                                    <th>Product Number</th>
                                    <th>Note</th>
                                    <th>User</th>
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

</div>
@endsection

@push('custom-scripts')
<script>
    $(function() {
        $("#notes-table").DataTable({
            processing: true,
            serverSide: true,
            bSortCellsTop: true,
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000],
                [10, 25, 50, 100, 500, 1000]
            ],
            order: [
                [1, 'desc']  
            ],
            columnDefs: [{

                    visible: false,
                    targets: [0],
                    searchable: false
                },
                {
                    targets: [1],
                    iDataSort: 0
                }

            ],
            ajax: {
                url: "{{ url('note/datatable') }}",
                type: "POST",
                "data": function(d) {
                    d._token = "{{csrf_token()}}";
                }
            },
            columns: [{
                    data: "date_string",
                    name: "date_string"
                },
                {
                    data: "date",
                    name: "date"
                },
                {
                    data: "product_nr",
                    name: "product_nr"
                },
                {
                    data: "description",
                    name: "description"
                },
                {
                    data: "user",
                    name: "user"
                }
            ],

        });
    });
</script>
@endpush