@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            /* Change 'red' to your desired text color */
            font-size: 12px;
            /* Adjust font size if needed */
            /* Add any other styling as per your design */
        }

        .sparkline12-list {
            margin-bottom: 100px;
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-start align-items-center">
                    <h5 class="page-title">
                        Product Consumption Material List
                    </h5>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 show-btn-position">
                     <a href="{{ route('list-consumption-report') }}" class=" ml-3"> <button type="submit" class="btn btn-primary filterbg">Back</button>
                   </a>
                </div>
            </div>

                        
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if (session('status') === 'error')
                                    <div class="alert alert-danger">
                                        {{ session('msg') }}
                                    </div>
                                @endif

                                @if (isset($productDetails) && isset($dataGroupedById))
                                    <!-- Display product details -->
                                @else
                                    <div class="alert alert-warning">
                                        No product details available.
                                    </div>
                                @endif

                                <form method="POST" id="addProductForm" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="business_details_id" id="business_details_id"
                                        value="{{ $id }}">
                                    <input type="hidden" name="id" id="id" value="{{ $productDetails->id }}">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="product_name">Name:</label>
                                            <input type="text" class="form-control" id="name" name="product_name"
                                                value="{{ $productDetails->product_name }}" placeholder="Enter Product Name"
                                                readonly>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label for="description">Description:</label>
                                            <input type="text" class="form-control" id="description" name="description"
                                                value="{{ $productDetails->description }}" placeholder="Enter Description"
                                                readonly>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-white repeater" id="purchase_order_table">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No.</th>
                                                    <th>Part Item</th>
                                                    <th>Quantity</th>
                                                    <th>Unit</th>
                                                    <th>Basic Rate</th>
                                                    <th>Amount</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dataGroupedById as $key => $items)
                                                    @foreach ($items as $index => $item)
                                                  
                                                        <tr class="item-row">
                                                            <td>
                                                                <input type="text"
                                                                    name="addmore[{{ $index }}][id]"
                                                                    class="form-control" readonly
                                                                    value="{{ $index + 1 }}">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" readonly
                                                                    value="{{ $dataOutputPartItem->where('id', $item->part_item_id)->first()->description ?? 'N/A' }}">
                                                                <input type="hidden"
                                                                    name="addmore[{{ $index }}][part_item_id]"
                                                                    value="{{ $item->part_item_id }}">


                                                            </td>
                                                            <td>
                                                                <input class="form-control quantity" readonly
                                                                    name="addmore[{{ $index }}][quantity]"
                                                                    type="text" step="any" required
                                                                    value="{{ $item->production_quantity }}">
                                                                <span class="stock-available"></span>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" readonly
                                                                    value="{{ $dataOutputUnitMaster->where('id', $item->unit)->first()->name ?? 'N/A' }}">
                                                                <input type="hidden"
                                                                    name="addmore[{{ $index }}][unit]"
                                                                    value="{{ $item->unit }}">
                                                            </td>
                                                               <td>
                                                                <input class="form-control basic_rate" readonly
                                                                    name="addmore[{{ $index }}][basic_rate]"
                                                                    type="text" step="any" required
                                                                    value="{{ $item->basic_rate }}">
                                                                <span class="stock-available"></span>
                                                            </td>
                                                              <td>
                                                                <input class="form-control items_used_total_amount" readonly
                                                                    name="addmore[{{ $index }}][items_used_total_amount]"
                                                                    type="text" step="any" required
                                                                    value="{{ $item->items_used_total_amount }}">
                                                                <span class="stock-available"></span>
                                                            </td>
                                                        </tr>
                                                       
                                                    @endforeach
                                                @endforeach
                                                <tr>
                                                    <td colspan="5" class="text-right"><b>Total</b></td>
                                                    <td>
                                                        <input class="form-control total_items_used_amount" readonly
                                                            type="text" step="any"
                                                            value="{{ $total_items_used_amount }}">
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
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
@endsection
