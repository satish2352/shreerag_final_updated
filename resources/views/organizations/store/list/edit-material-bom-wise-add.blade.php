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
                    <center><h1>Issue Material For Product</h1></center>
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
        //    dd($productDetails);
        //    die();
           ?>
                             <form action="{{ route('update-material-list-bom-wise', $id) }}" method="POST" id="addProductForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="business_details_id" id="business_details_id" value="{{ $id }}">
                                <input type="hidden" name="production_id" id="production_id" value="{{ $productDetails->productionId }}"> <!-- Hidden field for productionId -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label for="product_name">Name:</label>
                                        <input type="text" class="form-control" id="name" name="product_name" value="{{ $productDetails->product_name }}" placeholder="Enter Product Name" readonly>
                                    </div>
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
                                                    <button type="button" class="btn btn-sm btn-success" id="add_more_btn">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataGroupedById as $key => $items)
                                                @foreach ($items as $index => $item)
                                                    <tr>
                                                        <td>
                                                            <input type="text" name="addmore[{{ $index }}][id]" class="form-control" readonly value="{{ $item->id }}">
                                                        </td>
                                                        <td>
                                                            <select class="form-control part-no" name="addmore[{{ $index }}][part_item_id]">
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
                                        </tbody>
                                    </table>
                                </div>
                                <div class="login-btn-inner">
                                    <button class="btn btn-sm btn-primary" type="submit" id="saveBtn">Save Data</button>
                                </div>
                                <?php
                                // dd($productDetails);
                                // die();
                                ?>
                                {{-- <a href="{{ route('accepted-and-material-sent', base64_encode($productDetails->id)) }}">
                                    <button data-toggle="tooltip" title="Requirement forwarded for production" class="pd-setting-ed">
                                        Requirement forwarded for production
                                    </button>
                                </a> --}}
                                {{-- <div class="login-btn-inner">
                                    <button class="btn btn-sm btn-primary" type="submit">Save Data</button>
                                </div> --}}
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
                <select class="form-control part-no mb-2" name="addmore[${i}][part_item_id]">
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
});
});
</script>



@endsection
