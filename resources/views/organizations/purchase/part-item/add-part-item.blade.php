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
    .red-text{
        color: red !important;  
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Add Item Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                                <form action="{{ route('store-part-item') }}" method="POST" enctype="multipart/form-data" id="regForm">
                                    @csrf
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="part_number">Part No:</label>
                                                <input type="text" class="form-control" id="part_number" name="part_number" value="{{old('part_number') }}" placeholder="Enter part no">
                                                @if ($errors->has('part_number'))
                                                <span class="red-text">{{ $errors->first('part_number') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="description">Description:</label>
                                                <input type="text" class="form-control" id="description" name="description" value="{{old('description') }}" placeholder="Enter description">
                                                @if ($errors->has('description'))
                                                <span class="red-text">{{ $errors->first('description') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="extra_description">Extra Description : (optional)</label>
                                                <input type="text" class="form-control" id="extra_description" name="extra_description" value="{{old('extra_description') }}" placeholder="Enter extra description">
                                                @if ($errors->has('extra_description'))
                                                <span class="red-text">{{ $errors->first('extra_description') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="unit_id">Unit <span class="text-danger">*</span></label>
                                                <select class="form-control" name="unit_id" id="unit_id">
                                                    <option value="">Select Unit</option>
                                                    @foreach ($dataOutputUnitMaster as $data)
                                                        <option value="{{ $data['id'] }}" {{ old('unit_id') == $data['id'] ? 'selected' : '' }}>{{ $data['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('unit_id'))
                                                <span class="red-text">{{ $errors->first('unit_id') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="hsn_id">HSN/SAC No <span class="text-danger">*</span></label>
                                                <select class="form-control" name="hsn_id" id="hsn_id">
                                                    <option value="">Select HSN/SAC No</option>
                                                    @foreach ($dataOutputHSNMaster as $HSNMaster)
                                                        <option value="{{ $HSNMaster['id'] }}" {{ old('hsn_id') == $HSNMaster['id'] ? 'selected' : '' }}>{{ $HSNMaster['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('hsn_id'))
                                                <span class="red-text">{{ $errors->first('hsn_id') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="group_type_id">Group Master <span class="text-danger">*</span></label>
                                                <select class="form-control" name="group_type_id" id="group_type_id">
                                                    <option value="">Select Group</option>
                                                    @foreach ($dataOutputGroupMaster as $GroupMaster)
                                                        <option value="{{ $GroupMaster['id'] }}" {{ old('group_type_id') == $GroupMaster['id'] ? 'selected' : '' }}>{{ $GroupMaster['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('group_type_id'))
                                                <span class="red-text">{{ $errors->first('group_type_id') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="rack_id">Rack Number <span class="text-danger">*</span></label>
                                                <select class="form-control" name="rack_id" id="rack_id">
                                                    <option value="">Select Rack Number</option>
                                                    @foreach ($dataOutputGroupMaster as $GroupMaster)
                                                        <option value="{{ $GroupMaster['id'] }}" {{ old('rack_id') == $GroupMaster['id'] ? 'selected' : '' }}>{{ $GroupMaster['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('rack_id'))
                                                <span class="red-text">{{ $errors->first('rack_id') }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="rack_id">Rack Number<span class="text-danger">*</span></label>
                                                <select class="form-control" name="rack_id" id="rack_id">
                                                    <option value="">Select Rack Number</option>
                                                    @foreach ($dataOutputGroupMaster as $GroupMaster)
                                                        <option value="{{ $GroupMaster['id'] }}" {{ old('rack_id') == $GroupMaster['id'] ? 'selected' : '' }}>{{ $GroupMaster['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('rack_id'))
                                                <span class="red-text">{{ $errors->first('rack_id') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="basic_rate">Basic Rate:</label>
                                                <input type="text" class="form-control" id="basic_rate" name="basic_rate"  value="{{old('basic_rate') }}" placeholder="Enter basic rate">
                                                @if ($errors->has('basic_rate'))
                                                <span class="red-text">{{ $errors->first('basic_rate') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="opening_stock">Opening Stock:</label>
                                                <input type="text" class="form-control" id="opening_stock" name="opening_stock" value="{{old('opening_stock') }}" placeholder="Enter opening stock">
                                                @if ($errors->has('opening_stock'))
                                                <span class="red-text">{{ $errors->first('opening_stock') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-part-item') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Save Data</button>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $.validator.addMethod("spcenotallow", function(value, element) {
            return this.optional(element) || value.trim().length > 0;
        }, "Field cannot contain only spaces.");

        $("#regForm").validate({
            rules: {
                part_number: {
                    required: true,
                    spcenotallow: true
                },
                description: {
                    required: true,
                    spcenotallow: true
                },
                unit_id: {
                    required: true
                },
                hsn_id: {
                    required: true
                },
                group_type_id: {
                    required: true
                },
                rack_id: {
                    required: true
                },
                basic_rate: {
                    required: true,
                    number: true
                },
                opening_stock: {
                    required: true,
                    number: true
                }
            },
            messages: {
                part_number: {
                    required: "Please enter the part number.",
                    spcenotallow: "Part number cannot contain only spaces."
                },
                description: {
                    required: "Please enter a description.",
                    spcenotallow: "Description cannot contain only spaces."
                },
                unit_id: {
                    required: "Please select a unit."
                },
                hsn_id: {
                    required: "Please select an HSN/SAC number."
                },
                group_type_id: {
                    required: "Please select a group."
                },
                rack_id: {
                    required: "Please select a rack number."
                },
                basic_rate: {
                    required: "Please enter the basic rate.",
                    number: "Please enter a valid number."
                },
                opening_stock: {
                    required: "Please enter the opening stock.",
                    number: "Please enter a valid number."
                }
            }
        });
    });
</script>
@endsection
