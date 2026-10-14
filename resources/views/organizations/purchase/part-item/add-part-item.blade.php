@extends('admin.layouts.master')
@section('content')
<style>
    .custom-dropdown {
        position: relative;
        width: 100%;
    }
    .dropdown-height {
        height: 280px !important;
    }
    .custom-dropdown .dropdown-options {
        position: absolute;
        width: 600px !important;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ccc;
        z-index: 999;
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
    .custom-dropdown.error-border .dropdown-input {
        border: 2px solid red !important;
    }
</style>
<div class="data-table-area mg-tb-15">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10 col-md-12">
<div class="emp-form-card">
  <div class="emp-form-header">
    <h4><i class="fa fa-plus mr-2"></i> Add Part Item</h4>
    <a href="{{ route('list-part-item') }}" class="emp-form-close" title="Close">&times;</a>
  </div>
  <div class="emp-form-body">
    <form action="{{ route('store-part-item') }}" method="POST" enctype="multipart/form-data" id="regForm">
        @csrf
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" id="part_number" name="part_number" placeholder=" " value="{{ old('part_number') }}">
                    <label for="part_number">Part No <span class="red-text">*</span></label>
                    @if($errors->has('part_number'))<span class="fl-error">{{ $errors->first('part_number') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" id="description" name="description" placeholder=" " value="{{ old('description') }}">
                    <label for="description">Description <span class="red-text">*</span></label>
                    @if($errors->has('description'))<span class="fl-error">{{ $errors->first('description') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" id="extra_description" name="extra_description" placeholder=" " value="{{ old('extra_description') }}">
                    <label for="extra_description">Extra Description (optional)</label>
                    @if($errors->has('extra_description'))<span class="fl-error">{{ $errors->first('extra_description') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <select class="form-control {{ old('unit_id') ? 'has-value' : '' }}" name="unit_id" id="unit_id" onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="">Select Unit</option>
                        @foreach ($dataOutputUnitMaster as $data)
                            <option value="{{ $data['id'] }}" {{ old('unit_id') == $data['id'] ? 'selected' : '' }}>{{ $data['name'] }}</option>
                        @endforeach
                    </select>
                    <label for="unit_id">Unit <span class="red-text">*</span></label>
                    @if($errors->has('unit_id'))<span class="fl-error">{{ $errors->first('unit_id') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <div class="custom-dropdown">
                        <input type="hidden" name="hsn_id" class="part_no" value="{{ old('hsn_id') }}">
                        @php
                            $selectedPartItem = $dataOutputHSNMaster->firstWhere('id', old('hsn_id'));
                        @endphp
                        <input type="text" class="dropdown-input form-control part-no" placeholder=" " readonly value="{{ $selectedPartItem->name ?? '' }}">
                        @if($errors->has('hsn_id'))<span class="fl-error">{{ $errors->first('hsn_id') }}</span>@endif
                        <div class="dropdown-options dropdown-height" style="display: none;">
                            <input type="text" class="search-box form-control" placeholder="Search...">
                            <div class="options-list">
                                @foreach ($dataOutputHSNMaster as $data)
                                    <div class="option" data-id="{{ $data->id }}">{{ $data->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <label for="hsn_id">HSN/SAC No <span class="red-text">*</span></label>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <select class="form-control {{ old('group_type_id') ? 'has-value' : '' }}" name="group_type_id" id="group_type_id" onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="">Select Group</option>
                        @foreach ($dataOutputGroupMaster as $GroupMaster)
                            <option value="{{ $GroupMaster['id'] }}" {{ old('group_type_id') == $GroupMaster['id'] ? 'selected' : '' }}>{{ $GroupMaster['name'] }}</option>
                        @endforeach
                    </select>
                    <label for="group_type_id">Group Master <span class="red-text">*</span></label>
                    @if($errors->has('group_type_id'))<span class="fl-error">{{ $errors->first('group_type_id') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <select class="form-control {{ old('rack_id') ? 'has-value' : '' }}" name="rack_id" id="rack_id" onchange="this.classList.toggle('has-value', this.value !== '')">
                        <option value="">Select Rack Number</option>
                        @foreach ($dataRackMaster as $rackMaster)
                            <option value="{{ $rackMaster['id'] }}" {{ old('rack_id') == $rackMaster['id'] ? 'selected' : '' }}>{{ $rackMaster['name'] }}</option>
                        @endforeach
                    </select>
                    <label for="rack_id">Rack Number (optional)</label>
                    @if($errors->has('rack_id'))<span class="fl-error">{{ $errors->first('rack_id') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" id="basic_rate" name="basic_rate" placeholder=" " value="{{ old('basic_rate') }}">
                    <label for="basic_rate">Basic Rate <span class="red-text">*</span></label>
                    @if($errors->has('basic_rate'))<span class="fl-error">{{ $errors->first('basic_rate') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <input type="text" class="form-control" id="opening_stock" name="opening_stock" placeholder=" " value="{{ old('opening_stock') }}">
                    <label for="opening_stock">Opening Stock <span class="red-text">*</span></label>
                    @if($errors->has('opening_stock'))<span class="fl-error">{{ $errors->first('opening_stock') }}</span>@endif
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                <div class="fl-group">
                    <label for="image" style="position:static; font-size:13px; margin-bottom:5px; display:block;">Image (optional)</label>
                    <input type="file" name="image" id="image" accept="image/*" value="{{ old('image') }}" class="form-control">
                    @if($errors->has('image'))<span class="fl-error">{{ $errors->first('image') }}</span>@endif
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-right">
                <a href="{{ route('list-part-item') }}" class="btn btn-secondary mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </div>
    </form>
  </div>
</div>
</div></div></div></div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        $.validator.addMethod("spcenotallow", function(value, element) {
            return this.optional(element) || value.trim().length > 0;
        }, "Field cannot contain only spaces.");

        $("#regForm").validate({
               ignore: [],
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
