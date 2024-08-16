@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 10px;
        }
        label.error {
            color: red;
            font-size: 12px;
        }
        .form-display-center{
        display: flex !important;
        justify-content: center !important;
        align-items: center;
        }
        .red-text{
            color: red; 
        }
    </style>
    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Add New Business</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if (Session::get('status') == 'success')
                                    <div class="col-md-12">
                                        <div class="alert alert-success alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>Success!</strong> {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                @endif

                                @if (Session::get('status') == 'error')
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>Error!</strong> {!! session('msg') !!}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <div class="row d-flex justify-content-center form-display-center">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                            <form action="{{ route('store-business') }}" method="POST" id="addEmployeeForm"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group-inner">
                                                <div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="customer_po_number">Customer PO Number :  <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="customer_po_number" value="{{old('customer_po_number') }}"
                                                            name="customer_po_number" placeholder="Enter Customer PO Number">
                                                            @if ($errors->has('customer_po_number'))
                                                            <span class="red-text"><?php echo $errors->first('customer_po_number', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="title">Customer Name :  <span class="text-danger">*</span></label> 
                                                        <input type="text" class="form-control" id="title" value="{{ old('title') }}"
                                                            name="title" placeholder="Enter Customer Name">
                                                            @if ($errors->has('title'))
                                                            <span class="red-text"><?php echo $errors->first('title', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover table-white repeater" id="purchase_order_table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th class="col-sm-3">Product Name</th>
                                                                            <th class="col-md-3">Description</th>
                                                                            <th class="col-md-2">Quantity</th>
                                                                            {{-- <th class="col-md-2">Unit</th> --}}
                                                                            <th class="col-md-2">Rate</th>
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
                                                                                <input class="form-control product_name" name="addmore[0][product_name]" type="text" style="min-width:150px">
                                                                            </td>
                                        
                                                                            <td>
                                                                                <input class="form-control description" name="addmore[0][description]" type="text" style="min-width:150px">
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control quantity" name="addmore[0][quantity]" type="text">
                                                                            </td>
                                                                            {{-- <td>
                                                                                <input class="form-control unit" name="addmore[0][unit]"  type="text">
                                                                            </td> --}}
                                                                            <td>
                                                                                <input class="form-control rate" name="addmore[0][rate]" type="text">
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
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="po_validity">PO Validity :  <span class="text-danger">*</span></label> 
                                                        <input type="date" class="form-control" id="po_validity" value="{{ old('po_validity') }}"
                                                            name="po_validity" placeholder="Enter PO Validity">
                                                            @if ($errors->has('po_validity'))
                                                            <span class="red-text"><?php echo $errors->first('po_validity', ':message'); ?></span>
                                                        @endif
                                                    </div>
                                                   
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="customer_payment_terms">Payment Terms :</label> (optional) 
                                                        <input type="text" class="form-control" id="customer_payment_terms" value="{{ old('customer_payment_terms') }}"
                                                            name="customer_payment_terms" placeholder="Enter Payment Terms">
                                                            
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="customer_terms_condition">Terms Condition :</label> (optional) 
                                                        <textarea class="form-control" rows="3" type="text" class="form-control" id="customer_terms_condition" name="customer_terms_condition"
                                                        placeholder="Enter Terms and Condition">{{ old('customer_terms_condition') }}</textarea>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <div class="sparkline12-graph">
                                                            <div id="pwd-container1">
                                                                <div class="form-group">
                                                                    <label for="remarks">Remark <span class="text-danger">*</span></label> 
                                                                    <textarea class="form-control" rows="3" type="text" class="form-control" id="remarks" name="remarks"
                                                                        placeholder="Enter Remark">{{ old('remarks') }}</textarea>
                                                                       
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="pwstrength_viewport_progress"></span></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-12" style="display: flex; justify-content: center;">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-business') }}" class="btn btn-white"
                                                                    style="margin-bottom:50px">Cancel</a>
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
            // Initialize jQuery Validation
            var validator = $("#addEmployeeForm").validate({
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
                    'addmore[0][product_name]': {
                        required: true,
                        maxlength: 100, // Maximum length of 100 characters
                        // alphanumeric: true // Alphanumeric validation
                    },
                    'addmore[0][description]': {
                        required: true,
                        maxlength: 255 // Maximum length of 255 characters
                    },
                    'addmore[0][quantity]': {
                        required: true,
                        digits: true, // Digits only
                        min: 1 // Minimum value 1
                    },
                    'addmore[0][rate]': {
                        required: true,
                        number: true, // Number validation
                        min: 0.01 // Minimum value 0.01
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
                    'addmore[0][product_name]': {
                        required: "Please enter the Product Name.",
                        maxlength: "Product Name must be at most 100 characters long.",
                        // alphanumeric: "Product Name can only contain letters and numbers."
                    },
                    'addmore[0][description]': {
                        required: "Please enter the Description.",
                        maxlength: "Description must be at most 255 characters long."
                    },
                    'addmore[0][quantity]': {
                        required: "Please enter the Quantity.",
                        digits: "Please enter only digits for Quantity.",
                        min: "Quantity must be at least 1."
                    },
                    'addmore[0][rate]': {
                        required: "Please enter the Rate.",
                        number: "Please enter a valid number for Rate.",
                        min: "Rate must be a positive number."
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
                row.find('.product_name').rules("add", {
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
                row.find('.rate').rules("add", {
                    required: true,
                    number: true, // Number validation
                    min: 0.01, // Minimum value 0.01
                    messages: {
                        required: "Please enter the Rate.",
                        number: "Please enter a valid number for Rate.",
                        min: "Rate must be a positive number."
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
                            <input class="form-control product_name" name="addmore[${i}][product_name]" type="text" style="min-width:150px">
                        </td>
                        <td>
                            <input class="form-control description" name="addmore[${i}][description]" type="text" style="min-width:150px">
                        </td>
                        <td>
                            <input class="form-control quantity" name="addmore[${i}][quantity]" type="text">
                        </td>
                        <td>
                            <input class="form-control rate" name="addmore[${i}][rate]" type="text">
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
    
      {{-- <script>
        $(document).ready(function() {
            // Initialize jQuery Validation
            var validator = $("#purchase_order_table").validate({
                ignore: [], // Validate hidden inputs as well
                rules: {
                    'addmore[0][product_name]': {
                        required: true,
                    },
                    // 'addmore[0][description]': {
                    //     required: true,
                    // },
                    'addmore[0][quantity]': {
                        required: true,
                        digits: true,
                    },
                    'addmore[0][rate]': {
                        required: true,
                        number: true,
                    },
                    'addmore[0][amount]': {
                        required: true,
                    },
                },
                messages: {
                    'addmore[0][product_name]': {
                        required: "Please Enter the Part Number",
                    },
                    // 'addmore[0][description]': {
                    //     required: "Please Enter the Description",
                    // },
                    'addmore[0][quantity]': {
                        required: "Please Enter the Quantity",
                        digits: "Please enter only digits for Quantity",
                    },
                    'addmore[0][rate]': {
                        required: "Please Enter the Rate",
                        number: "Please enter a valid number for Rate",
                    },
                    'addmore[0][amount]': {
                        required: "Please Enter the Amount",
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass("product_name") || 
                        element.hasClass("quantity") || 
                        element.hasClass("rate") || 
                        element.hasClass("amount")) {
                        error.insertAfter(element.closest('td'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });
    
            // Function to initialize validation for dynamically added fields
            function initializeValidation(context) {
                $(context).find('.product_name').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please Enter the Part Number",
                        }
                    });
                });
                $(context).find('.quantity').each(function() {
                    $(this).rules("add", {
                        required: true,
                        digits: true,
                        messages: {
                            required: "Please Enter the Quantity",
                            digits: "Please enter only numbers for Quantity",
                        }
                    });
                });
                // $(context).find('.unit').each(function() {
                //     $(this).rules("add", {
                //         required: true,
                //         messages: {
                //             required: "Please Enter the Unit",
                //         }
                //     });
                // });
                $(context).find('.rate').each(function() {
                    $(this).rules("add", {
                        required: true,
                        number: true,
                        messages: {
                            required: "Please Enter the Rate",
                            number: "Please enter a valid number for Rate",
                        }
                    });
                });
                $(context).find('.amount').each(function() {
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: "Please Enter the Amount",
                        }
                    });
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
                           <input class="form-control product_name" name="addmore[${i}][product_name]" type="text" style="min-width:150px">
                        </td>
                       
                        <td>
                            <input class="form-control quantity" name="addmore[${i}][quantity]" type="text">
                        </td>
                        <td>
                            <input class="form-control rate" name="addmore[${i}][rate]" type="text">
                        </td>
                           <td>
                        <input class="form-control amount" name="addmore[${i}][amount]" readonly style="width:120px" type="text">
                       </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete" data-repeater-delete>
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $("#purchase_order_table tbody").append(newRow);
    
                // Reinitialize validation for dynamically added fields
                validator.resetForm(); // Reset validation state
                initializeValidation($("#purchase_order_table")); // Initialize for all rows
            });
    
            // Remove a row when the "Remove" button is clicked
            $(document).on("click", ".remove-row", function() {
                var i_count = $('#i_id').val();
                var i = parseInt(i_count) - 1;
                $('#i_id').val(i);
    
                $(this).closest("tr").remove();
    
                // Reset validation state after removing row
                validator.resetForm();
            });
    
            // Initialize validation for the first row on page load
            initializeValidation($("#purchase_order_table"));
    
            // Custom validation method for minimum date
            $.validator.addMethod("minDate", function(value, element) {
                var today = new Date();
                var inputDate = new Date(value);
                return inputDate >= today;
            }, "The date must be today or later.");
    
            // Initialize date pickers with min date set to today
            // function setMinDateForDueDates() {
            //     var today = new Date().toISOString().split('T')[0];
            //     $('.due-date').attr('min', today);
            // }
            // setMinDateForDueDates();
    
            // $(document).on('focus', '.due-date', function() {
            //     setMinDateForDueDates();
            // });
    
            $(document).on('keyup', '.quantity, .rate', function(e) {
                var currentRow = $(this).closest("tr");
                var current_row_quantity = currentRow.find('.quantity').val();
                var current_row_rate = currentRow.find('.rate').val();
                var new_total_price = current_row_quantity * current_row_rate;
                currentRow.find('.amount').val(new_total_price);
            });
        });
    </script> --}}
   
   
   
   {{-- <script>
        jQuery.noConflict();
        jQuery(document).ready(function($) {
            // Function to set minimum date for the po_validity field
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
    
            $("#addEmployeeForm").validate({
                rules: {
                   
                    title: {
                        required: true,
                    },
                   
                    customer_po_number: {
                        required: true,
                        minlength: 10,
                        maxlength: 16
                    },
                  
                    po_validity: {
                        required: true,
                    },
                    remarks: {
                        required: true,
                    },
                    // hsn_number: {
                    //     required: true,
                    //     number: true,
                    // },
                    'input[name^="addmore["][name$="[product_name]"]': {
                            required: true,
                        },
                        // 'addmore[][description]': {
                        //     required: true,
                        // },
                        // 'addmore[][due_date]': {
                        //     required: true,
                        // },
                        'addmore[][quantity]': {
                            required: true,
                        },
                        'addmore[][rate]': {
                            required: true,
                        },
                        'addmore[][amount]': {
                            required: true,
                        },
                   
                },
                messages: {
                   
                    title: {
                        required: "Please enter Customer Name.",
                    },
                    
                    customer_po_number: {
                        required: "Please enter po number.",
                        minlength: "PO number must be at least 10 characters long.",
                        maxlength: "PO number must be at most 16 characters long."
                    }, 
                    po_validity: {
                        required: "Please enter po validity.",
                    },
                    // hsn_number: {
                    //     required: "Please enter hsn number.",
                    //     number: "Please enter a valid number.",
                    // },
                    'input[name^="addmore["][name$="[product_name]"]': {
                            required: "Please Enter the Part Number",
                        },
                        // 'addmore[][description]': {
                        //     required: "Please Enter the Description",
                        // },
                        // 'addmore[][due_date]': {
                        //     required: "Please Enter the Due Date",
                        // },
                        'addmore[][quantity]': {
                            required: "Please Enter the Quantity",
                        },
                        
                        'addmore[][rate]': {
                            required: "Please Enter the Rate", 
                        },
                        'addmore[][amount]': {
                            required: "Please Enter the Amount",
                        },
                        remarks: {
                        required: "Please enter remarks",
                    },
                  
                },
                submitHandler: function(form) {
                    // Use SweetAlert to show a confirmation dialog
                    Swal.fire({
                        icon: 'question',
                        title: 'Are you sure?',
                        text: 'You want to submit this business to Design Department ?',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            // If user clicks "Yes", submit the form
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
     --}}
@endsection
