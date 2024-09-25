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
    .disabled-btn {
        background-color: #ccc;  /* Light gray background */
        color: #666;             /* Darker gray text */
        cursor: not-allowed;     /* Show not-allowed cursor */
        opacity: 0.7;            /* Slightly transparent */
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="sparkline12-list">
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Issue Material for Product</h1></center>
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
           
                             <form action="{{ route('update-recived-inprocess-production-material', $id) }}" method="POST" id="addProductForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="business_details_id" id="business_details_id" value="{{ $id }}">
                                <input type="hidden" name="part_item_id" id="part_item_id" value="{{ $id }}">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label for="product_name">Name:</label>
                                        <input type="text" class="form-control" id="name" name="product_name" value="{{ $productDetails->product_name }}" placeholder="Enter Product Name" readonly>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="description">Description:</label>
                                        <input type="text" class="form-control" id="description" name="description" value="{{ $productDetails->description }}" placeholder="Enter Description" readonly>
                                    </div>
                                    {{-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label for="unit_id">Employee <span class="text-danger">*</span></label>
                                        <select class="form-control" name="user_id" id="user_id">
                                            <option value="">Select Employee</option>
                                            @foreach ($dataOutputUser as $data)
                                                <option value="{{ $data['id'] }}" {{ old('user_id') == $data['id'] ? 'selected' : '' }}>{{ $data['f_name'] }}{{ $data['l_name'] }}{{ $data['m_name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('user_id'))
                                        <span class="red-text">{{ $errors->first('user_id') }}</span>
                                        @endif
                                    </div> --}}
                                    
                                  
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
                                                    <button type="button" class="btn btn-sm btn-success" id="add_more_btn">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        {{-- <tbody>
                                            @foreach ($dataGroupedById as $key => $items)
                                                @foreach ($items as $index => $item)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="addmore[{{ $index }}][id]" class="form-control" readonly value="{{ $item->id }}">
                                                        </td>
                                                        <td>
                                                            <select class="form-control part-no" name="addmore[{{ $index }}][part_no_id]">
                                                                <option value="">Select Part Item</option>
                                                                @foreach ($dataOutputPartItem as $partItem)
                                                                    <option value="{{ $partItem->id }}" {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                        {{ $partItem->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control quantity" name="addmore[{{ $index }}][quantity]" type="text" value="{{ $item->quantity }}">
                                                        </td>
                                                        <td>
                                                            <input class="form-control unit" name="addmore[{{ $index }}][unit]" type="text" value="{{ $item->unit }}">
                                                        </td>
                                                        <td>
                                                            <input type="checkbox" name="addmore[{{ $index }}][material_send_production]" value="1" {{ $item->material_send_production ? 'checked' : '' }}>

                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody> --}}
                                        <tbody>
                                            @foreach ($dataGroupedById as $key => $items)
                                                @foreach ($items as $index => $item)
                                                    <tr>
                                                        <td>
                                                            {{ $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            @if($item->material_send_production == 0)
                                                            <select class="form-control part-no" name="addmore[{{ $index }}][part_no_id]">
                                                                <option value="">Select Part Item</option>
                                                                @foreach ($dataOutputPartItem as $partItem)
                                                                    <option value="{{ $partItem->id }}" {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                        {{ $partItem->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @else
                                                            <select class="form-control part-no disabled-btn" name="addmore[{{ $index }}][part_no_id]" disabled>
                                                                <option value="">Select Part Item</option>
                                                                @foreach ($dataOutputPartItem as $partItem)
                                                                    <option value="{{ $partItem->id }}" {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                        {{ $partItem->description }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @endif

                                                        </td>
                                                        <td>
                                                            @if($item->material_send_production == 0)
                                                            <input class="form-control quantity" name="addmore[{{ $index }}][quantity]" type="text" value="{{ $item->quantity }}">
                                                            @else
                                                            <input class="form-control quantity disabled-btn" name="addmore[{{ $index }}][quantity]" type="text" value="{{ $item->quantity }}" disabled>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->material_send_production == 0)
                                                            <input class="form-control unit" name="addmore[{{ $index }}][unit]" type="text" value="{{ $item->unit }}">
                                                            @else
                                                            <input class="form-control unit disabled-btn" name="addmore[{{ $index }}][unit]" type="text" value="{{ $item->unit }}" disabled>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->material_send_production == 0)
                                                            <input type="checkbox" name="addmore[{{ $index }}][material_send_production]"  value="1" {{ $item->material_send_production ? 'checked' : '' }}>
                                                            @else
                                                            <input type="checkbox" name="addmore[{{ $index }}][material_send_production]" class="disabled-btn" value="1" {{ $item->material_send_production ? 'checked' : '' }} disabled>
                                                            @endif
                                                        </td>
                                                        
                                                        <td>
                                                            @if($item->material_send_production == 0)
                                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            @else
                                                        <button type="button" class="btn btn-sm btn-danger remove-row disabled-btn" disabled>
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
   
    // Add more rows when the "Add More" button is clicked
$("#add_more_btn").click(function() {
    var i_count = $('#i_id').val();
    var i = parseInt(i_count) + 1;
    $('#i_id').val(i);

    var newRow = `
        <tr>
            <td>
                <input type="text" name="addmore[${i}][id]" class="form-control" style="min-width:50px" readonly value="${i}">
            </td>
            <td>
                <select class="form-control part-no mb-2" name="addmore[${i}][part_no_id]">
                    <option value="">Select Part Item</option>
                    @foreach ($dataOutputPartItem as $data)
                        <option value="{{ $data['id'] }}">{{ $data['description'] }}</option>
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
                <input type="checkbox" name="addmore[${i}][material_send_production]" value="1">
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


    $(document).on('click', '.remove-row', function() {
    $(this).closest('tr').remove();
    updateSerialNumbers(); // This function should update the serial numbers dynamically after deletion
});

function updateSerialNumbers() {
    $('#purchase_order_table tbody tr').each(function(index, row) {
        $(row).find('td:first').text(index + 1); // Updates the serial number
    });
}

});

</script>



@endsection
