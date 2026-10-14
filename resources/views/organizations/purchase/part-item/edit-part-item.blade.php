@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-edit mr-2"></i> Edit Part Item</h4>
    <a href="{{ route('list-part-item') }}" class="emp-form-close" title="Close">&times;</a>
  </div>
  <div class="emp-form-body">
    @if(session('msg'))
    <div class="alert alert-{{ session('status') }} mb-3">{{ session('msg') }}</div>
    @endif
    <form action="{{ route('update-part-item') }}" method="POST" enctype="multipart/form-data" id="regForm" autocomplete="off">
        @csrf
        <input type="hidden" value="{{ old('part_item_id', $editData->part_item_id) }}" id="part_item_id" name="part_item_id">

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" value="{{ old('part_number', $editData->part_number) }}" id="part_number" name="part_number" placeholder=" ">
                    <label for="part_number">Part Number <span class="red-text">*</span></label>
                    @if($errors->has('part_number'))<span class="fl-error">{{ $errors->first('part_number') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" value="{{ old('description', $editData->description) }}" id="description" name="description" placeholder=" ">
                    <label for="description">Description <span class="red-text">*</span></label>
                    @if($errors->has('description'))<span class="fl-error">{{ $errors->first('description') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" value="{{ old('extra_description', $editData->extra_description) }}" id="extra_description" name="extra_description" placeholder=" ">
                    <label for="extra_description">Extra Description (optional)</label>
                    @if($errors->has('extra_description'))<span class="fl-error">{{ $errors->first('extra_description') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <select class="form-control has-value" name="unit_id" id="unit_id" onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="" default>Select Unit Name</option>
                        @foreach ($dataOutputUnitMaster as $service)
                            <option value="{{ $service->id }}" {{ old('unit_id', $editData->unit_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="unit_id">Unit Name <span class="red-text">*</span></label>
                    @if($errors->has('unit_id'))<span class="fl-error">{{ $errors->first('unit_id') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <select class="form-control has-value" name="hsn_id" id="hsn_id" onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="" default>Select HSN Name</option>
                        @foreach ($dataOutputHSNMaster as $hsn_data)
                            <option value="{{ $hsn_data->id }}" {{ old('hsn_id', $editData->hsn_id) == $hsn_data->id ? 'selected' : '' }}>
                                {{ $hsn_data->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="hsn_id">HSN Name <span class="red-text">*</span></label>
                    @if($errors->has('hsn_id'))<span class="fl-error">{{ $errors->first('hsn_id') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <select class="form-control has-value" name="group_type_id" id="group_type_id" onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="" default>Select Group Name</option>
                        @foreach ($dataOutputGroupMaster as $group_data)
                            <option value="{{ $group_data->id }}" {{ old('group_type_id', $editData->group_type_id) == $group_data->id ? 'selected' : '' }}>
                                {{ $group_data->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="group_type_id">Group Name <span class="red-text">*</span></label>
                    @if($errors->has('group_type_id'))<span class="fl-error">{{ $errors->first('group_type_id') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <select class="form-control has-value" name="rack_id" id="rack_id" onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="" default>Select Rack Number</option>
                        @foreach ($dataRackMaster as $rack)
                            <option value="{{ $rack->id }}" {{ old('rack_id', $editData->rack_id) == $rack->id ? 'selected' : '' }}>
                                {{ $rack->name }}
                            </option>
                        @endforeach
                    </select>
                    <label for="rack_id">Rack Number (optional)</label>
                    @if($errors->has('rack_id'))<span class="fl-error">{{ $errors->first('rack_id') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <label for="image" style="position:static; font-size:13px; margin-bottom:5px; display:block;">Image</label>
                    <input type="file" name="image" class="form-control" id="image" accept="image/*" placeholder="image">
                    <img id="english"
                        src="{{ Config::get('DocumentConstant.PART_ITEM_VIEW') }}{{ $editData->image }}"
                        class="img-fluid img-thumbnail mt-1" width="150">
                    <img id="english_imgPreview" src="#" alt="pic"
                        class="img-fluid img-thumbnail mt-1" width="150" style="display:none">
                    @if($errors->has('image'))<div class="fl-error">{{ $errors->first('image') }}</div>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" value="{{ old('basic_rate', $editData->basic_rate) }}" id="basic_rate" name="basic_rate" placeholder=" ">
                    <label for="basic_rate">Basic Rate <span class="red-text">*</span></label>
                    @if($errors->has('basic_rate'))<span class="fl-error">{{ $errors->first('basic_rate') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" value="{{ old('opening_stock', $editData->opening_stock) }}" id="opening_stock" name="opening_stock" placeholder=" " disabled>
                    <label for="opening_stock">Open Stock <span class="red-text">*</span></label>
                    @if($errors->has('opening_stock'))<span class="fl-error">{{ $errors->first('opening_stock') }}</span>@endif
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-right">
                <a href="{{ route('list-part-item') }}" class="btn btn-secondary mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </div>
    </form>
  </div>
</div>
</div></div></div></div>
@endsection
