@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="emp-form-card">
                        <div class="emp-form-header">
                            <h4><i class="fa fa-edit mr-2"></i> Edit Testimonial</h4>
                            <a href="{{ route('list-testimonial') }}" class="emp-form-close">&times;</a>
                        </div>
                        <div class="emp-form-body">

                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif

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
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input class="form-control" type="text" name="title" id="title"
                                                placeholder=" "
                                                value="@if (old('title')) {{ old('title') }}@else{{ $editData->title }} @endif">
                                            <label for="title">Title <span class="red-text">*</span></label>
                                            <label class="error py-2" for="title" id="title_error"></label>
                                            @if ($errors->has('title'))
                                                <span class="fl-error"><?php echo $errors->first('title', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input class="form-control" type="text" name="position" id="position"
                                                placeholder=" "
                                                value="@if (old('position')) {{ old('position') }}@else{{ $editData->position }} @endif">
                                            <label for="position">Position <span class="red-text">*</span></label>
                                            <label class="error py-2" for="position" id="position_error"></label>
                                            @if ($errors->has('position'))
                                                <span class="fl-error"><?php echo $errors->first('position', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group" id="summernote_id">
                                            <span class="summernote1">
                                                <textarea class="form-control" name="description" id="description" placeholder="Enter the Description">
                                             @if (old('description'))
{{ old('description') }}@else{{ $editData->description }}
@endif
                                        </textarea>
                                            </span>
                                            <label for="description">Description <span class="red-text">*</span></label>
                                            @if ($errors->has('description'))
                                                <span class="fl-error"><?php echo $errors->first('description', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="file" name="image" class="form-control"
                                                id="english_image" accept="image/*" placeholder="image">
                                            <label for="english_image">Image</label>
                                            @if ($errors->has('image'))
                                                <div class="fl-error"><?php echo $errors->first('image', ':message'); ?>
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
                                        <button type="submit" class="btn btn-sm btn-bg-colour" id="submitButton">
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

@endsection
