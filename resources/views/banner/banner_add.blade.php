@extends('layouts.app')
@section('page-title','FlexibleDrive | Banner Management')
@section('content')
<div class="content">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator">Banner Management</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Banner</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <section class="page-content container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Add Image</button></h5>
                    <div class="card-body">
                        <form action="{{route('banner.save')}}" method="post" enctype="multipart/form-data" class="{{($errors->any()) ? 'was-validated' : ''}}">
                            {{csrf_field()}}
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="defaultSelect">Image (1108 x 248)* <small>upto 5MB</small></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input {{($errors->has('image')) ? 'invalid' : ''}}" id="validatedCustomFile" {{(Request::segment(3) == 'edit') ? '' : 'required'}} accept="image/*" name="image">
                                        <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                                    </div>
                                    @if ($errors->has('image'))
                                    <div class="invalid-feedback" style="display: block;">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('custom-scripts')

@endpush