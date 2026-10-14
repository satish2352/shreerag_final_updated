@extends('admin.layouts.master')

@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">

  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Notice</h4>
    <a href="{{ route('list-notice') }}" class="emp-form-close" title="Close">&times;</a>
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

    <form class="forms-sample" action="{{ route('add-notice') }}" method="POST"
        enctype="multipart/form-data" id="regForm">
      @csrf
      <div class="row">

        {{-- Department Dropdown (checkbox multi-select) --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <label for="departments">Select Departments</label>
            <div class="dropdown">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Select Departments
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="padding: 10px; max-height: 300px; overflow-y: auto;">
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

        {{-- Title --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" name="title" id="title"
                placeholder=" " value="{{ old('title') }}">
            <label for="title">Title <span class="red-text">*</span></label>
            @if ($errors->has('title'))
              <span class="fl-error">{{ $errors->first('title') }}</span>
            @endif
          </div>
        </div>

        {{-- Description --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <textarea class="form-control" name="description" id="descriptionsumernote"
                placeholder=" ">{{ old('description') }}</textarea>
            <label for="descriptionsumernote">Description <span class="red-text">*</span></label>
            @if ($errors->has('description'))
              <span class="fl-error">{{ $errors->first('description') }}</span>
            @endif
          </div>
        </div>

        {{-- Upload PDF --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="file" class="form-control" accept="application/pdf" id="image" name="image">
            <label for="image">Upload PDF <span class="red-text">*</span></label>
            @if ($errors->has('image'))
              <span class="fl-error">{{ $errors->first('image') }}</span>
            @endif
          </div>
        </div>

        <div class="col-md-12 col-sm-12 text-center mt-3">
          <button type="submit" class="btn btn-sm btn-bg-colour" id="submitButton">Save &amp; Submit</button>
          <a href="{{ route('list-notice') }}" class="btn btn-sm btn-secondary ml-2">Back</a>
        </div>

      </div>
    </form>

  </div>
</div>
</div></div></div></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
  $(document).ready(function() {
    $.validator.addMethod("atLeastOneDepartment", function(value, element) {
        return $("input[name='department_id[]']:checked").length > 0;
    }, "Please select at least one department.");

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
                fileSize: [50, 1048]
            }
        },
        messages: {
            title: { required: "Please select a title." },
            description: { required: "Please enter a description." },
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
                error.insertAfter($(".dropdown"));
            } else {
                error.insertAfter(element);
            }
        }
    });

    $("#selectAll").change(function() {
        $("input[name='department_id[]']").prop("checked", $(this).prop("checked"));
    });

    $("input[name='department_id[]']").change(function() {
        if (!$(this).prop("checked")) {
            $("#selectAll").prop("checked", false);
        }
    });

    $('.dropdown-menu input').click(function(e) {
        e.stopPropagation();
    });
  });
</script>
<script>
    document.getElementById('selectAll').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="department_id[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    const departmentCheckboxes = document.querySelectorAll('input[name="department_id[]"]');
    departmentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const allChecked = Array.from(departmentCheckboxes).every(cb => cb.checked);
            document.getElementById('selectAll').checked = allChecked;
        });
    });
</script>
@endsection
