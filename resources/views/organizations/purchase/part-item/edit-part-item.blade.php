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
    .marg-top{
        margin-top: 30px;
    }
    .form-control  {
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
                                        <input type="hidden" class="form-control" value="@if (old('id')) {{ old('id') }}@else{{ $editData->id }} @endif" id="id" name="id">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="part_number">Part Number  <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="@if (old('part_number')) {{ old('part_number') }}@else{{ $editData->part_number }} @endif" id="part_number" name="part_number" placeholder="Enter part item name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="description">Description  <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="@if (old('description')) {{ old('description') }}@else{{ $editData->description }} @endif" id="description" name="description" placeholder="Enter part item name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="extra_description">Extra Description (optional)</label>
                                                <input type="text" class="form-control" value="@if (old('extra_description')) {{ old('extra_description') }}@else{{ $editData->extra_description }} @endif" id="extra_description" name="extra_description" placeholder="Enter part extra description">
                                            </div>
                                          
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <div class="form-group">
                                                    <label for="unit_id">Unit Name <span class="text-danger">*</span></label>
                                                    <select class="form-control mb-2"
                                                        name="unit_id" id="unit_id">
                                                        <option value="" default>Select
                                                            Unit Name</option>
                                                        @foreach ($dataOutputUnitMaster as $service)
                                                            <option value="{{ $service['id'] }}"
                                                                {{ old('unit_id', $editData->unit_id) == $service->id ? 'selected' : '' }}>
                                                                {{ $service->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('unit_id'))
                                                        <span
                                                            class="red-text">{{ $errors->first('unit_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <div class="form-group">
                                                    <label for="hsn_id">HSN Name <span class="text-danger">*</span></label>
                                                    <select class="form-control mb-2"
                                                        name="hsn_id" id="hsn_id">
                                                        <option value="" default>Select
                                                            HSN Name</option>
                                                        @foreach ($dataOutputHSNMaster as $hsn_data)
                                                            <option value="{{ $hsn_data['id'] }}"
                                                                {{ old('hsn_id', $editData->hsn_id) == $hsn_data->id ? 'selected' : '' }}>
                                                                {{ $hsn_data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('hsn_id'))
                                                        <span
                                                            class="red-text">{{ $errors->first('hsn_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <div class="form-group">
                                                    <label for="group_type_id">Group Name <span class="text-danger">*</span></label>
                                                    <select class="form-control mb-2"
                                                        name="group_type_id" id="group_type_id">
                                                        <option value="" default>Select
                                                            Group Name</option>
                                                        @foreach ($dataOutputGroupMaster as $group_data)
                                                            <option value="{{ $group_data['id'] }}"
                                                                {{ old('group_type_id', $editData->group_type_id) == $group_data->id ? 'selected' : '' }}>
                                                                {{ $group_data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('group_type_id'))
                                                        <span
                                                            class="red-text">{{ $errors->first('group_type_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <div class="form-group">
                                                    <label for="rack_id">Rack Number </label> (optional)
                                                    <select class="form-control mb-2"
                                                        name="rack_id" id="rack_id">
                                                        <option value="" default>Select Rack Number</option>
                                                        @foreach ($dataRackMaster as $rack_data)
                                                            <option value="{{ $rack_data['id'] }}"
                                                                {{ old('rack_id', $editData->rack_id) == $rack_data->id ? 'selected' : '' }}>
                                                                {{ $rack_data->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('rack_id'))
                                                        <span
                                                            class="red-text">{{ $errors->first('rack_id') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="basic_rate">Basic Rate <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="@if (old('basic_rate')) {{ old('basic_rate') }}@else{{ $editData->basic_rate }} @endif" id="basic_rate" name="basic_rate" placeholder="Enter part item name">
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="opening_stock">Open Stock <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="@if (old('opening_stock')) {{ old('opening_stock') }}@else{{ $editData->opening_stock }} @endif" id="opening_stock" name="opening_stock" placeholder="Enter open stock" disabled>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->

<script>
    $(document).ready(function() {


        $("#addEmployeeForm").on('submit', function() {
    // Trim the input value before submitting the form
    var basicrateInput = $('#basic_rate');
    var openingstockInput = $('#opening_stock');
    basicrateInput.val($.trim(basicrateInput.val()));
    openingstockInput.val($.trim(openingstockInput.val()));
});

        // $.validator.addMethod("spcenotallow", function(value, element) {
        //     return this.optional(element) || value.trim().length > 0;
        // }, "Field cannot contain only spaces.");

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
                // rack_id: {
                //     required: true
                // },
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
                // rack_id: {
                //     required: "Please select a rack number."
                // },
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
