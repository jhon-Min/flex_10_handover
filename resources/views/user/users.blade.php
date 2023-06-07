@extends('layouts.app')

@section('content')
    <div class="content">
        <header class="page-header">
            <div class="d-flex align-items-center">
                <div class="mr-auto">
                    <h1 class="separator">Users</h1>
                    <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i
                                        class="icon dripicons-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Users</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </header>
        <section class="page-content container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="users-table" class="table table-striped table-bordered table-data"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Company Name</th>
                                        <th>Mobile</th>
                                        <th>State</th>
                                        <th>Zip</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->company_name }}</td>
                                            <td>{{ $user->mobile }}</td>
                                            <td>{{ $user->state }}</td>
                                            <td>{{ $user->zip }}</td>
                                            <td id="badge_{{ $user->id }}">{!! $user->status() !!}</td>
                                            <td class="td_action_{{ $user->id }}">
                                                @if ($user->admin_approval_status == 1)
                                                    @php
                                                        $url_approve = route('user.update', ['id' => $user->id, 'status' => 2]);
                                                        $url_decline = route('user.update', ['id' => $user->id, 'status' => 3]);
                                                    @endphp
                                                    <a title="Approve" href="javascript:void(0)"
                                                        onclick="account_code_alert('Account','Approved', '{{ $url_approve }}')"
                                                        class="badge badge-success color-white" id="sweetalert_demo_9"><i
                                                            class="zmdi zmdi-check zmdi-hc-fw"></i></a>
                                                    <a title="Decline" href="#"
                                                        onclick="confirmation_alert('Account','Decline','{{ $url_decline }}')"
                                                        class="badge badge-danger color-white"><i
                                                            class="zmdi zmdi-close zmdi-hc-fw"></i></a>
                                                @endif
                                                @if ($user->admin_approval_status == 2)
                                                    @php
                                                        $url_soft_delete = route('user.soft-delete');
                                                        $checkedStatus = @$user->is_active == 1 ? 'checked' : '';
                                                    @endphp
                                                    <label class="switch">
                                                        <input {{ $checkedStatus }} class="userStatusToggel"
                                                            type="checkbox" name="is_active"
                                                            onchange="toggleCheckbox(this,,'{{ $url_soft_delete }}','{{ $user->id }}')">
                                                        <div class="slider round">
                                                            <span class="on">Active</span>
                                                            <span class="off">In Active</span>
                                                        </div>
                                                    </label>
                                                @endif
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
    </div>
@endsection

@push('custom-scripts')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 105px;
            height: 27px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ff5c75;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 8px;
            width: 10px;
            left: 10px;
            bottom: 9px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2fbfa0;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2fbfa0;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(73px);
            -ms-transform: translateX(73px);
            transform: translateX(73px);
        }

        .slider.round {
            border-radius: 71px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .on {
            display: none;
        }

        .on,
        .off {
            color: white;
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            font-size: 14px;
            width: 100%;
            font-weight: 400;
            text-align: center;
        }

        input:checked+.slider .on {
            display: block;
        }

        input:checked+.slider .off {
            display: none;
        }
    </style>
    <script>
        $(function() {
            $("#users-table").DataTable({
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
                    url: "{{ url('user/datatable') }}",
                    type: "POST",
                    "data": function(d) {
                        d._token = "{{ csrf_token() }}";
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
                        data: "company_name",
                        name: "company_name"
                    },
                    {
                        data: "mobile",
                        name: "mobile"
                    },
                    {
                        data: "state",
                        name: "state"
                    },
                    {
                        data: "zip",
                        name: "zip"
                    },
                    {
                        data: "status",
                        name: "status"
                    },
                    {
                        data: "action",
                        name: "action"
                    }
                ],

            });
        });

        function toggleCheckbox(e, url, userId) {
            $(".userStatusToggel").attr("disabled", true);
            var isActive = 0;
            var isActiveStr = "Inactivate";
            var failedStatus = true;
            if (e.checked == 1) {
                isActive = 1;
                isActiveStr = "Activate";
                failedStatus = false;
            }
            swal({
                title: 'Are you sure?',
                text: "You want to " + isActiveStr + " this user Account!",
                type: 'warning',
                showCancelButton: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ' + isActiveStr + ' it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true,
                allowOutsideClick: false,
            }).then((result) => {

                if (result.value) {
                    var account_code = result.value;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        type: 'post',
                        url: url,
                        data: {
                            userId: userId,
                            is_active: isActive
                        },
                        "success": function(response) {
                            $(".userStatusToggel").attr("disabled", false);
                            if (response.success == '1') {
                                swal(
                                    isActiveStr + '!',
                                    response.message,
                                    'success'
                                )
                                if (response.reload == '1') {
                                    location.reload();
                                }
                                if (response.remove_action != '') {
                                    $('.' + response.remove_action).html('');
                                }
                                if (response.badge != '' && response.badge_data != '') {
                                    $('#badge_' + response.badge_data).html(response.badge);
                                }
                                if (response.delete != '') {
                                    $('#' + response.delete).remove();
                                }
                                $('.table-data').DataTable().ajax.reload();

                            } else {
                                swal(
                                    'Failed',
                                    response.message,
                                    'error'
                                )
                            }
                        }
                    });
                } else {
                    e.checked = failedStatus;
                    $(".userStatusToggel").attr("disabled", false);
                    if (result.dismiss === swal.DismissReason.cancel) {
                        swal(
                            'Cancelled',
                            'Action not performed :)',
                            'error'
                        )
                    } else {
                        swal(
                            'Warning',
                            'Please enter account code.',
                            'error'
                        ).then((result) => {
                            account_code_alert(type, action, url);
                        });
                    }
                }
            });
        }
    </script>
@endpush
