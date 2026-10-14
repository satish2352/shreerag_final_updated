@extends('admin.layouts.master')

@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">

  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Rules &amp; Regulations</h4>
    <a href="{{ route('list-rules-regulations') }}" class="emp-form-close" title="Close">&times;</a>
  </div>

  <div class="emp-form-body">

    @if (Session::get('status') == 'success')
      <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Success!</strong> {{ Session::get('msg') }}
      </div>
    @endif

    @if (Session::get('status') == 'error')
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Error!</strong> {!! session('msg') !!}
      </div>
    @endif

    <form class="forms-sample" action="{{ route('add-rules-regulations') }}"
        method="POST" enctype="multipart/form-data" id="regForm">
      @csrf
      <div class="row">

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input class="form-control" name="title" id="title"
                placeholder=" " value="{{ old('title') }}">
            <label for="title">Title <span class="red-text">*</span></label>
            @if ($errors->has('title'))
              <span class="fl-error">{{ $errors->first('title') }}</span>
            @endif
          </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <textarea class="form-control" name="description" id="description"
                placeholder=" ">{{ old('description') }}</textarea>
            <label for="description">Description <span class="red-text">*</span></label>
            @if ($errors->has('description'))
              <span class="fl-error">{{ $errors->first('description') }}</span>
            @endif
          </div>
        </div>

        <div class="col-md-12 col-sm-12 text-center mt-3">
          <button type="submit" class="btn btn-sm btn-bg-colour" id="submitButton">Save &amp; Submit</button>
          <a href="{{ route('list-rules-regulations') }}" class="btn btn-sm btn-primary ml-2">Back</a>
        </div>

      </div>
    </form>

  </div>
</div>
</div></div></div></div>

<script>
    $(document).ready(function() {
        function checkFormValidity() {
            const title = $('#title').val();
            const image = $('#image').val();
        }

        $.validator.addMethod("fileExtension", function(value, element, param) {
            const extension = value.split('.').pop().toLowerCase();
            return $.inArray(extension, param) !== -1;
        }, "Invalid file extension.");

        $.validator.addMethod("fileSize", function(value, element, param) {
            const fileSizeKB = element.files[0].size / 1024;
            return fileSizeKB >= param[0] && fileSizeKB <= param[1];
        }, "File size must be between {0} KB and {1} KB.");

        $('#image').attr('accept', 'image/jpeg, image/png');
        $('input, textarea, #image').on('input change', checkFormValidity);

        $.validator.addMethod("spcenotallow", function(value, element) {
            if ("select" === element.nodeName.toLowerCase()) {
                var e = $(element).val();
                return e && e.length > 0;
            }
            return this.checkable(element) ? this.getLength(value, element) > 0 : value.trim().length > 0;
        }, "Enter Some Text");

        $("#regForm").validate({
            rules: {
                title: { required: true, spcenotallow: true },
                image: {
                    required: true,
                    fileExtension: ["jpg", "jpeg", "png"],
                    fileSize: [50, 1048],
                    imageDimensions: [200, 200, 1000, 1000],
                },
            },
            messages: {
                title: { required: "Please enter the Title.", spcenotallow: "Enter Some Title" },
                image: {
                    required: "Please upload an Image (jpg, jpeg, png).",
                    fileExtension: "Only JPG, JPEG, and PNG images are allowed.",
                    fileSize: "File size must be between 50 KB and 1048 KB.",
                    imageDimensions: "Image dimensions must be between 200x200 and 1000x1000 pixels.",
                },
            },
        });
    });
</script>
@endsection
