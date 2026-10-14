@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">

  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Role</h4>
    <a href="{{ route('list-roles') }}" class="emp-form-close" title="Close">&times;</a>
  </div>

  <div class="emp-form-body">

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('store-roles') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row">

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="role_name" name="role_name"
                placeholder=" ">
            <label for="role_name">Role Name</label>
          </div>
        </div>

        <div class="col-md-12 col-sm-12 text-center mt-3">
          <a href="{{ route('list-roles') }}" class="btn btn-white">Cancel</a>
          <button class="btn btn-sm btn-primary ml-2" type="submit">Save Data</button>
        </div>

      </div>
    </form>

  </div>
</div>
</div></div></div></div>

<script src="js/password-meter/pwstrength-bootstrap.min.js"></script>
<script src="js/password-meter/zxcvbn.js"></script>
<script src="js/password-meter/password-meter-active.js"></script>

@endsection
