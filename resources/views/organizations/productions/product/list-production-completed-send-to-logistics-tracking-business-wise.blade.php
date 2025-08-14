@extends('admin.layouts.master')
@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 200px;">
        <div class="sparkline12-list" >
            <div class="sparkline12-hd">
                <div class="main-sparkline12-hd">
                    <center><h1>Used Material List for Production Making </h1></center>
                </div>
            </div>
            <div class="sparkline12-graph">
                <div class="basic-login-form-ad">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="all-form-element-inner">
                                     <form action="{{ route('update-production', $id) }}" method="POST" id="addProductForm" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="business_details_id" id="business_details_id" value="{{ $id }}">
                                        <input type="hidden" name="part_item_id" id="part_item_id" value="{{ $id }}">
                                        <div class="row">
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
                                            <table class="table table-hover table-white" id="purchase_order_table">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Part Item</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody style="overflow: scroll;">
                                                    @foreach ($dataGroupedById as $key => $items)
                                                        @foreach ($items as $index => $item)
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control" readonly value="{{ $index + 1 }}">
                                                                </td>
                                                                <td>
                                                                    <select class="form-control part-no" name="addmore[{{ $index }}][part_item_id]" disabled>
                                                                        <option value="">Select Part Item</option>
                                                                        @foreach ($dataOutputPartItem as $partItem)
                                                                            <option value="{{ $partItem->id }}" {{ $partItem->id == $item->part_item_id ? 'selected' : '' }}>
                                                                                {{ $partItem->description }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control val-quantity" name="addmore[{{ $index }}][quantity]" type="text" value="{{ $item->quantity }}" readonly>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control val-unit" name="addmore[{{ $index }}][unit]" disabled>
                                                                        <option value="">Select Unit</option>
                                                                        @foreach ($dataOutputUnitMaster as $unit_data)
                                                                            <option value="{{ $unit_data->id }}" {{ $unit_data->id == $item->unit ? 'selected' : '' }}>
                                                                                {{ $unit_data->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
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
@endsection
