@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="emp-form-card">
                        <div class="emp-form-header">
                            <h4><i class="fa fa-boxes mr-2"></i> Add Stock</h4>
                            <a href="{{ route('list-inventory-material') }}" class="emp-form-close">&times;</a>
                        </div>
                        <div class="emp-form-body">

                            <form action="{{ route('store-product-stock') }}" method="POST"
                                enctype="multipart/form-data" id="regForm">
                                @csrf
                                <div class="row">

                                    <!-- custom dropdown for Item Name -->
                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <div class="custom-dropdown" style="position:relative;width:100%;">
                                                <input type="hidden" name="part_item_id" class="part_no"
                                                    value="{{ old('part_item_id') }}">
                                                @php
                                                    $selectedItem = $dataOutputPartItem->firstWhere(
                                                        'id',
                                                        old('part_item_id'),
                                                    );
                                                @endphp
                                                <input type="text" class="dropdown-input form-control part-no"
                                                    placeholder=" " readonly
                                                    value="{{ $selectedItem->description ?? '' }}">
                                                <div class="dropdown-options" style="position:absolute;width:100%;top:100%;left:0;right:0;background:white;border:1px solid #ccc;z-index:999;overflow-y:auto;height:280px;display:none;">
                                                    <input type="text" class="search-box form-control"
                                                        placeholder="Search..." style="border-bottom:1px solid #ccc;margin-bottom:5px;">
                                                    <div class="options-list">
                                                        @foreach ($dataOutputPartItem as $data)
                                                            <div class="option" data-id="{{ $data->id }}" style="padding:6px 10px;cursor:pointer;">
                                                                {{ $data->description }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <label for="part_item_id">Item Name <span class="text-danger">*</span></label>
                                            @if ($errors->has('part_item_id'))
                                                <span class="fl-error">{{ $errors->first('part_item_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                                        <div class="fl-group">
                                            <input type="text" class="form-control" id="quantity"
                                                name="quantity" placeholder=" ">
                                            <label for="quantity">Quantity</label>
                                            @if ($errors->has('quantity'))
                                                <span class="fl-error">{{ $errors->first('quantity') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="login-btn-inner">
                                    <div class="row">
                                        <div class="col-lg-5"></div>
                                        <div class="col-lg-7">
                                            <div class="login-horizental cancel-wp pull-left">
                                                <a href="{{ route('list-inventory-material') }}"
                                                    class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                    type="submit" style="margin-bottom:50px">Save Data</button>
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
        $(document).ready(function() {
            // ✅ Validation
            $.validator.addMethod("spcenotallow", function(value, element) {
                return this.optional(element) || value.trim().length > 0;
            }, "Field cannot contain only spaces.");

            $("#regForm").validate({
                ignore: [],

                rules: {
                    part_item_id: {
                        required: true
                    },
                    quantity: {
                        required: true,
                        number: true
                    }
                },

                messages: {
                    part_item_id: {
                        required: "Please select the item name."
                    },
                    quantity: {
                        required: "Please enter a quantity.",
                        number: "Please enter a valid number."
                    }
                },

                errorPlacement: function(error, element) {
                    if (element.attr("name") === "part_item_id") {
                        if (element.closest(".custom-dropdown").next("label.error").length === 0) {
                            error.insertAfter(element.closest(".custom-dropdown"));
                        }
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            // ✅ Custom dropdown logic
            const dropdownInput = $(".dropdown-input");
            const dropdownOptions = $(".dropdown-options");
            const searchBox = $(".search-box");

            dropdownInput.on("click", function() {
                const options = $(this).siblings(".dropdown-options");
                $(".dropdown-options").not(options).hide();
                options.toggle();
                searchBox.val("").trigger("keyup");
            });

            $(document).on("click", ".option", function() {
                const id = $(this).data("id");
                const name = $(this).text();
                const dropdown = $(this).closest(".custom-dropdown");

                dropdown.find(".part_no").val(id);
                dropdown.find(".dropdown-input").val(name);
                dropdown.find(".dropdown-options").hide();
            });

            $(document).on("keyup", ".search-box", function() {
                const search = $(this).val().toLowerCase();
                const optionsList = $(this).siblings(".options-list").find(".option");

                optionsList.each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(search));
                });
            });

            $(document).on("click", function(e) {
                if (!$(e.target).closest(".custom-dropdown").length) {
                    $(".dropdown-options").hide();
                }
            });
        });
    </script>
@endsection
