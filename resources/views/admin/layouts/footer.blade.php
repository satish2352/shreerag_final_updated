</div>
<div class="footer-copyright-area navbar-fixed-bottom">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="footer-copy-right">
                    <p>Copyright &copy; {{ date('Y') }} <a href="https://www.sumagoinfotech.com"
                            target="_blank"> Made with Passion by Sumago Infotech.</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/vendor/jquery-1.11.3.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/jquery-price-slider.js') }}"></script>
<script src="{{ asset('js/jquery.meanmenu.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/jquery.sticky.js') }}"></script>
<script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
<script src="{{ asset('js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('js/scrollbar/mCustomScrollbar-active.js') }}"></script>
<script src="{{ asset('js/metisMenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('js/metisMenu/metisMenu-active.js') }}"></script>
<!-- <script src="{{ asset('js/morrisjs/raphael-min.js') }}"></script>
<script src="{{ asset('js/morrisjs/morris.js') }}"></script>
<script src="{{ asset('js/morrisjs/morris-active.js') }}"></script> -->
<script src="{{ asset('js/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('js/sparkline/jquery.charts-sparkline.js') }}"></script>
<script src="{{ asset('js/calendar/moment.min.js') }}"></script>
<script src="{{ asset('js/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('js/calendar/fullcalendar-active.js') }}"></script>
<script src="{{ asset('js/plugins.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table.js') }}"></script>
<script src="{{ asset('js/data-table/tableExport.js') }}"></script>
<script src="{{ asset('js/data-table/data-table-active.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-editable.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-editable.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-resizable.js') }}"></script>
<script src="{{ asset('js/data-table/colResizable-1.5.source.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-export.js') }}"></script>
<script src="{{ asset('js/editable/jquery.mockjax.js') }}"></script>
<script src="{{ asset('js/editable/mock-active.js') }}"></script>
<script src="{{ asset('js/editable/select2.js') }}"></script>
<script src="{{ asset('js/editable/moment.min.js') }}"></script>
<script src="{{ asset('js/editable/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('js/editable/bootstrap-editable.js') }}"></script>
<script src="{{ asset('js/editable/xediable-active.js') }}"></script>
<script src="{{ asset('js/chart/jquery.peity.min.js') }}"></script>
<script src="{{ asset('js/peity/peity-active.js') }}"></script>
<script src="{{ asset('js/tab.js') }}"></script>
<link rel="stylesheet" href="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css') }}">
<script src="{{ asset('https://cdn.jsdelivr.net/npm/flatpickr') }}"></script>

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
                var current_row_quantity=currentRow.find('input[name="addmore[quantity]"]').val();
        var current_row_rate = currentRow.find('input[name="addmore[rate]"]').val();
        var new_total_price=current_row_quantity * current_row_rate;
        currentRow.find('input[name="addmore[amount]"]').val(new_total_price);
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
                        
                    },

                });

                
            });
    </script>

</body>

</html>
