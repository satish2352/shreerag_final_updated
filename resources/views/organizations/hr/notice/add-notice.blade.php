@extends('admin.layouts.master')

@section('content')
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
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="company_id">Select Department:</label>
                                            <select class="form-control custom-select-value" name="department_id" id="department_id">
                                                <ul class="dropdown-menu ">
                                                    <option value="">Select Department</option>
                                                    @foreach($dept as $datas)
                                                    <option value="{{$datas->id}}">{{ucfirst($datas->department_name)}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input class="form-control mb-2" name="title" id="title"
                                                placeholder="Enter the Title" name="title"
                                                value="{{ old('title') }}">
                                            @if ($errors->has('title'))
                                                <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group" id="summernote_id">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description" id="description" placeholder="Enter Page Content">{{ old('description') }}</textarea>
                                            @if ($errors->has('description'))
                                                <span
                                                    class="red-text">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="image">Upload </label><br>
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
                                        <span><a href="{{ route('list-services') }}"
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
           

        // Initialize the form validation
        $("#regForm").validate({
            rules: {
                department_id: {
                    required: true,
               
                    department_id:true,
                },
                title: {
                    required: true,
                },
                description: {
                    required: true,
                },
             

            },
            messages: {
                department_id: {
                    required: "Please slect deparment name",
                    // remote: "This Email already exists."
                },
                title: {
                    required: "Please Select Role Name",
                },
                description: {
                    required: "Please Enter the Password",
                },
               
            },

        });
    });
</script>
    @endsection
