@extends('admin.layouts.master')

@section('content')
<style>
    .error{
        color: red !important;
    }
    .form-control{
        color: black !important;
    }
  
    .dropdown-menu {
        max-height: 300px;
        overflow-y: auto;
    }
    .dropdown-toggle {
        width: 100%;
        text-align: left;
    }
    </style>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="sparkline12-list">
                                            <div class="sparkline12-hd">
                                                <div class="main-sparkline12-hd">
                                                    <center>
                                                        <h1>Add Notice</h1>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="sparkline12-graph">
                                                <div class="basic-login-form-ad">
                                                    <div class="row">
                            
                            
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
                                                                    <div class="row d-flex justify-content-center form-display-center">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <form class="forms-sample" action="{{ route('add-notice') }}" method="POST"
                                enctype="multipart/form-data" id="regForm">
                                @csrf
                                <?php
// dd($dept);
// die();
                                ?>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="departments">Select Departments</label>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Select Departments
                                            </button>
                                    
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="padding: 10px;">
                                                <div class="form-check">
                                                    <input type="checkbox" id="selectAll" value="all" class="form-check-input">
                                                    <label for="selectAll" class="form-check-label"><b>All Departments</b></label>
                                                </div>
                                                <hr>
                                    
                                                @foreach($departments as $department)
                                                    <div class="form-check">
                                                        <input type="checkbox" name="department_id[]" value="{{ $department->id }}" class="form-check-input">
                                                        <label class="form-check-label">{{ $department->department_name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                    
                                    {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="company_id">Select Department <span class="text-danger">*</span></label>
                                            <select class="form-control custom-select-value" name="department_id" id="department_id">
                                                <ul class="">
                                                    <option value="">Select Department</option>
                                                    @foreach($dept as $datas)
                                                    <option value="{{$datas->id}}">{{ucfirst($datas->department_name)}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="title">Title <span class="text-danger">*</span></label>
                                            <input class="form-control mb-2" name="title" id="title"
                                                placeholder="Enter the Title" name="title"
                                                value="{{ old('title') }}">
                                            @if ($errors->has('title'))
                                                <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                    <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group" id="summernote_id">
                                            <label for="description">Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="description" id="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                                <span
                                                    class="red-text">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="image">Upload <span class="text-danger">*</span></label><br>
                                            {{-- <input type="file" name="image" id="image" accept="/*"
                                                value="{{ old('image') }}" class="form-control mb-2">
                                            @if ($errors->has('image'))
                                                <span class="red-text"><?php //echo $errors->first('image', ':message'); ?></span>
                                            @endif --}}

                                            <input type="file" class="form-control" accept="application/pdf"
                                            id="image" name="image">
                                        @if ($errors->has('image'))
                                            <span class="red-text"><?php echo $errors->first('image', ':message'); ?></span>
                                        @endif
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-sm btn-success" id="submitButton"  >
                                            Save &amp; Submit
                                        </button>
                                        {{-- <button type="reset" class="btn btn-sm btn-danger">Cancel</button> --}}
                                        <span><a href="{{ route('list-notice') }}"
                                                class="btn btn-sm btn-primary ">Back</a></span>
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
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
<script>
  $(document).ready(function() {
    // Custom validation rule to ensure at least one department is selected
    $.validator.addMethod("atLeastOneDepartment", function(value, element) {
        return $("input[name='department_id[]']:checked").length > 0;
    }, "Please select at least one department.");

    // Initialize form validation
    $("#regForm").validate({
        rules: {
            "department_id[]": {
                atLeastOneDepartment: true
            },
            title: {
                required: true
            },
            description: {
                required: true
            },
            image: {
                required: true,
                fileExtension: ["pdf"],
                fileSize: [50, 1048] // Min 50KB, Max 1MB
            }
        },
        messages: {
            title: {
                required: "Please select a title."
            },
            description: {
                required: "Please enter a description."
            },
            image: {
                required: "Please upload a PDF file.",
                fileExtension: "Only PDF files are allowed.",
                fileSize: "File size must be between 50 KB and 1 MB."
            },
            "department_id[]": {
                atLeastOneDepartment: "Please select at least one department."
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") === "department_id[]") {
                error.insertAfter($(".dropdown")); // Show error below the dropdown
            } else {
                error.insertAfter(element);
            }
        }
    });

    // "Select All" functionality
    $("#selectAll").change(function() {
        $("input[name='department_id[]']").prop("checked", $(this).prop("checked"));
    });

    $("input[name='department_id[]']").change(function() {
        if (!$(this).prop("checked")) {
            $("#selectAll").prop("checked", false);
        }
    });

    // Ensure dropdown remains open after a click (Bootstrap fix)
    $('.dropdown-menu input').click(function(e) {
        e.stopPropagation();
    });
});

</script>
<script>
    // "Select All" functionality
    document.getElementById('selectAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="department_id[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Auto-check "Select All" if all departments are manually selected
    const departmentCheckboxes = document.querySelectorAll('input[name="department_id[]"]');
    departmentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const allChecked = Array.from(departmentCheckboxes).every(cb => cb.checked);
            document.getElementById('selectAll').checked = allChecked;
        });
    });
</script>
    @endsection
