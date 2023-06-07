@extends('layouts.app')
@section('page-title','FlexibleDrive | Maket Intel')
@section('content')
<div class="content">
    <header class="page-header">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h1 class="separator"> Maket Intel</h1>
                <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}"><i class="icon dripicons-home"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{(Request::segment(3) == 'edit') ? 'Edit' : 'Add'}} Maket Intel</li>
                    </ol>
                </nav>
            </div>
        </div>
    </header>
    <section class="page-content container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">{{(Request::segment(3) == 'edit') ? 'Edit' : 'Add'}} Details</button></h5>
                    <div class="card-body">
                        @if(Request::segment(3) == 'edit')
                        <form action="{{route('market-intel.update')}}" method="post" enctype="multipart/form-data" class="{{($errors->any()) ? 'was-validated' : ''}}">
                            <input type="hidden" name="market_intel" value="{{$market_intel->id}}">
                            @else
                            <form action="{{route('market-intel.save')}}" method="post" enctype="multipart/form-data" class="{{($errors->any()) ? 'was-validated' : ''}}">
                                @endif
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label for="defaultSelect">Title*</label>
                                    <input type="text" class="form-control {{($errors->has('title')) ? 'invalid' : ''}}" required="" class="form-control" name="title" value="{{ old('title', (isset($market_intel->title) && !empty(($market_intel->title))) ? $market_intel->title : '')}}" />
                                    @if ($errors->has('title'))
                                    <div class="invalid-feedback" style="display: block;">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-row">
                                    <div class="col-{{(Request::segment(3) == 'edit') ? '5' : '7'}}">
                                        <div class="form-group">
                                            <label for="defaultSelect">URL</label>
                                            <input type="url" class="form-control mb-2 {{($errors->has('url')) ? 'invalid' : ''}}" class="form-control" name="url" placeholder="http://example.com/page" value="{{ old('url', (isset($market_intel->url) && !empty(($market_intel->url))) ? $market_intel->url : '')}}" />
                                            @if ($errors->has('url'))
                                            <div class="invalid-feedback" style="display: block;">
                                                <strong>{{ $errors->first('url') }}</strong>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="form-group">
                                            <label for="defaultSelect">Image (265x200)* <small>upto 2MB</small></label>
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
                                    @if((Request::segment(3) == 'edit'))
                                    <div class="col-2">
                                        <img src="{{$market_intel->image_url}}" class="img-responsive" />
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="defaultSelect">Short Description* <small>(max 100 character)</small></label>
                                    <input type="text" class="form-control {{($errors->has('short_description')) ? 'invalid' : ''}}" required="" class="form-control" name="short_description" maxlength="100" value="{{ old('short_description', (isset($market_intel->short_description) && !empty(($market_intel->short_description))) ? $market_intel->short_description : '')}}" />
                                    @if ($errors->has('short_description'))
                                    <div class="invalid-feedback" style="display: block;">
                                        <strong>{{ $errors->first('short_description') }}</strong>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="defaultSelect">Description*</label><br />
                                    <textarea name="description" required="" id="editor1" class="cke_wrapper {{($errors->has('description')) ? 'invalid' : ''}}" rows="10" cols="80">
                                    {{ old('description', (isset($market_intel->description) && !empty(($market_intel->description))) ? $market_intel->description : '')}}
                                    </textarea>
                                    @if ($errors->has('description'))
                                    <div class="invalid-feedback" style="display: inline;" role="alert">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </div>
                                    @endif
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
<script>
    CKEDITOR.replace('editor1', {
        on: {
            pluginsLoaded: function(event) {
                event.editor.dataProcessor.dataFilter.addRules({
                    elements: {
                        script: function(element) {
                            return false;
                        }
                    }
                });
            }
        }
    });
</script>
@endpush