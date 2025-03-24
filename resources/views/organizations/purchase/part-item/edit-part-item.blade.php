@extends('admin.layouts.master')
@section('content')
<style>
    .form-control {
        border: 2px solid #ced4da;
        border-radius: 4px;
    }
    .error {
        color: red !important;
    }
    .red-text {
        color: red !important;  
    }
    .marg-top {
        margin-top: 30px;
    }
    .form-control {
        color: #303030 !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Update Item Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        @if(session('msg'))
                            <div class="alert alert-{{ session('status') }}">
                                {{ session('msg') }}
                            </div>
                        @endif
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                                <form action="{{ route('update-part-item') }}" method="POST" enctype="multipart/form-data" id="regForm" autocomplete="off">
                                    @csrf
                                    <div class="form-group-inner">
                                        <input type="hidden" class="form-control" value="{{ old('part_item_id', $editData->part_item_id) }}" id="part_item_id" name="part_item_id">

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="part_number">Part Number <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ old('part_number', $editData->part_number) }}" id="part_number" name="part_number" placeholder="Enter part number">
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="description">Description <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ old('description', $editData->description) }}" id="description" name="description" placeholder="Enter description">
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="extra_description">Extra Description (optional)</label>
                                                <input type="text" class="form-control" value="{{ old('extra_description', $editData->extra_description) }}" id="extra_description" name="extra_description" placeholder="Enter extra description">
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <div class="form-group">
                                                    <label for="unit_id">Unit Name <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="unit_id" id="unit_id">
                                                        <option value="" default>Select Unit Name</option>
                                                        @foreach ($dataOutputUnitMaster as $service)
                                                            <option value="{{ $service->id }}" {{ old('unit_id', $editData->unit_id) == $service->id ? 'selected' : '' }}>
                                                                {{ $service->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('unit_id'))
                                                        <span class="red-text">{{ $errors->first('unit_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <div class="form-group">
                                                    <label for="hsn_id">HSN Name <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="hsn_id" id="hsn_id">
                                                        <option value="" default>Select HSN Name</option>
                                                        @foreach ($dataOutputHSNMaster as $hsn_data)
                                                            <option value="{{ $hsn_data->id }}" {{ old('hsn_id', $editData->hsn_id) == $hsn_data->id ? 'selected' : '' }}>
                                                                {{ $hsn_data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('hsn_id'))
                                                        <span class="red-text">{{ $errors->first('hsn_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <div class="form-group">
                                                    <label for="group_type_id">Group Name <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="group_type_id" id="group_type_id">
                                                        <option value="" default>Select Group Name</option>
                                                        @foreach ($dataOutputGroupMaster as $group_data)
                                                            <option value="{{ $group_data->id }}" {{ old('group_type_id', $editData->group_type_id) == $group_data->id ? 'selected' : '' }}>
                                                                {{ $group_data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('group_type_id'))
                                                        <span class="red-text">{{ $errors->first('group_type_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <?php
// dd($dataRackMaster);
// die();
                                            ?>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <div class="form-group">
                                                    <label for="rack_id">Rack Number (optional)</label>
                                                    <select class="form-control" name="rack_id" id="rack_id">
                                                        <option value="" default>Select Rack Number</option>
                                                        @foreach ($dataRackMaster as $rack)
                                                            <option value="{{ $rack->id }}" {{ old('rack_id', $editData->rack_id) == $rack->id ? 'selected' : '' }}>
                                                                {{ $rack->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('rack_id'))
                                                        <span class="red-text">{{ $errors->first('rack_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="basic_rate">Basic Rate <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ old('basic_rate', $editData->basic_rate) }}" id="basic_rate" name="basic_rate" placeholder="Enter basic rate">
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="opening_stock">Open Stock <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ old('opening_stock', $editData->opening_stock) }}" id="opening_stock" name="opening_stock" placeholder="Enter open stock" disabled>
                                            </div>
                                        </div>

                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-part-item') }}">
                                                            <button class="btn btn-white" style="margin-bottom:50px">Cancel</button>
                                                        </a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Update Data</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
