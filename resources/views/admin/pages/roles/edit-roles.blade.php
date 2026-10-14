@extends('admin.layouts.master')
@section('content')

<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">

  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Role</h4>
    <a href="{{ route('list-roles') }}" class="emp-form-close" title="Close">&times;</a>
  </div>

  <div class="emp-form-body">

    @if (session('msg'))
      <div class="alert alert-{{ session('status') }}">{{ session('msg') }}</div>
    @endif

    @if (Session::get('status') == 'success')
      <div class="alert alert-custom-success" id="success-alert">
        <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
      </div>
    @endif

    @if (Session::get('status') == 'error')
      <div class="alert alert-custom-danger" id="error-alert">
        <strong style="color: red;">Error!</strong> {!! session('msg') !!}
      </div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('update-roles') }}" method="POST" enctype="multipart/form-data"
        id="editEmployeeForm" autocomplete="off">
      @csrf
      <input type="hidden" class="form-control"
          value="@if (old('id')){{ old('id') }}@else{{ $editData->id }}@endif"
          id="id" name="id">

      <div class="row">

        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="text" class="form-control" id="role_name" name="role_name"
                placeholder=" "
                value="@if (old('role_name')){{ old('role_name') }}@else{{ $editData->role_name }}@endif">
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

<script src="{{ asset('js/password-meter/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('js/password-meter/zxcvbn.js') }}"></script>
<script src="{{ asset('js/password-meter/password-meter-active.js') }}"></script>

@endsection
