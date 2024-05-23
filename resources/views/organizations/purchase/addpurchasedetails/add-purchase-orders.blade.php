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
            <form action="{{route('purchase.store-purchase-order')}} " id="forms" method="post" enctype="multipart/form-data">
              @csrf
              
              <input class="form-control" type="hidden" name="requistition_id" id="requistition_id" value="{{$requistition_id}}">
              <input class="form-control" type="hidden" name="client_name" id="client_name">
              <div class="row">
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Client Name<span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="client_name" id="client_name">
                    @if ($errors->has('client_name'))
                        <span class="red-text"><?php echo $errors->first('client_name', ':message'); ?></span>
                    @endif
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Phone Number <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" name="phone_number">
                  </div>
                </div>

                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" type="email" name="email">
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Tax</label>
                    <select name="tax" class="form-control" title="select tax" id="tax">
                      <option value="">Select Tax</option>
                      <option value="9">C-GST</option>
                      <option value="9">S-GST</option>
                      <option value="18">C-GST + S-GST</option>

                    </select>
                  </div>
                </div>
              </div>
              <div class="row">

                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Invoice Date <span class="text-danger">*</span></label>
                    <div class="cal-icon">
                    <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                name="invoice_date" id="invoice_date"
                                                value="">
                      <!-- <input class="form-control datetimepicker" type="text" name="invoice_date"> -->
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>GST Number<span class="text-danger">*</span></label>
                    <div class="cal-icon">
                      <input class="form-control " type="text" name="gst_number">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Payment Terms</label>
                    <select name="payment_terms" class="form-control" title="select tax" id="">
                      <option value="">Select Tax</option>
                      <option value="30">30 Days</option>
                      <option value="60">60 Days</option>
                      <option value="90">90 Days</option>

                    </select>
                  </div>
                </div>
                <div class="col-sm-6 col-md-3">
                  <div class="form-group">
                    <label>Client Address</label>
                    <textarea class="form-control" rows="3" name="client_address"></textarea>
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
                          <th class="col-md-2">HSN</th>
                          <th class="col-md-2">Quantity</th>
                          <th class="col-md-2">Rate</th>
                          <th>Amount</th>
                          <th><button type="button" class="btn btn-sm btn-success font-18 mr-1" id="add_more_btn" title="Add" data-repeater-create>
                              <i class="fa fa-plus"></i>
                            </button> </th>
                        </tr>
                      </thead>
                      <tbody >
                        <tr >
                          <td>
                            <input type="text" name="id" class="form-control" style="min-width:50px" readonly value="1">
                            <input type="hidden" id="i_id" class="form-control" style="min-width:50px" readonly value="0">
                          </td>
                          <td>
                            <input class="form-control" name="addmore[0][part_no]" type="text" style="min-width:150px">
                          </td>
                          <td>
                            <input class="form-control " name="addmore[0][description]" type="text" style="min-width:150px">
                          </td>
                          <td>
                          <!-- <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                name="addmore[0][due_date]" id="due_date"
                                                value=""> -->
                            <input class="form-control" placeholder="YYYY-MM-DD" name="addmore[0][due_date]" type="date"
                              style="min-width:150px" value="">
                          </td>
                          <td>
                            <input class="form-control" name="addmore[0][hsn]" style="width:100px" type="text">
                          </td>
                          <td>
                            <input class="form-control quantity" name="addmore[0][quantity]" style="width:100px" type="text">
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
                    <label>Discount </label>
                    <input class="form-control text-right" type="text" name="discount" value="" placeholder="0">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                      <option value="paid">Paid</option>
                      <option value="pending">Pending</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Other Information</label>
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
        // Add more rows when the "Add More" button is clicked
        $("#add_more_btn").click(function() {
        //   alert('kkkkkkkkkkkkkkk');
        var i_count=$('#i_id').val();
        var i = parseInt(i_count)+parseInt(1)
        $('#i_id').val(i);
            var newRow = `
            <tr>
            <td>
                <input type="text" name="id" class="form-control" style="min-width:50px" readonly value="`+i+`">
            </td>
            <td>
                <input class="form-control" name="addmore[`+i+`][part_no]" type="text" style="min-width:150px">
            </td>
            <td>
                <input class="form-control " name="addmore[`+i+`][description]" type="text" style="min-width:150px">
            </td>
            <td>
            <input type="date" class="form-control mb-2" placeholder="YYYY-MM-DD"
                                                name="addmore[0][due_date]" id="due_date"
                                                value="">
            </td>
            <td>
                <input class="form-control" name="addmore[`+i+`][hsn]" style="width:100px" type="text">
            </td>
            <td>
                <input class="form-control quantity" name="addmore[`+i+`][quantity]" style="width:100px" type="text">
            </td>
            <td>
                <input class="form-control rate" name="addmore[`+i+`][rate]" style="width:80px" type="text">
            </td>
            <td>
                <input class="form-control total_amount" name="addmore[`+i+`][amount]" readonly style="width:120px" type="text">
            </td>
            <td>
            <button type="button" class="btn btn-sm btn-danger font-18 ml-2 remove-row" title="Delete"
                data-repeater-delete>
                <i class="fa fa-trash"></i>
            </button>

            </td>
            </tr>
            `;
            $("#purchase_order_table tbody").append(newRow);
            i++;
        });
        
        // Remove a row when the "Remove" button is clicked
        $(document).on("click", ".remove-row", function() {
            var i_count=$('#i_id').val();
            var i = parseInt(i_count)-parseInt(1)
            $('#i_id').val(i);

            $(this).closest("tr").remove();
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
        $("#forms").validate({
                    rules: {
                        client_name: {
                            required: true,
                        },
                        phone_number: {
                            required: true,
                        },
                        email: {
                            required: true,
                        },
                        tax: {
                            required: true,
                        },
                        invoice_date: {
                            required: true,
                        },
                        gst_number: {
                            required: true,
                        },
                        payment_terms: {
                            required: true,
                        },
                        client_address: {
                            required: true,
                        },
                        discount: {
                            required: true,
                        },
                        status: {
                            required: true,
                        },
                        note: {
                            required: true,
                        },
                        'addmore[][part_no]': {
                            required: true,
                        },
                        'addmore[][description]': {
                            required: true,
                        },
                        'addmore[][due_date]': {
                            required: true,
                        },
                        'addmore[][hsn]': {
                            required: true,
                        },
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
                        client_name: {
                            required: "Please Enter the Client Name",
                        },
                        phone_number: {
                            required: "Please Enter the Phone Number",
                        },
                        email: {
                            required: "Please Enter the Eamil",
                        },
                        tax: {
                            required: "Please Enter the Tax",
                        },
                        invoice_date: {
                            required: "Please Enter the Invoice Date",
                        },
                        gst_number: {
                            required: "Please Enter the GST Number",
                        },
                        payment_terms: {
                            required: "Please Enter the Payment Terms",
                        },
                        client_address: {
                            required: "Please Enter the Client Address",
                        },
                        discount: {
                            required: "Please Enter the Discount",
                        },
                        status: {
                            required: "Please Enter the Status",
                        },
                        note: {
                            required: "Please Enter the Other Information",
                        },
                        'addmore[][part_no]': {
                            required: "Please Enter the Part Number",
                        },
                        'addmore[][description]': {
                            required: "Please Enter the Description",
                        },
                        'addmore[][due_date]': {
                            required: "Please Enter the Due Date",
                        },
                        'addmore[][hsn]': {
                            required: "Please Enter the HSN",
                        },
                        'addmore[][quantity]': {
                            required: "Please Enter the Quantity",
                        },
                        'addmore[][rate]': {
                            required: "Please Enter the Rate", 
                        },
                        'addmore[][amount]': {
                            required: "Please Enter the Amount",
                        },
                        
                    },

                    // errorElement: "span",
                    // errorPlacement: function(error, element) {
                    //     error.addClass("text-danger");
                    //     error.insertAfter(element);
                    // },
                    // highlight: function(element, errorClass, validClass) {
                    //     $(element).addClass(errorClass).removeClass(validClass);
                    //     $(element).closest('.form-group').addClass('has-error');
                    // },
                    // unhighlight: function(element, errorClass, validClass) {
                    //     $(element).removeClass(errorClass).addClass(validClass);
                    //     $(element).closest('.form-group').removeClass('has-error');
                    // }

                });

                
            });
    </script>

        @endsection