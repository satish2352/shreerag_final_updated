@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Team Member</h4>
    <a href="{{ route('list-team') }}" class="emp-form-close">&times;</a>
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

    <form class="forms-sample" action="{{ route('update-team') }}" method="post" id="regForm" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder=" " value="@if (old('name')) {{ old('name') }}@else{{ $editData->name }} @endif">
                    <label for="name">Name <span class="red-text">*</span></label>
                    @if ($errors->has('name'))<span class="fl-error">{{ $errors->first('name') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" name="position" id="position" placeholder=" " value="@if (old('position')) {{ old('position') }}@else{{ $editData->position }} @endif">
                    <label for="position">Position <span class="red-text">*</span></label>
                    @if ($errors->has('position'))<span class="fl-error">{{ $errors->first('position') }}</span>@endif
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="file" name="image" class="form-control" id="image" accept="image/*">
                    <label for="image">Image</label>
                    @if ($errors->has('image'))<span class="fl-error">{{ $errors->first('image') }}</span>@endif
                </div>
                <img id="english"
                    src="{{ Config::get('DocumentConstant.TEAM_VIEW') }}{{ $editData->image }}"
                    class="img-fluid img-thumbnail" width="150">
                <img id="english_imgPreview" src="#" alt="pic"
                    class="img-fluid img-thumbnail" width="150" style="display:none">
            </div>
            <div class="col-md-12 col-sm-12 text-center">
                <button type="submit" class="btn btn-sm btn-bg-colour" id="submitButton">
                    Save &amp; Update
                </button>
                {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                <span><a href="{{ route('list-team') }}" class="btn btn-sm btn-primary">Back</a></span>
            </div>
        </div>
        <input type="hidden" name="id" id="id" class="form-control" value="{{ $editData->id }}" placeholder="">
        {{-- <input type="text" name="currentMarathiImage" id="currentMarathiImage"
    class="form-control" value="" placeholder=""> --}}
    </form>

  </div>
</div>
</div></div></div></div>
@push('scripts')
<script>
    $(document).ready(function() {
        // Function to check if all input fields are filled with valid data
        function checkFormValidity() {
            const name = $('#name').val();
            const position = $('#position').val();
            // const image = $('#image').val();
        }

        // Custom validation method to check file extension
        $.validator.addMethod("fileExtension", function(value, element, param) {
            // Get the file extension
            const extension = value.split('.').pop().toLowerCase();
            return $.inArray(extension, param) !== -1;
        }, "Invalid file extension.");

        // Custom validation method to check file size
        $.validator.addMethod("fileSize", function(value, element, param) {
            // Convert bytes to KB
            const fileSizeKB = element.files[0].size / 1024;
            return fileSizeKB >= param[0] && fileSizeKB <= param[1];
        }, "File size must be between {0} KB and {1} KB.");

        // Update the accept attribute to validate based on file extension
        $('#image').attr('accept', 'image/jpeg, image/png');

        // Call the checkFormValidity function on input change
        $('input, textarea, #image').on('input change', checkFormValidity);
        $.validator.addMethod("spcenotallow", function(value, element) {
            if ("select" === element.nodeName.toLowerCase()) {
                var e = $(element).val();
                return e && e.length > 0;
            }
            return this.checkable(element) ? this.getLength(value, element) > 0 : value.trim().length >
                0;
        }, "Enter Some Text");

        // Initialize the form validation
        $("#regForm").validate({
            rules: {
                name: {
                    required: true,
                    spcenotallow: true,
                },
                position: {
                    required: true,
                    spcenotallow: true,
                },
                // image: {
                //     required: true,
                //     fileExtension: ["jpg", "jpeg", "png"],
                //     fileSize: [50, 1048], // Min 1KB and Max 2MB (2 * 1024 KB)
                //     imageDimensions: [200, 200, 1000, 1000], // Min width x height and Max width x height
                // },
            },
            messages: {
                name: {
                    required: "Please enter the name.",
                    spcenotallow: "Enter Some name",
                },
                position: {
                    required: "Please enter the position.",
                    spcenotallow: "Enter Some position",
                },
                // image: {
                //     required: "Please upload an Image (jpg, jpeg, png).",
                //     fileExtension: "Only JPG, JPEG, and PNG images are allowed.",
                //     fileSize: "File size must be between 50 KB and 1048 KB.",
                //     imageDimensions: "Image dimensions must be between 200x200 and 1000x1000 pixels.",
                // },
            },
        });
    });
</script>
@endpush
@endsection
