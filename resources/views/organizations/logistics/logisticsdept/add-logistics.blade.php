@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Logistics</h4>
    <a href="{{ route('list-final-production-completed-recive-to-logistics') }}" class="emp-form-close" title="Close">&times;</a>
  </div>
  <div class="emp-form-body">
    @if(session('msg'))
      <div class="alert alert-{{ session('status') }}">{{ session('msg') }}</div>
    @endif
    @if(Session::has('status'))
      <div class="alert alert-{{ Session::get('status') }} alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>{{ ucfirst(Session::get('status')) }}!</strong> {{ Session::get('msg') }}
      </div>
    @endif
    <form action="{{ route('store-logistics', $editData->quantity_tracking_id) }}" method="POST" id="editLogisticsForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="business_id" class="form-control" value="{{ $editData->quantity_tracking_id }}">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input class="form-control" name="customer_po_number" id="customer_po_number" placeholder=" "
              value=" @if(old('customer_po_number')){{ old('customer_po_number') }}@else{{ $editData->customer_po_number }}@endif" readonly>
            <label for="customer_po_number">PO Number <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="product_name" name="product_name" placeholder=" "
              value=" @if(old('product_name')){{ old('product_name') }}@else{{ $editData->product_name }}@endif" readonly>
            <label for="product_name">Product Name <span class="red-text">*</span></label>
            @if($errors->has('product_name'))<span class="fl-error">{{ $errors->first('product_name') }}</span>@endif
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="quantity" name="quantity" placeholder=" "
              value=" @if(old('quantity')){{ old('quantity') }}@else{{ $editData->quantity }}@endif" readonly>
            <label for="quantity">Total Quantity <span class="red-text">*</span></label>
            @if($errors->has('quantity'))<span class="fl-error">{{ $errors->first('quantity') }}</span>@endif
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="completed_quantity" name="completed_quantity" placeholder=" "
              value=" @if(old('completed_quantity')){{ old('completed_quantity') }}@else{{ $editData->completed_quantity }}@endif" readonly>
            <label for="completed_quantity">Actual Production Quantity <span class="red-text">*</span></label>
            @if($errors->has('completed_quantity'))<span class="fl-error">{{ $errors->first('completed_quantity') }}</span>@endif
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="title" name="title" placeholder=" "
              value=" @if(old('title')){{ old('title') }}@else{{ $editData->title }}@endif" readonly>
            <label for="title">Vendor Name <span class="red-text">*</span></label>
            @if($errors->has('title'))<span class="fl-error">{{ $errors->first('title') }}</span>@endif
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <select class="form-control {{ old('vehicle_type_id') ? 'has-value' : '' }}" id="vehicle_type_id" name="vehicle_type_id"
              onchange="myFunction(this.value); this.classList.toggle('has-value', this.value !== '')">
              <option value=""></option>
              @foreach($dataOutputVehicleType as $role)
                <option value="{{ $role['id'] }}" {{ old('vehicle_type_id') == $role['id'] ? 'selected' : '' }}>{{ $role['name'] }}</option>
              @endforeach
            </select>
            <label for="vehicle_type_id">Vehicle Type <span class="red-text">*</span></label>
            @if($errors->has('vehicle_type_id'))<span class="fl-error">{{ $errors->first('vehicle_type_id') }}</span>@endif
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <select class="form-control {{ old('transport_name_id') ? 'has-value' : '' }}" id="transport_name_id" name="transport_name_id"
              onchange="myFunction(this.value); this.classList.toggle('has-value', this.value !== '')">
              <option value=""></option>
              @foreach($dataOutputTransportName as $role)
                <option value="{{ $role['id'] }}" {{ old('transport_name_id') == $role['id'] ? 'selected' : '' }}>{{ $role['name'] }}</option>
              @endforeach
            </select>
            <label for="transport_name_id">Transport Name <span class="red-text">*</span></label>
            @if($errors->has('transport_name_id'))<span class="fl-error">{{ $errors->first('transport_name_id') }}</span>@endif
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="truck_no" name="truck_no" placeholder=" " value=" ">
            <label for="truck_no">Truck Number <span class="red-text">*</span></label>
            @if($errors->has('truck_no'))<span class="fl-error">{{ $errors->first('truck_no') }}</span>@endif
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="from_place" name="from_place" placeholder=" " value=" ">
            <label for="from_place">From Place <span class="red-text">*</span></label>
            @if($errors->has('from_place'))<span class="fl-error">{{ $errors->first('from_place') }}</span>@endif
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="to_place" name="to_place" placeholder=" " value=" ">
            <label for="to_place">To Place <span class="red-text">*</span></label>
            @if($errors->has('to_place'))<span class="fl-error">{{ $errors->first('to_place') }}</span>@endif
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 text-center mt-4">
        <a href="{{ route('list-final-production-completed-recive-to-logistics') }}" class="btn btn-sm btn-secondary mr-2">Cancel</a>
        <button type="submit" class="btn btn-sm btn-bg-colour">Save Data</button>
      </div>
    </form>
  </div>
</div>
</div></div></div></div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
{{-- <script>
    $(document).ready(function () {
        $.validator.addMethod("spcenotallow", function (value, element) {
            return value.trim().length > 0;
        }, "This field cannot be empty or only spaces.");

        $("#editLogisticsForm").validate({
            rules: {
                vehicle_type_id: { required: true },
                transport_name_id: { required: true },
                from_place: { required: true, spcenotallow: true },
                to_place: { required: true, spcenotallow: true },
                truck_no: { required: true, spcenotallow: true }
            },
            messages: {
                vehicle_type_id: { required: "Please select the vehicle type." },
                transport_name_id: { required: "Please select the transport name." },
                from_place: { required: "Please enter the origin place.", spcenotallow: "Origin place cannot be empty or only spaces." },
                to_place: { required: "Please enter the destination place.", spcenotallow: "Destination place cannot be empty or only spaces." },
                truck_no: { required: "Please enter the truck number.", spcenotallow: "Truck number cannot be empty or only spaces." }
            },
            errorElement: "span",
            errorClass: "error",
            highlight: function (element) { $(element).addClass('is-invalid'); },
            unhighlight: function (element) { $(element).removeClass('is-invalid'); }
        });
    });
</script> --}}
<script>
    $(document).ready(function () {
        $.validator.addMethod("spcenotallow", function (value, element) {
            return value.trim().length > 0;
        }, "This field cannot be empty or only spaces.");

        $("#editLogisticsForm").validate({
            rules: {
                vehicle_type_id: { required: true },
                transport_name_id: { required: true },
                from_place: { required: true, spcenotallow: true },
                to_place: { required: true, spcenotallow: true },
                truck_no: { required: true, spcenotallow: true }
            },
            messages: {
                vehicle_type_id: { required: "Please select the vehicle type." },
                transport_name_id: { required: "Please select the transport name." },
                from_place: { required: "Please enter the origin place.", spcenotallow: "Origin place cannot be empty or only spaces." },
                to_place: { required: "Please enter the destination place.", spcenotallow: "Destination place cannot be empty or only spaces." },
                truck_no: { required: "Please enter the truck number.", spcenotallow: "Truck number cannot be empty or only spaces." }
            },
            errorElement: "span",
            errorClass: "error",
            highlight: function (element) { $(element).addClass('is-invalid'); },
            unhighlight: function (element) { $(element).removeClass('is-invalid'); },
            submitHandler: function (form) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to submit the Logistics data to Fianance Department ?",
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
