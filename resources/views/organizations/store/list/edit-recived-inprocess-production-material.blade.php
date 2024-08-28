@extends('admin.layouts.master')
@section('content')
<style>
    label {
        margin-top: 20px;
    }
    label.error {
        color: red; /* Change 'red' to your desired text color */
        font-size: 12px; /* Adjust font size if needed */
        /* Add any other styling as per your design */
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Edit Product Data</h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                      

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            @if (Session::get('status') == 'success')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-success " id="success-alert">
                                        <button type="button" data-bs-dismiss="alert"></button>
                                        <strong style="color: green;">Success!</strong> {{ Session::get('msg') }}
                                    </div>
                                </div>
                            @endif

                            @if (Session::get('status') == 'error')
                                <div class="col-12 grid-margin">
                                    <div class="alert alert-custom-danger " id="error-alert">
                                        <button type="button" data-bs-dismiss="alert"></button>
                                        <strong style="color: red;">Error!</strong> {!! session('msg') !!}
                                    </div>
                                </div>
                            @endif
                           
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                    <?php
// dd($editData);
// die();
                                    ?>
                                    <form action="{{ route('update-production', $editData->business_details_id) }}" method="POST" id="addProductForm" enctype="multipart/form-data">
                                        @csrf
                                       {{-- <input type="hidden" name="id" id="id" class="form-control"
                                    value="{{ $editData->id }}" placeholder=""> --}}
                                    <input type="hidden" name="business_details_id" id="business_details_id" class="form-control" value="{{ $editData->business_details_id }}">
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="prdouct_name">Name:</label>
                                                    <input type="text" class="form-control" id="name" name="prdouct_name" value="{{ $editData->prdouct_name }}" placeholder="Enter Employee name" readonly>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label for="description">Description:</label>
                                                    <input type="text" class="form-control" id="description" name="description" value="{{ $editData->description }}" placeholder="Enter email" readonly>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-white repeater" id="purchase_order_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th class="col-sm-3">Product Name</th>
                                                                    <th class="col-md-2">Quantity</th>
                                                                    {{-- <th class="col-md-2">Unit</th> --}}
                                                                    <th class="col-md-2">Unit</th>
                                                                    <th>
                                                                        <button type="button" class="btn btn-sm btn-success font-18 mr-1" id="add_more_btn"
                                                                            title="Add" data-repeater-create>
                                                                            <i class="fa fa-plus"></i>
                                                                        </button>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <input type="text" name="id" class="form-control" style="min-width:50px" readonly value="1">
                                                                        <input type="hidden" id="i_id" class="form-control" style="min-width:50px" readonly value="0">
                                                                    </td>
                                                                    <td>
                                                                        <select class="form-control part-no mb-2" name="addmore[0][part_no_id]" id="">
                                                                        <option value="" default>Select Part Item</option>
                                                                        @foreach ($dataOutputPartItem as $data)
                                                                                <option value="{{ $data['id'] }}" >
                                                                                    {{ $data['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                
                                                                {{-- <input class="form-control part-no" name="addmore[0][part_no]" type="text" style="min-width:150px"> --}}
                                                            </td>
                                                                    {{-- <td>
                                                                        <input class="form-control part-no" name="addmore[0][part-no]" type="text" style="min-width:150px">
                                                                    </td> --}}
                                                                    <td>
                                                                        <input class="form-control quantity" name="addmore[0][quantity]" type="text">
                                                                    </td>
                                                                    {{-- <td>
                                                                        <input class="form-control unit" name="addmore[0][unit]"  type="text">
                                                                    </td> --}}
                                                                    <td>
                                                                        <input class="form-control unit" name="addmore[0][unit]" type="text">
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete"
                                                                            data-repeater-delete>
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>  
                                            
                                            <div class="login-btn-inner">
                                                <div class="row">
                                                    <div class="col-lg-5"></div>
                                                    <div class="login-horizental cancel-wp pull-left">
                                                        <a href="" class="btn btn-white"
                                                            style="margin-bottom:50px">Cancel</a>
                                                        <button class="btn btn-sm btn-primary login-submit-cs"
                                                            type="submit" style="margin-bottom:50px">Save Data</button>
                                                    </div>
                                                    {{-- <div class="col-lg-7">
                                                        <div class="login-horizental cancel-wp pull-left">
                                                            <a href="{{ route('list-purchases') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                                                            <button class="btn btn-sm btn-primary login-submit-cs" type="submit" style="margin-bottom:50px">Update Data</button>
                                                        </div>
                                                    </div> --}}
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
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
<script>
    $(document).ready(function() {

        function setMinDate() {
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var year = today.getFullYear();
            var todayDate = year + '-' + month + '-' + day;

            $('#po_validity').attr('min', todayDate);
        }
         // Call the function to set the minimum date
    setMinDate();

        // Initialize jQuery Validation
        var validator = $("#addProductForm").validate({
            ignore: [], // Validate hidden inputs as well
            rules: {
                title: {
                    required: true,
                    maxlength: 50, // Maximum length of 50 characters
                    // alphanumeric: true // Alphanumeric validation
                },
                customer_po_number: {
                    required: true,
                    minlength: 10,
                    maxlength: 16,
                    digits: true // Digits only
                },
                po_validity: {
                    required: true,
                    date: true // Date validation
                },
                remarks: {
                    required: true,
                    maxlength: 255 // Maximum length of 255 characters
                },
                'addmore[0][part-no]': {
                    required: true,
                    maxlength: 100, // Maximum length of 100 characters
                    // alphanumeric: true // Alphanumeric validation
                },
                'addmore[0][quantity]': {
                    required: true,
                    digits: true, // Digits only
                    min: 1 // Minimum value 1
                },
                'addmore[0][unit]': {
                    required: true,
                    // number: true, // Number validation
                    // min: 0.01 // Minimum value 0.01
                }
            },
            messages: {
                title: {
                    required: "Please enter Customer Name.",
                    maxlength: "Customer Name must be at most 50 characters long.",
                    // alphanumeric: "Customer Name can only contain letters and numbers."
                },
                customer_po_number: {
                    required: "Please enter PO number.",
                    minlength: "PO number must be at least 10 characters long.",
                    maxlength: "PO number must be at most 16 characters long.",
                    digits: "PO number can only contain digits."
                },
                po_validity: {
                    required: "Please enter PO validity.",
                    date: "Please enter a valid date."
                },
                remarks: {
                    required: "Please enter remarks.",
                    maxlength: "Remarks must be at most 255 characters long."
                },
                'addmore[0][part-no]': {
                    required: "Please enter the Product Name.",
                    maxlength: "Product Name must be at most 100 characters long.",
                    // alphanumeric: "Product Name can only contain letters and numbers."
                },
                'addmore[0][quantity]': {
                    required: "Please enter the Quantity.",
                    digits: "Please enter only digits for Quantity.",
                    min: "Quantity must be at least 1."
                },
                'addmore[0][unit]': {
                    required: "Please enter the unit.",
                    // number: "Please enter a valid number for unit.",
                    // min: "unit must be a positive number."
                }
            },
            errorPlacement: function(error, element) {
                error.addClass('text-danger'); // Add Bootstrap text-danger class for styling
                if (element.closest('.form-group').length) {
                    element.closest('.form-group').append(error);
                } else if (element.closest('td').length) {
                    element.closest('td').append(error);
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // Attach validation to the default row
        initializeValidation($("#purchase_order_table tbody tr"));

        // Function to attach validation rules to dynamic fields
        function initializeValidation(row) {
            row.find('.part-no').rules("add", {
                required: true,
                maxlength: 100, // Maximum length of 100 characters
                // alphanumeric: true, // Alphanumeric validation
                messages: {
                    required: "Please enter the Product Name.",
                    maxlength: "Product Name must be at most 100 characters long.",
                    // alphanumeric: "Product Name can only contain letters and numbers."
                }
            });
            row.find('.description').rules("add", {
                required: true,
                maxlength: 255, // Maximum length of 255 characters
                messages: {
                    required: "Please enter the Description.",
                    maxlength: "Description must be at most 255 characters long."
                }
            });
            row.find('.quantity').rules("add", {
                required: true,
                digits: true, // Digits only
                min: 1, // Minimum value 1
                messages: {
                    required: "Please enter the Quantity.",
                    digits: "Please enter only digits for Quantity.",
                    min: "Quantity must be at least 1."
                }
            });
            row.find('.unit').rules("add", {
                required: true,
                // number: true, // Number validation
                // min: 0.01, // Minimum value 0.01
                messages: {
                    required: "Please enter the unit.",
                    // number: "Please enter a valid number for unit.",
                    // min: "unit must be a positive number."
                }
            });
        }

        // Add more rows when the "Add More" button is clicked
        $("#add_more_btn").click(function() {
            var i_count = $('#i_id').val();
            var i = parseInt(i_count) + 1;
            $('#i_id').val(i);

            var newRow = `
                <tr>
                    <td>
                        <input type="text" name="id" class="form-control" style="min-width:50px" readonly value="${i}">
                    </td>
                   <td>
                         <select class="form-control part-no mb-2" name="addmore[${i}][part_no_id]" id="">
                                                <option value="" default>Select Part Item</option>
                                                @foreach ($dataOutputPartItem as $data)
                                                        <option value="{{ $data['id'] }}" >
                                                            {{ $data['name'] }}</option>
                                                @endforeach
                                            </select>
                       
                    </td>
                    <td>
                        <input class="form-control quantity" name="addmore[${i}][quantity]" type="text">
                    </td>
                    <td>
                        <input class="form-control unit" name="addmore[${i}][unit]" type="text">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete" data-repeater-delete>
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            var row = $(newRow).appendTo("#purchase_order_table tbody");

            // Attach validation to the new row
            initializeValidation(row);
            validator.resetForm(); // Reset validation state after adding a new row
        });

        // Remove a row when the "Remove" button is clicked
        $(document).on("click", ".remove-row", function() {
            $(this).closest("tr").remove();
            validator.resetForm(); // Reset validation state after removing a row
        });
    });
</script>


@endsection
