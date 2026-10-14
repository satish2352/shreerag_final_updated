@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Store Receipt</h4>
    <a href="{{ route('list-store-receipt') }}" class="emp-form-close" title="Close">&times;</a>
  </div>
  <div class="emp-form-body">
    @if(session('msg'))
      <div class="alert alert-{{ session('status') }}">{{ session('msg') }}</div>
    @endif
    @if(Session::get('status') == 'success')
      <div class="col-md-12">
        <div class="alert alert-success alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Success!</strong> {{ Session::get('msg') }}
        </div>
      </div>
    @endif
    @if(Session::get('status') == 'error')
      <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong>Error!</strong> {!! session('msg') !!}
        </div>
      </div>
    @endif

    <form action="{{ route('store-products') }}" method="POST" id="addDesignsForm" enctype="multipart/form-data">
      @csrf
      <div class="form-group-inner">
        <div class="container-fluid">
          @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
              </ul>
            </div>
          @endif
          @if(Session::has('success'))
            <div class="alert alert-success text-center">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
              <p>{{ Session::get('success') }}</p>
            </div>
          @endif
        </div>

        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="store_date">Date:</label>
            <input type="date" class="form-control" id="store_date" name="store_date">
          </div>
          <!--
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="receipt_no">Receipt No. :</label>
            <input type="text" class="form-control" id="receipt_no" name="receipt_no">
          </div> -->
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="name">Name of store person :</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name of store person">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="contact_number">Contact No. :</label>
            <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter Contact No.">
          </div>
        </div>

        <div style="margin-top:30px;">
          <table class="table table-bordered" id="dynamicTable">
            <tr>
              <th>Quantity</th>
              <th>Description</th>
              <th>Price</th>
              <th>Amount</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
            <tr>
              <td><input type="text" name="addmore[0][quantity]" placeholder="Enter your Quantity" class="form-control" /></td>
              <td><input type="text" name="addmore[0][description]" placeholder="Enter your description" class="form-control" /></td>
              <td><input type="text" name="addmore[0][price]" placeholder="Enter your Price" class="form-control" /></td>
              <td><input type="text" name="addmore[0][amount]" placeholder="Enter your amount" class="form-control" /></td>
              <td><input type="text" name="addmore[0][total]" placeholder="Enter your total" class="form-control" /></td>
              <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
            </tr>
          </table>
        </div>

        <div class='row'>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="remark">Remark :</label>
            <input type="text" class="form-control" id="remark" name="remark" placeholder="Enter Remark here">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="signature">Signature :</label>
            <input type="file" class="form-control" accept="image/*" id="signature" name="signature" placeholder="Enter signature">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <label for="status">Status :</label>
            <input type="text" class="form-control" id="status" name="status" placeholder="Enter Remark here">
          </div>
        </div>

        <div class="col-md-12 col-sm-12 text-center mt-4">
          <a href="{{ route('list-store-receipt') }}" class="btn btn-sm btn-secondary mr-2">Cancel</a>
          <button type="submit" class="btn btn-sm btn-bg-colour">Save Data</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div></div></div></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
var i = 0;

$("#add").click(function() {
    ++i;
    $("#dynamicTable").append(
        '<tr><td><input type="text" name="addmore[' + i + '][quantity]" placeholder="Enter your quantity" class="form-control" /></td><td><input type="text" name="addmore[' + i + '][description]" placeholder="Enter your description" class="form-control" /></td><td><input type="text" name="addmore[' + i + '][price]" placeholder="Enter your Price" class="form-control" /></td><td><input type="text" name="addmore[' + i + '][amount]" placeholder="Enter your amount" class="form-control" /></td><td><input type="text" name="addmore[' + i + '][total]" placeholder="Enter your total" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
    );
});

$(document).on("click", ".remove-tr", function() {
    $(this).parents("tr").remove();
});
</script>
<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
    $("#addDesignsForm").validate({
        rules: {
            store_date: { required: true },
            receipt_no: { required: true },
            name: { required: true },
            contact_number: { required: true },
            address: { required: true },
            quantity: { required: true },
            description: { required: true },
            price: { required: true },
            amount: { required: true },
            total: { required: true },
            remark: { required: true },
            signature: { required: true },
            status: { required: true },
        },
        messages: {
            receipt_date: { required: "Please enter a valid date." },
            name: { required: "Please enter  name." },
            contact_number: { required: "Please enter a valid contact no." },
            quantity: { required: "Please enter quantity." },
            description: { required: "Please enter  a valid description." },
            price: { required: "Please enter  price." },
            amount: { required: "Please enter amount" },
            total: { required: "Please enter  total" },
            remark: { required: "Please write remark" },
            signature: { required: "Please select an image.", accept: "Please select an image file." },
            status: { required: "Please enter status" },
        },
    });
});
</script>

@endsection
