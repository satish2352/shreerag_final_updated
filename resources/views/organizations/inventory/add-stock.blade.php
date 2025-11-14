@extends('admin.layouts.master')
@section('content')
<style>
    .form-control {
        border: 2px solid #ced4da;
        border-radius: 4px;
        background-color: #fff !important;
    }
    .error {
        color: red !important;
    }
    .red-text {
        color: red !important;
    }
    .custom-dropdown {
        position: relative;
        width: 100%;
    }
    .dropdown-height {
        height: 280px !important;
    }
    .custom-dropdown .dropdown-options {
        position: absolute;
        width: 100%;
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
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Add Stock Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="all-form-element-inner">
                                <form action="{{ route('store-product-stock') }}" method="POST" enctype="multipart/form-data" id="regForm">
                                    @csrf
                                    <div class="form-group-inner">
                                        <div class="row">
                                            <!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="part_item_id">Item Name <span class="text-danger">*</span></label>
                                                <select class="form-control" name="part_item_id" id="part_item_id">
                                                    <option value="">Select Item Name</option>
                                                    @foreach ($dataOutputPartItem as $data)
                                                        <option value="{{ $data['id'] }}">{{ $data['description'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('part_item_id'))
                                                <span class="red-text">{{ $errors->first('part_item_id') }}</span>
                                                @endif
                                            </div> -->

                                                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="part_item_id">Item Name <span class="text-danger">*</span></label>
                                                <div class="custom-dropdown">
                                                    <input type="hidden" name="part_item_id" class="part_no" value="{{ old('part_item_id') }}">
                                                    @php
                                                        $selectedItem = $dataOutputPartItem->firstWhere('id', old('part_item_id'));
                                                    @endphp
                                                    <input type="text" class="dropdown-input form-control part-no" placeholder="Select Item..." readonly value="{{ $selectedItem->description ?? '' }}">
                                                    <div class="dropdown-options dropdown-height" style="display: none;">
                                                        <input type="text" class="search-box form-control" placeholder="Search...">
                                                        <div class="options-list">
                                                            @foreach ($dataOutputPartItem as $data)
                                                                <div class="option" data-id="{{ $data->id }}">{{ $data->description }}</div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($errors->has('part_item_id'))
                                                    <span class="red-text">{{ $errors->first('part_item_id') }}</span>
                                                @endif
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="quantity">Quantity:</label>
                                                <input type="text" class="form-control" id="quantity" name="quantity" placeholder="Enter stock quantity">
                                                @if ($errors->has('quantity'))
                                                <span class="red-text">{{ $errors->first('quantity') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="login-btn-inner">
                                            <div class="row">
                                                <div class="col-lg-5"></div>
                                                <div class="col-lg-7">
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="{{ route('list-inventory-material') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
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
<!-- <script>
    $(document).ready(function() {
        // Custom method to check for spaces only
        $.validator.addMethod("spcenotallow", function(value, element) {
            return this.optional(element) || value.trim().length > 0;
        }, "Field cannot contain only spaces.");

        // Validate the form
        $("#regForm").validate({
            rules: {
                part_item_id: {
                    required: true,
                    spcenotallow: true
                },
                quantity: {
                    required: true,
                    spcenotallow: true,
                    number: true // Ensures only valid numeric values
                }
            },
            messages: {
                part_item_id: {
                    required: "Please select the part number.",
                    spcenotallow: "Part number cannot contain only spaces."
                },
                quantity: {
                    required: "Please enter a quantity.",
                    spcenotallow: "Quantity cannot contain only spaces.",
                    number: "Please enter a valid number for quantity."
                }
            }
        });
    });
</script> -->
<script>
$(document).ready(function () {
    // ✅ Validation
    $.validator.addMethod("spcenotallow", function(value, element) {
        return this.optional(element) || value.trim().length > 0;
    }, "Field cannot contain only spaces.");

    $("#regForm").validate({
        rules: {
            part_item_id: { required: true },
            quantity: { required: true, number: true }
        },
        messages: {
            part_item_id: { required: "Please select the item name." },
            quantity: {
                required: "Please enter a quantity.",
                number: "Please enter a valid number."
            }
        }
    });

    // ✅ Custom dropdown logic
    const dropdownInput = $(".dropdown-input");
    const dropdownOptions = $(".dropdown-options");
    const searchBox = $(".search-box");

    dropdownInput.on("click", function () {
        const options = $(this).siblings(".dropdown-options");
        $(".dropdown-options").not(options).hide();
        options.toggle();
        searchBox.val("").trigger("keyup");
    });

    $(document).on("click", ".option", function () {
        const id = $(this).data("id");
        const name = $(this).text();
        const dropdown = $(this).closest(".custom-dropdown");

        dropdown.find(".part_no").val(id);
        dropdown.find(".dropdown-input").val(name);
        dropdown.find(".dropdown-options").hide();
    });

    $(document).on("keyup", ".search-box", function () {
        const search = $(this).val().toLowerCase();
        const optionsList = $(this).siblings(".options-list").find(".option");

        optionsList.each(function () {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(search));
        });
    });

    $(document).on("click", function (e) {
        if (!$(e.target).closest(".custom-dropdown").length) {
            $(".dropdown-options").hide();
        }
    });
});
</script>
@endsection
