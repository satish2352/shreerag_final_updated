@extends('admin.layouts.master')
@section('content')
<style>
.form-control {
  border: 2px solid #ced4da;
  border-radius: 4px;
}
.error{
  color:red;
}
.form-control {
    color: black;
}
</style>
<div class="data-table-area mg-tb-15">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline13-list">
          <div class="sparkline13-hd">
            <div class="main-sparkline13-hd">
              <h1>Purchase Order <span class="table-project-n">Data</span> Table</h1>
            </div><br>
            <form action="{{route('store-purchase-order')}} " id="forms" method="post" enctype="multipart/form-data">
              @csrf
              
              <input class="form-control" type="hidden" name="requistition_id" id="requistition_id" value="{{$requistition_id}}">
              <input class="form-control" type="hidden" name="vendor_id" id="vendor_id">
              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                      <label for="vendor_id">Vendor Company Name <span class="text-danger">*</span></label>
                      {{-- <select class="form-control"  name="vendor_id" id="vendor_id">
                          <option>Select</option> --}}

                          <select class="form-control mb-2" name="vendor_id" id="vendor_id">
                          <option value="" default>Vendor Company Name</option>

                          @foreach ($dataOutputVendor as $data)
                                  <option value="{{ $data['id'] }}" >
                                      {{ $data['vendor_company_name'] }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div class="form-group">
                    <label>Tax Type<span class="text-danger">*</span></label>
                    <select name="tax_type" class="form-control" title="select tax" id="tax_type">
                      <option value="">Select Tax Type</option>
                      <option value="CGST">C-GST</option>
                      <option value="SGST">S-GST</option>
                      <option value="IGST">I-GST</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="form-group">
                        <label for="tax_id">Tax<span class="text-danger">*</span></label>  
                            <select class="form-control mb-2" name="tax_id" id="tax_id">
                            <option value="" default>Tax</option>
                            @foreach ($dataOutputTax as $data)
                                    <option value="{{ $data['id'] }}" >
                                        {{ $data['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
              </div>
              <div class="row">

                <div class="col-lg-6 col-md-6 col-sm-6">
                  <div class="form-group">
                    <label>Purchase Order Date <span class="text-danger">*</span></label>
                    <div class="cal-icon">
                    <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                name="invoice_date" id="invoice_date"
                                                value="">
                      <!-- <input class="form-control datetimepicker" type="text" name="invoice_date"> -->
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                  <div class="form-group">
                    <label>Payment Terms <span class="text-danger">*</span></label>
                    <select name="payment_terms" class="form-control" title="select tax" id="">
                      <option value="">Select Tax</option>
                      <option value="30">30 Days</option>
                      <option value="60">60 Days</option>
                      <option value="90">90 Days</option>

                    </select>
                  </div>
                </div>
              </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-white repeater" id="purchase_order_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="col-sm-2">Part No.</th>
                                    <th class="col-md-2">Description</th>
                                    <th class="col-md-2">Due Date</th>
                                    <th class="col-md-2">Quantity</th>
                                    <th class="col-md-2">Unit</th>
                                    <th class="col-md-2">Rate</th>
                                    <th>Amount</th>
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
                                                <option value="" default>Tax</option>
                                                @foreach ($dataOutputPartItem as $data)
                                                        <option value="{{ $data['id'] }}" >
                                                            {{ $data['name'] }}</option>
                                                @endforeach
                                            </select>
                                        
                                        {{-- <input class="form-control part-no" name="addmore[0][part_no]" type="text" style="min-width:150px"> --}}
                                    </td>

                                    <td>
                                        <input class="form-control description" name="addmore[0][description]" type="text" style="min-width:150px">
                                    </td>
                                    <td>
                                        <input class="form-control due-date" placeholder="YYYY-MM-DD" name="addmore[0][due_date]" type="date"
                                            style="min-width:150px" value="">
                                    </td>
                                    <td>
                                        <input class="form-control quantity" name="addmore[0][quantity]" style="width:100px" type="text">
                                    </td>
                                    <td>
                                        <input class="form-control unit" name="addmore[0][unit]" style="width:80px" type="text">
                                    </td>
                                    <td>
                                        <input class="form-control rate" name="addmore[0][rate]" style="width:80px" type="text">
                                    </td>
                                    <td>
                                        <input class="form-control total_amount" name="addmore[0][amount]" readonly style="width:120px" type="text">
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
            
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Quote Number  (optional)</label>
                    <input class="form-control text-right" type="text" name="quote_no" value="" placeholder="">
                  </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                      <label>Discount (optional)</label>
                      <select class="form-control" name="discount">
                        <option value="0">0 %</option>
                        <option value="1">1 %</option>
                        <option value="2">2 %</option>
                        <option value="3">3 %</option>
                        <option value="4">4 %</option>
                        <option value="5">5 %</option>
                        <option value="6">6 %</option>
                        <option value="7">7 %</option>
                        <option value="8">8 %</option>
                        <option value="9">9 %</option>
                        <option value="10">10 %</option>
                        <option value="11">11 %</option>
                        <option value="12">12 %</option>
                        <option value="13">13 %</option>
                        <option value="14">14 %</option>
                        <option value="15">15 %</option>
                        <option value="16">16 %</option>
                        <option value="17">17 %</option>
                        <option value="18">18 %</option>
                        <option value="19">19 %</option>
                        <option value="20">20 %</option>
                        <option value="21">21 %</option>
                        <option value="22">22 %</option>
                        <option value="23">23 %</option>
                        <option value="24">24 %</option>
                        <option value="25">25 %</option>
                        <option value="26">26 %</option>
                        <option value="27">27 %</option>
                        <option value="28">28 %</option>
                        <option value="29">29 %</option>
                        <option value="30">30 %</option>
                        <option value="31">31 %</option>
                        <option value="32">32 %</option>
                        <option value="33">33 %</option>
                        <option value="34">34 %</option>
                        <option value="35">35 %</option>
                        <option value="36">36 %</option>
                        <option value="37">37 %</option>
                        <option value="38">38 %</option>
                        <option value="39">39 %</option>
                        <option value="40">40 %</option>
                        <option value="41">41 %</option>
                        <option value="42">42 %</option>
                        <option value="43">43 %</option>
                        <option value="44">44 %</option>
                        <option value="45">45 %</option>
                        <option value="46">46 %</option>
                        <option value="47">47 %</option>
                        <option value="48">48 %</option>
                        <option value="49">49 %</option>
                        <option value="50">50 %</option>
                      </select>
                    </div>
                  </div>
                  
                {{-- <div class="col-md-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="paid">Paid</option>
                      <option value="pending">Pending</option>
                    </select>
                  </div>
                </div> --}}
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Other Information <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="note"></textarea>
                  </div>
                </div>
              </div>
              <div class="login-btn-inner">
                <div class="row">
                  <div class="col-lg-5"></div>
                  <div class="col-lg-7">
                    <div class="login-horizental cancel-wp pull-left">
                      <a href="{{ route('list-purchase') }}" class="btn btn-white" style="margin-bottom:50px">Cancel</a>
                      <button class="btn btn-sm btn-primary login-submit-cs" type="submit"
                        style="margin-bottom:50px">Save
                        Data</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->


  <script>
    $(document).ready(function() {
        // Initialize jQuery Validation
        var validator = $("#purchase_order_table").validate({
            ignore: [], // Validate hidden inputs as well
            rules: {
                'addmore[0][part_no_id]': {
                    required: true,
                },
                // 'addmore[0][description]': {
                //     required: true,
                // },
                // 'addmore[0][due_date]': {
                //     required: true,
                //     minDate: true,
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
                'addmore[0][part_no_id]': {
                    required: "Please Enter the Part Number",
                },
                // 'addmore[0][description]': {
                //     required: "Please Enter the Description",
                // },
                // 'addmore[0][due_date]': {
                //     required: "Please Enter the Due Date",
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
                if (element.hasClass("part-no") || 
                    element.hasClass("due-date") || 
                    element.hasClass("quantity") || element.hasClass("unit") || element.hasClass("rate") ||
                    element.hasClass("total_amount")) {
                    error.insertAfter(element.closest('td'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // Function to initialize validation for dynamically added fields
        function initializeValidation(context) {
            $(context).find('.part-no').each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please Enter the Part Number",
                    }
                });
            });
            // $(context).find('.description').each(function() {
            //     $(this).rules("add", {
            //         required: true,
            //         messages: {
            //             required: "Please Enter the Description",
            //         }
            //     });
            // });
            // $(context).find('.due-date').each(function() {
            //     $(this).rules("add", {
            //         required: true,
            //         minDate: true,
            //         messages: {
            //             required: "Please Enter the Due Date",
            //         }
            //     });
            // });
          
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
            $(context).find('.unit').each(function() {
                $(this).rules("add", {
                    required: true,
                    messages: {
                        required: "Please Enter the Unit",
                    }
                });
            });
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
            $(context).find('.total_amount').each(function() {
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
                         <select class="form-control part-no mb-2" name="addmore[${i}][part_no_id]" id="">
                                                <option value="" default>Tax</option>
                                                @foreach ($dataOutputPartItem as $data)
                                                        <option value="{{ $data['id'] }}" >
                                                            {{ $data['name'] }}</option>
                                                @endforeach
                                            </select>
                       
                    </td>
                    <td>
                        <input class="form-control description" name="addmore[${i}][description]" type="text" style="min-width:150px">
                    </td>
                    <td>
                        <input class="form-control due-date" placeholder="YYYY-MM-DD" name="addmore[${i}][due_date]" type="date"
                            style="min-width:150px" value="">
                    </td>
                    <td>
                        <input class="form-control quantity" name="addmore[${i}][quantity]" style="width:100px" type="text">
                    </td>
                    <td>
                        <input class="form-control unit" name="addmore[${i}][unit]" style="width:100px" type="text">
                    </td>
                    <td>
                        <input class="form-control rate" name="addmore[${i}][rate]" style="width:80px" type="text">
                    </td>
                    <td>
                        <input class="form-control total_amount" name="addmore[${i}][amount]" readonly style="width:120px" type="text">
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
        function setMinDateForDueDates() {
            var today = new Date().toISOString().split('T')[0];
            $('.due-date').attr('min', today);
        }
        setMinDateForDueDates();

        $(document).on('focus', '.due-date', function() {
            setMinDateForDueDates();
        });

        $(document).on('keyup', '.quantity, .rate', function(e) {
            var currentRow = $(this).closest("tr");
            var current_row_quantity = currentRow.find('.quantity').val();
            var current_row_rate = currentRow.find('.rate').val();
            var new_total_price = current_row_quantity * current_row_rate;
            currentRow.find('.total_amount').val(new_total_price);
        });
    });
</script>

    <script>
        $(document).ready(function(){
                    $(document).on('keyup', '.quantity, .rate', function(e) {
                var currentRow = $(this).closest("tr");
                var current_row_quantity=currentRow.find('.quantity').val();
        var current_row_rate = currentRow.find('.rate').val();
        var new_total_price=current_row_quantity * current_row_rate;
        currentRow.find('.total_amount').val(new_total_price);
            });
        });
    </script>

    <script>  
        $(document).ready(function() {
            function setMinDate() {
                var today = new Date();
                var day = String(today.getDate()).padStart(2, '0');
                var month = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var year = today.getFullYear();
                var todayDate = year + '-' + month + '-' + day;
    
                $('#invoice_date').attr('min', todayDate);
               
            }
    
            // Call the function to set the minimum date
            setMinDate();
        $("#forms").validate({

            
                    rules: {
                        vendor_id: {
                            required: true,
                        },
                        tax_type: {
                            required: true,
                        },
                        tax_id: {
                            required: true,
                        },
                        tax: {
                            required: true,
                        },
                        invoice_date: {
                            required: true,
                        },
                        payment_terms: {
                            required: true,
                        },
                        // discount: {
                        //     required: true,
                        //     number: true,
                        // },
                        // quote_no: {
                        //     required: true,
                        //     number: true,
                        // },
                        // status: {
                        //     required: true,
                        // },
                        note: {
                            required: true,
                        },
                        'addmore[][part_no_id]': {
                            required: true,
                        },
                        'addmore[][description]': {
                            required: true,
                        },
                        'addmore[][due_date]': {
                            required: true,
                        },
                        'addmore[][quantity]': {
                            required: true,
                        },
                        'addmore[][unit]': {
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
                      vendor_id: {
                            required: "Please Select the Vendor Company Name",
                        },
                        tax: {
                            required: "Please Enter the Tax",
                        },
                        tax_type: {
                            required: "Please Select the Tax Type",
                        },
                        tax_id: {
                            required: "Please Select the Tax",
                        },
                        invoice_date: {
                            required: "Please Enter the Invoice Date",
                        },
                        payment_terms: {
                            required: "Please Enter the Payment Terms",
                        },
                        // discount: {
                        //     required: "Please Enter the Discount",
                        //     number: "Please enter a valid number.",
                        // },
                        // quote_no: {
                        //   required: "Please Enter the quote number",
                        //   number: "Please enter a valid number.",

                        // },
                        // status: {
                        //     required: "Please Enter the Status",
                        // },
                        note: {
                            required: "Please Enter the Other Information",
                        },
                        'addmore[][part_no_id]': {
                            required: "Please Enter the Part Number",
                        },
                        'addmore[][description]': {
                            required: "Please Enter the Description",
                        },
                        'addmore[][due_date]': {
                            required: "Please Enter the Due Date",
                        },
                        'addmore[][quantity]': {
                            required: "Please Enter the Quantity",
                        },
                        'addmore[][unit]': {
                            required: "Please Enter the Unit",
                        },
                        'addmore[][rate]': {
                            required: "Please Enter the Rate", 
                        },
                        'addmore[][amount]': {
                            required: "Please Enter the Amount",
                        },
                        
                    },

                });

                
            });
    </script>

        @endsection