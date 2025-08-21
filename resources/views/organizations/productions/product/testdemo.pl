<form
                                                action="{{ route('update-purchase-order', $editData[0]->purchase_main_id) }}"
                                                method="POST" id="editDesignsForm" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="purchase_main_id" id=""
                                                    class="form-control" value="{{ $editData[0]->purchase_main_id }}"
                                                    placeholder="">
                                                <a {{-- href="{{ route('add-more-data') }}" --}}>
                                                    <div class="container-fluid">
                                                        <!-- @if ($errors->any())
    <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
                                                                </ul>
                                                            </div>
    @endif -->

  

                                                        @foreach ($editData as $key => $editDataNew)
                                                            @if ($key == 0)
                                                               
                                                                <div class="row">
                                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Invoice date <span
                                                                                    class="text-danger">*</span></label>
                                                                            <div class="cal-icon">
                                                                                <input class="form-control datetimepicker"
                                                                                    type="text" name="invoice_date"
                                                                                    id="invoice_date"
                                                                                    value="{{ $editDataNew->invoice_date }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                  
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        <button type="button" name="add" id="add"
                                                            class="btn btn-success">Add More</button>
                                                        <div style="margin-top:10px;">
                                                            <table class="table table-bordered" id="dynamicTable">
                                                                <tr>
                                                                    <th>Part No</th>
                                                                <th>Quantity</th>
                                                                   <th>Action</th>
                                                                </tr>
                                                                @foreach ($editData as $key => $editDataNew)
                                                                    <tr>
                                                                        <input type="hidden" name="design_count"
                                                                            id="design_id_{{ $key }}"
                                                                            class="form-control"
                                                                            value="{{ $key }}" placeholder="">

                                                                        <input type="hidden"
                                                                            name="design_id_{{ $key }}"
                                                                            id="design_id_{{ $key }}"
                                                                            class="form-control"
                                                                            value="{{ $editDataNew->purchase_order_details_id }}"
                                                                            placeholder="">
                                                                        <td>
                                                                            <select class="form-control part-no mb-2" name="part_no_id_{{ $key }}" id="">
                                                                                <option value="" default>Select Item</option>
                                                                                @foreach ($dataOutputPartItem as $data)
                                                                                <option value="{{ $data['id'] }}"
                                                                                    {{ old('part_no_id', $editDataNew->part_no_id) == $data->id ? 'selected' : '' }}>
                                                                                    {{ $data->name }}
                                                                                </option>
                                                                            @endforeach
                                                                            </select>
                                                                        </td>
                                                                       
                                                                        <td>
                                                                            <input type="text"
                                                                                name="quantity_{{ $key }}"
                                                                                value="{{ $editDataNew->quantity }}"
                                                                                placeholder="Enter Quantity"
                                                                                class="form-control quantity" />
                                                                        </td>
                                                                       <td>
                                                                            <a data-id="{{ $editDataNew->id }}"
                                                                                class="delete-btn btn btn-danger m-1"
                                                                                title="Delete Tender"><i
                                                                                    class="fas fa-archive"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        </div>

                                                        @foreach ($editData as $key => $editDataNew)
                                                            @if ($key == 0)
                                                                <div class="form-group-inner">
                                                                   <div class="row">
                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                            <label for="note">Other Information
                                                                                :</label>
                                                                            <textarea class="form-control" name="note">@if (old('note')){{ old('note') }}@else{{ $editDataNew->note }}@endif</textarea>

                                                                        </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="login-btn-inner">
                                                        <div class="row">
                                                            <div class="col-lg-5"></div>
                                                            <div class="col-lg-7">
                                                                <div class="login-horizental cancel-wp pull-left">
                                                                    <a href="{{ route('list-purchase') }}"
                                                                        class="btn btn-white"
                                                                        style="margin-bottom:50px">Cancel</a>
                                                                    <button class="btn btn-sm btn-primary login-submit-cs"
                                                                        type="submit" style="margin-bottom:50px">Update
                                                                        Data</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </form>
  <script>
        $(document).ready(function() {
            var validator = $("#editDesignsForm").validate({
                ignore: [],
                rules: {
                  
                    'quantity_0': {
                        required: true,
                        digits: true,
                    },
                   
                },
                messages: {
                   
                    'part_no_id_0': {
                        required: "Please enter the Part Number",
                    },
                  'quantity_0': {
                        required: "Please enter the Quantity",
                        digits: "Please enter only digits for Quantity",
                    },
                   
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass("part_no_id") ||
                        
                        element.hasClass("quantity")) {
                        error.insertAfter(element);
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            var i = {!! count($editData) !!}; // Initialize i with the number of existing rows

            $("#add").click(function() {
                ++i;

                var newRow = $(
                    '<tr>' +
                    '<input type="hidden" name="addmore[' + i +
                    '][design_count]" class="form-control" value="' + i +
                    '" placeholder=""> <input type="hidden" name="addmore[' + i +
                    '][purchase_id]" class="form-control" value="' + i + '" placeholder="">' +
                    '<td>' +
            '<select class="form-control part_no_id mb-2" name="addmore[' + i + '][part_no_id]" id="">' +
                '<option value="" default>Select Part Item</option>' +
                '@foreach ($dataOutputPartItem as $data)' +
                    '<option value="{{ $data['id'] }}">{{ $data['description'] }}</option>' +
                '@endforeach' +
            '</select>' +
         
                    '<td><input type="text" class="form-control quantity" name="addmore[' + i +
                        '][quantity]" placeholder=" Quantity" /></td>' +
                    '<td><a class="remove-tr delete-btn btn btn-danger m-1" title="Delete Tender"><i class="fas fa-archive"></i></a></td>' +
                    '</tr>'
                );

                $("#dynamicTable").append(newRow);

                // Reinitialize validation for the new row
                $('select[name="addmore[' + i + '][part_no_id]"]').rules("add", {
            required: true,
            messages: {
                required: "Please select the Part Number",
            }
        });
              $('input[name="addmore[' + i + '][quantity]"]').rules("add", {
                    required: true,
                    digits: true,
                    messages: {
                        required: "Please enter the Quantity",
                        digits: "Please enter only digits for Quantity",
                    }
                });
            
              $('input[name="addmore[' + i + '][amount]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the Amount",
                    }
                });
            });
 $(document).on("click", ".remove-tr", function() {
                $(this).parents("tr").remove();
            });

            // Custom validation method for minimum date
            $.validator.addMethod("minDate", function(value, element) {
                var today = new Date();
                var inputDate = new Date(value);
                return inputDate >= today;
            }, "The date must be today or later.");

            // Initialize date pickers with min date set to today
            function setMinDateForDueDates() {
                var today = new Date().toISOString().split('T')[0];
                $('.due_date').attr('min', today);
            }
            setMinDateForDueDates();
 $(document).on('focus', '.due_date', function() {
                setMinDateForDueDates();
            });

            $(document).on('keyup', '.quantity, .rate', function(e) {
                var currentRow = $(this).closest("tr");
                var quantity = currentRow.find('.quantity').val();
                var rate = currentRow.find('.rate').val();
                var amount = quantity * rate;
                currentRow.find('.amount').val(amount);
            });
});
    </script>

using above addmore logic use in below form
 <form action="{{ route('update-production', $id) }}" method="POST" id="addProductForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="business_details_id" id="business_details_id" value="{{ $id }}">

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="description">Description:</label>
                                                <input type="text" class="form-control" id="description" name="description" value="{{ $productDetails->description }}" placeholder="Enter Description" readonly>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover table-white repeater" id="purchase_order_table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Part Item</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Send to Production</th>
                                                        <th>
                                                            <button type="button" class="btn btn-sm btn-bg-colour" id="add_more_btn">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($dataGroupedById as $key => $items)
                                                        @foreach ($items as $index => $item)
                                                            <tr @if($item->material_send_production) class="disabled-row" @endif>
                                                                <td>
                                                                    <input type="hidden" name="addmore[{{ $index }}][id]" class="form-control" value="{{ $item->id }}">
                                                                    <input type="text" class="form-control" readonly value="{{ $item->id }}">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control part-no" name="addmore[{{ $index }}][part_no_id]" @if($item->material_send_production) disabled @endif>
                                                                        <option value="">Select Part Item</option>
                                                                        @foreach ($dataOutputPartItem as $partItem)
                                                                            <option value="{{ $partItem->id }}" {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                                {{ $partItem->description }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control quantity" name="addmore[{{ $index }}][quantity]" type="text" value="{{ $item->quantity }}" @if($item->material_send_production) readonly @endif>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control unit" name="addmore[{{ $index }}][unit]" type="text" value="{{ $item->unit }}" @if($item->material_send_production) readonly @endif>
                                                                </td>
                                                                <td>
                                                                    <span class="material-send-production-status">
                                                                        @if($item->material_send_production)
                                                                            <i class="fa fa-check" style="color: green;"></i>
                                                                        @else
                                                                            <i class="fa fa-times" style="color: red;"></i>
                                                                        @endif
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if(!$item->material_send_production)
                                                                        <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="login-btn-inner">
                                            <button class="btn btn-sm btn-primary" type="submit">Save Data</button>
                                        </div>
                                    </form>
<script>
    let rowCounter = @json($dataGroupedById->count());
function initializeValidation(row) {
        row.find('.part-no').rules("add", {
            required: true,
            maxlength: 100,
            messages: {
                required: "Please enter the Part Item.",
                maxlength: "Part Item must be at most 100 characters long."
            }
        });
        row.find('.quantity').rules("add", {
            required: true,
            digits: true,
            min: 1,
            messages: {
                required: "Please enter the Quantity.",
                digits: "Please enter only digits for Quantity.",
                min: "Quantity must be at least 1."
            }
        });
        row.find('.unit').rules("add", {
            required: true,
            messages: {
                required: "Please enter the unit."
            }
        });
    }
$("#add_more_btn").click(function() {
        rowCounter++;
        var businessDetailsId = $('#business_details_id').val();
        var newRow = 
            <tr>
                <td>
                    <input type="hidden" name="addmore[${rowCounter}][id]" class="form-control" value="${rowCounter}">
                    <input type="text" class="form-control" readonly value="${rowCounter}">
                </td>
                <td>
                    <select class="form-control part-no mb-2" name="addmore[${rowCounter}][part_no_id]">
                        <option value="">Select Part Item</option>
                        @foreach ($dataOutputPartItem as $data)
                            <option value="{{ $data['id'] }}">{{ $data['description'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input class="form-control quantity" name="addmore[${rowCounter}][quantity]" type="text">
                </td>
                <td>
                    <input class="form-control unit" name="addmore[${rowCounter}][unit]" type="text">
                </td>
                <td>
                    <span class="material-send-production-status">
                        <i class="fa fa-question" style="color: gray;"></i>
                    </span>
                </td>
                <td>
                    <input type="hidden" name="addmore[${rowCounter}][business_details_id]" value="${businessDetailsId}">
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>;
var row = $(newRow).appendTo("#purchase_order_table tbody");

        // Attach validation to the new row
        initializeValidation(row);
    });

    // Remove a row when the delete button is clicked
    $(document).on("click", ".remove-row", function() {
        $(this).closest("tr").remove();
    });

    // Remove material_send_production fields before form submission
    $("#addProductForm").on("submit", function() {
        $(this).find('input[name*="[material_send_production]"]').remove();
    });
</script>
please give me correct code