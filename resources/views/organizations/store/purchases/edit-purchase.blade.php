@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Store Purchase</h4>
    <a href="{{ route('list-store-purchase') }}" class="emp-form-close" title="Close">&times;</a>
  </div>
  <div class="emp-form-body">
    @if(Session::get('status') == 'success')
      <div class="col-12 grid-margin">
        <div class="alert alert-custom-success" id="success-alert">
          <button type="button" data-bs-dismiss="alert"></button>
          <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
        </div>
      </div>
    @endif
    @if(Session::get('status') == 'error')
      <div class="col-12 grid-margin">
        <div class="alert alert-custom-danger" id="error-alert">
          <button type="button" data-bs-dismiss="alert"></button>
          <strong style="color: red;">Error!</strong> {!! session('msg') !!}
        </div>
      </div>
    @endif
    <form action="{{ route('update-store-purchase', $editData->id) }}" method="POST" id="editDesignsForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="id" class="form-control" value="{{ $editData->id }}">
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="name" name="name" placeholder=" " value="{{ $editData->name }}">
            <label for="name">Name <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="email" class="form-control" id="email" name="email" placeholder=" " value="{{ $editData->email }}">
            <label for="email">Email <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="price" name="price" placeholder=" " value="{{ $editData->price }}">
            <label for="price">Price <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="contact" name="contact" placeholder=" " value="{{ $editData->contact }}">
            <label for="contact">Contact <span class="red-text">*</span></label>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 text-center mt-4">
        <a href="{{ route('list-store-purchase') }}" class="btn btn-sm btn-secondary mr-2">Cancel</a>
        <button type="submit" class="btn btn-sm btn-bg-colour">Update Data</button>
      </div>
    </form>
  </div>
</div>
</div></div></div></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $("#editDesignsForm").validate({
            rules: {
                name: { required: true },
                email: { required: true },
                price: { required: true },
                contact: { required: true },
            },
            messages: {
                name: { required: "Please enter  name." },
                email: { required: "Please enter a valid email ." },
                price: { required: "Please enter price." },
                contact: { required: "Please contact information." },
            },
        });
    });
</script>

@endsection
