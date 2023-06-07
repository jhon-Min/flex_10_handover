@extends('layouts.app')
@section('page-title','FlexibleDrive | Profile')
@section('content')
<div class="content">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator"> My Profile</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <section class="page-content container-fluid">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 col-lg-3">
                                <div class="nav flex-column nav-pills" id="my-account-tabs" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link {{(!Session::get('tab') || Session::get('tab') != 'password') ? 'active' : ''}}" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="true">Profile</a>
                                    <a class="nav-link {{(Session::get('tab') && Session::get('tab') == 'password') ? 'active' : ''}}" id="v-pills-password-tab" data-toggle="pill" href="#v-pills-password" role="tab" aria-controls="v-pills-password" aria-selected="false">Change Password</a>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-9">
                                <div class="tab-content" id="my-account-tabsContent">
                                    <div class="tab-pane fade show {{(!Session::get('tab') || Session::get('tab') != 'password') ? 'active' : ''}}" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <h4 class="card-heading p-b-20">Profile</h4>
                                        <form action="{{route('my-profile.update')}}" method="post" enctype="multipart/form-data" class="{{($errors->any()) ? 'was-validated' : ''}}">
                                            {{csrf_field()}}
                                            <div class="form-group">
                                                <img src="{{($profile->profile_image) ? $profile->image_url : asset('assets/img/avatars/1.jpg')}}" class="w-50 rounded-circle" alt="{{$profile->name}}">
                                                <div class="file-upload">
                                                    <label for="upload" class="btn btn-primary m-b-0 m-l-5 m-r-5">Upload a new picture</label>
                                                    <input id="upload" class="file-upload__input" type="file" name="image">
                                                </div>
                                                <small>(200 x 200)* upto 2MB</small>
                                                @if ($errors->has('image'))
                                                <div class="invalid-feedback" style="display: block;">
                                                    <strong>{{ $errors->first('image') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="form-row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="inputName">First name</label>
                                                        <input type="text" class="form-control" required name="first_name" placeholder="Enter First name" value="{{$profile->first_name}}">
                                                        @if ($errors->has('first_name'))
                                                        <div class="invalid-feedback" style="display: block;">
                                                            <strong>{{ $errors->first('first_name') }}</strong>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="inputName">Last name</label>
                                                        <input type="text" class="form-control" required placeholder="Enter Last name" name="last_name" value="{{$profile->last_name}}">
                                                    </div>
                                                    @if ($errors->has('last_name'))
                                                    <div class="invalid-feedback" style="display: block;">
                                                        <strong>{{ $errors->first('last_name') }}</strong>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email address</label>
                                                <input type="email" readonly class="form-control" autocomplete="email" id="exampleInputEmail1" placeholder="Email" value="{{$profile->email}}">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Update Profile</button>
                                        </form>
                                    </div>
                                    <!--------Password------->
                                    <div class="tab-pane fade show {{(Session::get('tab') && Session::get('tab') == 'password') ? 'active' : ''}}" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab">
                                        <h4 class="card-heading p-b-20">Change Password</h4>
                                        <form action="{{route('my-profile.change.password')}}" method="post" enctype="multipart/form-data" class="{{($errors->any()) ? 'was-validated' : ''}}">
                                            {{csrf_field()}}

                                            <div class="form-group">
                                                <label for="inputCard"> Current password</label>
                                                <input type="password" name="old_password" class="form-control" required placeholder="••••••••••••">
                                                @if ($errors->has('old_password'))
                                                <div class="invalid-feedback" style="display: block;">
                                                    <strong>{{ $errors->first('old_password') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="inputCard"> New password</label>
                                                <input type="password" required name="new_password" class="form-control" placeholder="••••••••••••">
                                                @if ($errors->has('new_password'))
                                                <div class="invalid-feedback" style="display: block;">
                                                    <strong>{{ $errors->first('new_password') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="inputCard"> Confirm password</label>
                                                <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••••••">
                                                @if ($errors->has('confirm_password'))
                                                <div class="invalid-feedback" style="display: block;">
                                                    <strong>{{ $errors->first('confirm_password') }}</strong>
                                                </div>
                                                @endif
                                            </div>

                                            <button type="submit" class="btn btn-primary">Update Profile</button>
                                        </form>
                                    </div>
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
<script>
    $(function() {
        $('a[data-toggle="tab"]').on('click', function(e) {
            window.localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = window.localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
            window.localStorage.removeItem("activeTab");
        }
    });
</script>
@endpush