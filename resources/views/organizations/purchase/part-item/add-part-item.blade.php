@extends('admin.layouts.master')
@section('content')
<style>
    .form-control {
      border: 2px solid #ced4da;
      border-radius: 4px;
      background-color:#fff !important;
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

      .custom-dropdown {
        position: relative;
        width: 100%;
    }
.dropdown-height{
    height: 280px !important;
}
    .custom-dropdown .dropdown-options {
        position: absolute;
        width:600px !important;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ccc;
        z-index: 999;
        /* max-height: 200px; */
        overflow-y: auto;
    }

    .custom-dropdown .option {
        padding: 6px 10px;
        cursor: pointer;
    }

    .custom-dropdown .option:hover {
        background: #f0f0f0;
    }

    .custom-dropdown .search-box {
        border-bottom: 1px solid #ccc;
        margin-bottom: 5px;
    }
    .margin-bottom{
        margin-bottom: 100px !important;
    }
</style>
<div class="">
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
                                                <label for="part_number">Part No <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="part_number" name="part_number" value="{{old('part_number') }}" placeholder="Enter part no">
                                                @if ($errors->has('part_number'))
                                                <span class="red-text">{{ $errors->first('part_number') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="description">Description <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="description" name="description" value="{{old('description') }}" placeholder="Enter description">
                                                @if ($errors->has('description'))
                                                <span class="red-text">{{ $errors->first('description') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="extra_description">Extra Description : (optional)</label>
                                                <input type="text" class="form-control" id="extra_description" name="extra_description" value="{{old('extra_description') }}" placeholder="Enter extra description">
                                                @if ($errors->has('extra_description'))
                                                <span class="red-text">{{ $errors->first('extra_description') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
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
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="hsn_id">HSN/SAC No <span class="text-danger">*</span></label>

                                            
                                                                    <div class="custom-dropdown">
                                                                        <input type="hidden" name="hsn_id" class="part_no" value="{{ old('hsn_id') }}">
@php
    $selectedPartItem = $dataOutputHSNMaster->firstWhere('id', old('hsn_id'));
@endphp
<input type="text" class="dropdown-input form-control part-no" placeholder="Select HSN/SAC No..." readonly value="{{ $selectedPartItem->name ?? '' }}">


                                                                        <div class="dropdown-options dropdown-height" style="display: none;">
                                                                            <input type="text" class="search-box form-control" placeholder="Search...">
                                                                            <div class="options-list">
                                                                                @foreach ($dataOutputHSNMaster as $data)
                                                                                    <div class="option" data-id="{{ $data->id }}">{{ $data->name }}</div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                               </div>

                                      
                                                <!-- <select class="form-control" name="hsn_id" id="hsn_id">
                                                    <option value="">Select HSN/SAC No</option>
                                                    @foreach ($dataOutputHSNMaster as $HSNMaster)
                                                        <option value="{{ $HSNMaster['id'] }}" {{ old('hsn_id') == $HSNMaster['id'] ? 'selected' : '' }}>{{ $HSNMaster['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('hsn_id'))
                                                <span class="red-text">{{ $errors->first('hsn_id') }}</span>
                                                @endif
                                            </div> -->

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
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
                                            
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="rack_id">Rack Number (optional)</label>
                                                <select class="form-control" name="rack_id" id="rack_id">
                                                    <option value="">Select Rack Number</option>
                                                    @foreach ($dataRackMaster as $rackMaster)
                                                        <option value="{{ $rackMaster['id'] }}" {{ old('rack_id') == $rackMaster['id'] ? 'selected' : '' }}>{{ $rackMaster['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('rack_id'))
                                                <span class="red-text">{{ $errors->first('rack_id') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="basic_rate">Basic Rate  <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="basic_rate" name="basic_rate"  value="{{old('basic_rate') }}" placeholder="Enter basic rate">
                                                @if ($errors->has('basic_rate'))
                                                <span class="red-text">{{ $errors->first('basic_rate') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                <label for="opening_stock">Opening Stock <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="opening_stock" name="opening_stock" value="{{old('opening_stock') }}" placeholder="Enter opening stock">
                                                @if ($errors->has('opening_stock'))
                                                <span class="red-text">{{ $errors->first('opening_stock') }}</span>
                                                @endif
                                            </div>
                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 marg-top">
                                                                <div class="form-group">
                                                                    <label for="image">Image </label>&nbsp<span
                                                                        class="red-text">*</span><br>
                                                                    <input type="file" name="image" id="image"
                                                                        accept="image/*" value="{{ old('image') }}"
                                                                        class="form-control mb-2">
                                                                    @if ($errors->has('image'))
                                                                        <span class="red-text"><?php echo $errors->first('image', ':message'); ?></span>
                                                                    @endif
                                                                </div>
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



    // ✅ Custom dropdown (HSN/SAC No) functionality
$(document).ready(function () {
    const dropdownInput = $(".dropdown-input");
    const dropdownOptions = $(".dropdown-options");
    const searchBox = $(".search-box");

    // Toggle dropdown visibility on input click
    dropdownInput.on("click", function () {
        const options = $(this).siblings(".dropdown-options");
        $(".dropdown-options").not(options).hide(); // close others
        options.toggle();
        searchBox.val("").trigger("keyup"); // reset search when opening
    });

    // Select an option
    $(document).on("click", ".option", function () {
        const id = $(this).data("id");
        const name = $(this).text();
        const dropdown = $(this).closest(".custom-dropdown");

        dropdown.find(".part_no").val(id); // hidden input value
        dropdown.find(".dropdown-input").val(name); // show text
        dropdown.find(".dropdown-options").hide(); // close dropdown
    });

    // Filter options by search
    $(document).on("keyup", ".search-box", function () {
        const search = $(this).val().toLowerCase();
        const optionsList = $(this).siblings(".options-list").find(".option");

        optionsList.each(function () {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(search));
        });
    });

    // Close dropdown when clicking outside
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".custom-dropdown").length) {
            $(".dropdown-options").hide();
        }
    });
});

</script>
@endsection
