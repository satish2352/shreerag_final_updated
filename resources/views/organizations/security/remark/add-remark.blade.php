@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Remark</h4>
    <a href="{{ route('list-security-remark') }}" class="emp-form-close" title="Close">&times;</a>
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
    <form action="" method="POST" id="addDesignsForm" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="purchase_id" name="purchase_id" placeholder=" ">
            <label for="purchase_id">Select PO Number <span class="red-text">*</span></label>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="remark" name="remark" placeholder=" ">
            <label for="remark">Remark <span class="red-text">*</span></label>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 text-center mt-4">
        <a href="{{ route('list-security-remark') }}" class="btn btn-sm btn-secondary mr-2">Cancel</a>
        <button type="submit" class="btn btn-sm btn-bg-colour">Save Data</button>
      </div>
    </form>
  </div>
</div>
</div></div></div></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script>
var i = 0;
</script>
<script>
jQuery.noConflict();
jQuery(document).ready(function($) {
    $("#addDesignsForm").validate({
        rules: {
            purchase_id: { required: true },
            department_id: { required: true },
            remark: { required: true },
            status: { required: true },
        },
        messages: {
            purchase_id: { required: "Please Enter Po Number." },
            department_id: { required: "Please Enter Department." },
            remark: { required: "Please enter Remark." },
            status: { required: "Please enter Status." },
        },
    });
});
</script>

@endsection
