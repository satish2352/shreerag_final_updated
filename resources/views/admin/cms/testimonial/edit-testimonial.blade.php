@extends('admin.layouts.master')

@section('content')
    <style>
        .error {
            color: red !important;
        }

        .red-text {
            color: red !important;
        }
    </style>
    <div class="">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Update Testimonial</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if (Session::get('status') == 'success')
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-success " id="success-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
                                            <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                @endif

                                @if (Session::get('status') == 'error')
                                    <div class="col-12 grid-margin">
                                        <div class="alert alert-custom-danger " id="error-alert">
                                            <button type="button" data-bs-dismiss="alert"></button>
                                            <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                        </div>
                                    </div>
                                @endif

                                <div class="all-form-element-inner">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form class="forms-sample" action="{{ route('update-testimonial') }}" method="post"
                                        id="regForm" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="title">Title </label>&nbsp<span class="red-text">*</span>
                                                    <input class="form-control" name="title" id="title"
                                                        placeholder="Enter the Title"
                                                        value="@if (old('title')) {{ old('title') }}@else{{ $editData->title }} @endif">

                                                    <label class="error py-2" for="title" id="title_error"></label>
                                                    @if ($errors->has('title'))
                                                        <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="position">Position </label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <input class="form-control" name="position" id="position"
                                                        placeholder="Enter the Position"
                                                        value="@if (old('position')) {{ old('position') }}@else{{ $editData->position }} @endif">

                                                    <label class="error py-2" for="position" id="position_error"></label>
                                                    @if ($errors->has('position'))
                                                        <span class="red-text"><?php echo $errors->first('position', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group" id="summernote_id">
                                                    <label for="english_description">Description</label>&nbsp<span
                                                        class="red-text">*</span>
                                                    <span class="summernote1">
                                                        <textarea class="form-control" name="description" id="description" placeholder="Enter the Description">
                                             @if (old('description'))
{{ old('description') }}@else{{ $editData->description }}
@endif 
                                        </textarea>
                                                    </span>
                                                    @if ($errors->has('description'))
                                                        <span class="red-text"><?php echo $errors->first('description', ':message'); ?></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="image"> Image</label>
                                                    <input type="file" name="image" class="form-control"
                                                        id="english_image" accept="image/*" placeholder="image">
                                                    @if ($errors->has('image'))
                                                        <div class="red-text"><?php echo $errors->first('image', ':message'); ?>
                                                        </div>
                                                    @endif
                                                </div>
                                                <img id="english"
                                                    src="{{ Config::get('DocumentConstant.TESTIMONIAL_VIEW') }}{{ $editData->image }}"
                                                    class="img-fluid img-thumbnail" width="150">
                                                <img id="english_imgPreview" src="#" alt="pic"
                                                    class="img-fluid img-thumbnail" width="150" style="display:none">
                                            </div>
                                            <div class="col-md-12 col-sm-12 text-center">
                                                <button type="submit" class="btn btn-sm btn-success" id="submitButton">
                                                    Save &amp; Update
                                                </button>
                                                {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                                <span><a href="{{ route('list-testimonial') }}"
                                                        class="btn btn-sm btn-primary ">Back</a></span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" id="id" class="form-control"
                                            value="{{ $editData->id }}" placeholder="">

                                        {{-- <input type="text" name="currentMarathiImage" id="currentMarathiImage"
                                    class="form-control" value="" placeholder=""> --}}



                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
