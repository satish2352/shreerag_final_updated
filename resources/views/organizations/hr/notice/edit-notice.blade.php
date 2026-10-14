@extends('admin.layouts.master')

@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">

  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Notice</h4>
    <a href="{{ route('list-notice') }}" class="emp-form-close" title="Close">&times;</a>
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

    <form class="forms-sample" action="{{ route('update-notice') }}" method="post"
        id="regForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="id" class="form-control" value="{{ $editData->id }}">

      <div class="row">

        {{-- Department --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <select class="form-control {{ $editData->department_id ? 'has-value' : '' }}"
                name="department_id" id="department_id"
                onchange="this.classList.toggle('has-value', this.value !== '')">
              <option value="">Select Department</option>
              @foreach ($dept as $data)
                <option value="{{ $data->id }}"
                  @if ($editData->department_id == $data->id) selected @endif>
                  {{ $data->department_name }}
                </option>
              @endforeach
            </select>
            <label for="department_id">Department <span class="red-text">*</span></label>
            @if ($errors->has('department_id'))
              <span class="fl-error">{{ $errors->first('department_id') }}</span>
            @endif
          </div>
        </div>

        {{-- Title --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input class="form-control" name="title" id="title" placeholder=" "
                value="@if (old('title')){{ old('title') }}@else{{ $editData->title }}@endif">
            <label for="title">Title <span class="red-text">*</span></label>
            @if ($errors->has('title'))
              <span class="fl-error">{{ $errors->first('title') }}</span>
            @endif
          </div>
        </div>

        {{-- Description --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <div class="summernote1">
              <textarea class="form-control" name="description" id="descriptionsumernote"
                  placeholder=" ">@if (old('description')){{ old('description') }}@else{{ $editData->description }}@endif</textarea>
            </div>
            <label for="descriptionsumernote">Description <span class="red-text">*</span></label>
            @if ($errors->has('description'))
              <span class="fl-error">{{ $errors->first('description') }}</span>
            @endif
          </div>
        </div>

        {{-- Upload PDF --}}
        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
          <div class="fl-group">
            <input type="file" name="image" class="form-control" id="image"
                accept="application/pdf">
            <label for="image">Upload PDF (leave blank to keep existing)</label>
            @if ($errors->has('image'))
              <span class="fl-error">{{ $errors->first('image') }}</span>
            @endif
          </div>
        </div>

        <div class="col-md-12 col-sm-12 text-center mt-3">
          <button type="submit" class="btn btn-sm btn-success" id="submitButton">Save &amp; Update</button>
          <a href="{{ route('list-notice') }}" class="btn btn-sm btn-bg-colour ml-2">Back</a>
        </div>

      </div>
    </form>

  </div>
</div>
</div></div></div></div>

@endsection
