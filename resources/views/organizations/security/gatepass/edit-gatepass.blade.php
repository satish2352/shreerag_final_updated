@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Gatepass</h4>
    <a href="{{ route('list-gatepass') }}" class="emp-form-close" title="Close">&times;</a>
  </div>
  <div class="emp-form-body">
    @if(session('msg'))
      <div class="alert alert-{{ session('status') }}">{{ session('msg') }}</div>
    @endif
    <form action="{{ route('update-gatepass') }}" method="POST" enctype="multipart/form-data" id="regForm" autocomplete="off">
      @csrf
      <input type="hidden" class="form-control" value="@if(old('id')){{ old('id') }}@else{{ $editData->id }}@endif" id="id" name="id">
      <input type="hidden" class="form-control" value="@if(old('business_details_id')){{ old('business_details_id') }}@else{{ $editData->business_details_id }}@endif" id="business_details_id" name="business_details_id">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="purchase_orders_id" name="purchase_orders_id" placeholder=" "
              value="{{ $editData->purchase_orders_id }}" readonly>
            <label for="purchase_orders_id">PO <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="gatepass_name" name="gatepass_name" placeholder=" "
              value="{{ $editData->gatepass_name }}">
            <label for="gatepass_name">Name <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="date" class="form-control" id="gatepass_date" name="gatepass_date" placeholder=" "
              value="{{ $editData->gatepass_date }}">
            <label for="gatepass_date">Date <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="time" class="form-control" id="gatepass_time" name="gatepass_time" placeholder=" "
              value="{{ $editData->gatepass_time }}">
            <label for="gatepass_time">Time <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="remark" name="remark" placeholder=" "
              value="{{ $editData->remark }}">
            <label for="remark">Remark</label>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 text-center mt-4">
        <button type="button" onclick="window.location='{{ route('list-gatepass') }}'" class="btn btn-sm btn-secondary mr-2">Cancel</button>
        <button type="submit" class="btn btn-sm btn-bg-colour">Update Data</button>
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
        $.validator.addMethod("spcenotallow", function(value, element) {
            return this.optional(element) || value.trim().length > 0;
        }, "Enter some valid text");

        $("#regForm").validate({
            rules: {
                gatepass_name: { required: true, spcenotallow: true },
                gatepass_date: { required: true, date: true },
                gatepass_time: { required: true },
                remark: { spcenotallow: true },
            },
            messages: {
                gatepass_name: { required: "Please enter the name.", spcenotallow: "Name cannot contain only spaces." },
                gatepass_date: { required: "Please select a date.", date: "Enter a valid date." },
                gatepass_time: { required: "Please select a time." },
                remark: { spcenotallow: "Remark cannot contain only spaces." },
            },
            submitHandler: function(form) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Gatepass Updated successfully?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, submit it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>

@endsection
