@extends('admin.layouts.master')
@section('content')
<style>
label {
    margin-top: 20px;
}

label.error {
    color: red;
    /* Change 'red' to your desired text color */
    font-size: 12px;
    /* Adjust font size if needed */
    /* Add any other styling as per your design */
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center>
                        <h1>Add Requistion Data</h1>
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
                            <div class="col-md-12">
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Success!</strong> {{ Session::get('msg') }}
                                </div>
                            </div>
                            @endif

                            @if (Session::get('status') == 'error')
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Error!</strong> {!! session('msg') !!}
                                </div>
                            </div>
                            @endif
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                    <form action="{{ route('store-purchase-request-req') }}" method="POST" id="regForm"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group-inner">

                                            {{-- ========================== --}}
                                            <div class="container-fluid">
                                                @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif

                                                @if (Session::has('success'))
                                                <div class="alert alert-success text-center">

                                                    <a href="#" class="close" data-dismiss="alert"
                                                        aria-label="close">×</a>

                                                    <p>{{ Session::get('success') }}</p>

                                                </div>
                                                @endif


                                            </div>

                                            {{-- =================== --}}

                                            <div class="row">
                                               
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="bom_file_req">Bill Of Material (upload excel file min:1KB to 5MB) :</label>
                                                     <input type="file" class="form-control" accept=".xls, .xlsx" id="bom_file_req"
                                                         name="bom_file_req" placeholder="Enter bom_file_req">
                                                         <input type="hidden" class="form-control" id="production_id"
                                                        name="production_id" value="{{$createRequesition}}" placeholder="Enter your requistion number ">
                                                 </div>
                                                </div>


                                                <div style="margin-top:30px;" >
                                                <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 " > -->                                    
                                                                                                </div>
                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-requisition') }}" class="btn btn-white"
                                                                style="margin-bottom:50px">Cancel</a>
                                                            <button class="btn btn-sm btn-primary login-submit-cs"
                                                                type="submit" style="margin-bottom:50px">Submit Requisition Details To Purchase 
                                                                </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
  $(document).ready(function() {
    // Custom validation method to check file extension
    $.validator.addMethod("fileExtension", function(value, element, param) {
        const extension = value.split('.').pop().toLowerCase();
        return $.inArray(extension, param) !== -1;
    }, "Invalid file extension.");

    // Custom validation method to check file size
    $.validator.addMethod("fileSize", function(value, element, param) {
        if (element.files.length === 0) {
            return false; // No file selected
        }
        const fileSizeKB = element.files[0].size / 1024;
        return fileSizeKB >= param[0] && fileSizeKB <= param[1];
    }, "File size must be between {0} KB and {1} KB.");

    // Initialize the form validation
    $("#regForm").validate({
        rules: {
            bom_file_req: {
                required: true,
                fileExtension: ["xlsx", "xls"],
                fileSize: [1, 5120], // Min 1KB and Max 2MB (2 * 1024 KB)
            },
        },
        messages: {
            bom_file_req: {
                required: "Please upload an Excel file (xlsx, xls).",
                fileExtension: "Only XLSX and XLS files are allowed.",
                fileSize: "File size must be between 1 KB and 5120 KB.",
            },
        },
    });

    // Update the accept attribute to validate based on file extension
    $('#bom_file_req').attr('accept', '.xlsx, .xls');

    // Event listener to clear validation messages when a file is selected
    $('#bom_file_req').on('change', function() {
        // Clear validation errors for this field
        $(this).valid();  // This triggers the validation and clears errors if the field is valid
    });
});

</script>




@endsection