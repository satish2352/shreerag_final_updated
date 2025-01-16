@extends('admin.layouts.master')
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
@section('content')
    <style>
        a {
            color: black;
        }

        a:hover {
            color: black;
        }

        label {
            margin-top: 10px;
        }

        label.error {
            color: red;
            /* Change 'red' to your desired text color */
            font-size: 12px;
            /* Adjust font size if needed */
            /* Add any other styling as per your design */
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline12-list">
                    <div class="sparkline12-hd">
                        <div class="main-sparkline12-hd">
                            <center>
                                <h1>Edit Purchase Order Data</h1>
                            </center>
                        </div>
                    </div>
                    <div class="sparkline12-graph">
                        <div class="basic-login-form-ad">
                            <div class="row">
                                @if (session('msg'))
                                    <div class="alert alert-{{ session('status') }}">
                                        {{ session('msg') }}
                                    </div>
                                @endif

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if (Session::has('status'))
                                        <div class="col-md-12">
                                            <div class="alert alert-{{ Session::get('status') }} alert-dismissible"
                                                role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <strong>{{ ucfirst(Session::get('status')) }}!</strong>
                                                {{ Session::get('msg') }}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="all-form-element-inner">
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
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label for="Service">Vendor Company
                                                                                Name:  <span class="text-danger">*</span></label> 
                                                                            <select class="form-control mb-2 select2"
                                                                                name="vendor_id" id="vendor_id">
                                                                                <option value="" default>Select
                                                                                    Vendor Company Name</option>
                                                                                @foreach ($dataOutputVendor as $service)
                                                                                    <option value="{{ $service['id'] }}"
                                                                                        {{ old('vendor_id', $editDataNew->vendor_id) == $service->id ? 'selected' : '' }}>
                                                                                        {{ $service->vendor_company_name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @if ($errors->has('vendor_id'))
                                                                                <span
                                                                                    class="red-text">{{ $errors->first('vendor_id') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Contact Person Name<span class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control" id="contact_person_name" value="@if (old('contact_person_name')) {{ old('contact_person_name') }}@else{{ $editDataNew->contact_person_name }} @endif" name="contact_person_name" placeholder="Contact Person Name">
                                                                            @if ($errors->has('contact_person_name'))
                                                                            <span class="red-text"><?php echo $errors->first('contact_person_name', ':message'); ?></span>
                                                                        @endif
                                                                          
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Contact Person Number<span class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control" id="contact_person_number" value="@if (old('contact_person_number')) {{ old('contact_person_number') }}@else{{ $editDataNew->contact_person_number }} @endif" name="contact_person_number" placeholder="Contact Person Number">
                                                                            @if ($errors->has('contact_person_number'))
                                                                            <span class="red-text"><?php echo $errors->first('contact_person_number', ':message'); ?></span>
                                                                        @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                                                        <div class="form-group">
                                                                            <label>Tax Type <span class="text-danger">*</span></label>
                                                                            <select class="form-control mb-2" name="tax_type" id="tax_type">
                                                                                <option value="" {{ old('tax_type') == '' ? 'selected' : '' }}>Select Tax Type</option>
                                                                                <option value="GST" {{ old('tax_type', $editDataNew->tax_type) == 'GST' ? 'selected' : '' }}>GST</option>
                                                                                <option value="SGST" {{ old('tax_type', $editDataNew->tax_type) == 'SGST' ? 'selected' : '' }}>SGST</option>
                                                                                <option value="CGST" {{ old('tax_type', $editDataNew->tax_type) == 'CGST' ? 'selected' : '' }}>CGST</option>
                                                                                <option value="SGST+CGST" {{ old('tax_type', $editDataNew->tax_type) == 'SGST+CGST' ? 'selected' : '' }}>SGST+CGST</option>
                                                                                <option value="IGST" {{ old('tax_type', $editDataNew->tax_type) == 'IGST' ? 'selected' : '' }}>IGST</option>
                                                                            </select>
                                                                            @if ($errors->has('tax_type'))
                                                                                <span class="red-text">{{ $errors->first('tax_type') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label for="Service">Tax :  <span class="text-danger">*</span></label> 
                                                                        <select class="form-control mb-2"
                                                                            name="tax_id" id="tax_id">
                                                                            <option value="" default>Select
                                                                                Item Part</option>
                                                                            @foreach ($dataOutputTax as $taxData)
                                                                                <option value="{{ $taxData['id'] }}"
                                                                                    {{ old('tax_id', $editDataNew->tax_id) == $taxData->id ? 'selected' : '' }}>
                                                                                    {{ $taxData->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @if ($errors->has('tax_id'))
                                                                            <span
                                                                                class="red-text">{{ $errors->first('tax_id') }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-sm-4">
                                                                    <div class="form-group">
                                                                        <label>Purchase Order Date <span class="text-danger">*</span></label>
                                                                        <div class="cal-icon">
                                                                            <input class="form-control datetimepicker"
                                                                                type="text" name="invoice_date"
                                                                                id="invoice_date"
                                                                                value="{{ $editDataNew->invoice_date }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                              
                                                                 
                                                                    
                                                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                                                        <div class="form-group">
                                                                            <label>Payment Terms <span class="text-danger">*</span></label>
                                                                            <input type="text" class="form-control" id="payment_terms" value="@if (old('payment_terms')) {{ old('payment_terms') }}@else{{ $editDataNew->payment_terms }} @endif" name="payment_terms" placeholder="Enter payment terms">
                                                                            @if ($errors->has('payment_terms'))
                                                                            <span class="red-text"><?php echo $errors->first('payment_terms', ':message'); ?></span>
                                                                        @endif
                                                                            {{-- <select name="payment_terms"
                                                                                class="form-control"
                                                                                title="select payment terms"
                                                                                id="payment_terms">
                                                                                <option value="">Select Payment Terms
                                                                                </option>
                                                                                <option value="30"
                                                                                    {{ $editDataNew->payment_terms == 30 ? 'selected' : '' }}>
                                                                                    30 Days</option>
                                                                                <option value="60"
                                                                                    {{ $editDataNew->payment_terms == 60 ? 'selected' : '' }}>
                                                                                    60 Days</option>
                                                                                <option value="90"
                                                                                    {{ $editDataNew->payment_terms == 90 ? 'selected' : '' }}>
                                                                                    90 Days</option>
                                                                            </select> --}}
                                                                            @if ($errors->has('payment_terms'))
                                                                                <span
                                                                                    class="red-text">{{ $errors->first('payment_terms') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                        <label for="quote_no">Quote No:  (optional)</label>
                                                                        <input type="text" class="form-control"
                                                                            id="quote_no" name="quote_no"
                                                                            value="{{ $editDataNew->quote_no }}"
                                                                            placeholder="Enter Terms & Condition">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                        <button type="button" name="add" id="add"
                                                            class="btn btn-success">Add More</button>
                                                        <div style="margin-top:10px;">
                                                            <table class="table table-bordered" id="dynamicTable">
                                                                <tr>
                                                                    <th>Description </th>
                                                                    <th>HSN</th>
                                                                    <th>Part No</th>
                                                                    {{-- <th>Due Date</th> --}}
                                                                    <th>Quantity</th>
                                                                    <th>Unit</th>
                                                                    <th>Rate</th>
                                                                    <th>Discount</th>
                                                                    <th>Amount</th>
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
                                                                            <select class="form-control part_no_id mb-2 select2" name="part_no_id_{{ $key }}" id="part_no_id">
                                                                                <option value="" default>Select Description</option>
                                                                                @foreach ($dataOutputPartItem as $data)
                                                                                <option value="{{ $data['id'] }}"
                                                                                    {{ old('part_no_id', $editDataNew->part_no_id) == $data->id ? 'selected' : '' }}>
                                                                                    {{ $data->description }}
                                                                                </option>
                                                                            @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                name="hsn_id_{{ $key }}"
                                                                                value="{{ $editDataNew->hsn_name }}"
                                                                                placeholder="Enter hsn_id"
                                                                                class="form-control hsn_name" style="min-width:100px" disabled>

                                                                                <input type="hidden"
                                                                                name="hsn_id_{{ $key }}"
                                                                                value="{{ $editDataNew->hsn_id }}"
                                                                                placeholder="Enter hsn_id"
                                                                                class="form-control hsn_id" style="min-width:100px" >

                                                                                
                                                                           
                                                                            {{-- <select class="form-control hsn_id mb-2" name="hsn_id_{{ $key }}" id=""  style="min-width:100px">
                                                                                <option value="" default>Select HSN</option>
                                                                                @foreach ($dataOutputHSNMaster as $data)
                                                                                <option value="{{ $data['id'] }}"
                                                                                    {{ old('hsn_id', $editDataNew->hsn_id) == $data->id ? 'selected' : '' }}>
                                                                                    {{ $data->name }}
                                                                                </option>
                                                                            @endforeach
                                                                            </select> --}}
                                                                        </td>                                                                        
                                                                        <td>
                                                                            <input type="text"
                                                                                name="description_{{ $key }}"
                                                                                value="{{ $editDataNew->description }}"
                                                                                placeholder="Enter Description"
                                                                                class="form-control description" style="min-width:100px"/>
                                                                        </td>

                                                                        {{-- <td>
                                                                            <input type="date"
                                                                                name="discount_{{ $key }}"
                                                                                value="{{ $editDataNew->discount }}"
                                                                                placeholder="Enter Due Date"
                                                                                class="form-control discount" />
                                                                        </td> --}}

                                                                        <td>
                                                                            <input type="text"
                                                                                name="quantity_{{ $key }}"
                                                                                value="{{ $editDataNew->quantity }}"
                                                                                placeholder="Enter Quantity"
                                                                                class="form-control quantity" />
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control unit mb-2" name="unit_{{ $key }}" id="" style="min-width:100px">
                                                                                <option value="" default>Select Unit</option>
                                                                                @foreach ($dataOutputUnitMaster as $data)
                                                                                <option value="{{ $data['id'] }}"
                                                                                    {{ old('unit', $editDataNew->unit) == $data->id ? 'selected' : '' }}>
                                                                                    {{ $data->name }}
                                                                                </option>
                                                                            @endforeach
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                                name="rate_{{ $key }}"
                                                                                value="{{ $editDataNew->rate }}"
                                                                                placeholder="Enter Rate"
                                                                                class="form-control rate" />
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control discount" name="discount_{{ $key }}" id="discount_{{ $key }}" style="min-width:100px">
                                                                                <option value="0" {{ $editDataNew->discount == 0 ? 'selected' : '' }}>0 %</option>
                                                                                <option value="1" {{ $editDataNew->discount == 1 ? 'selected' : '' }}>1 %</option>
                                                                                <option value="2" {{ $editDataNew->discount == 2 ? 'selected' : '' }}>2 %</option>
                                                                                <option value="3" {{ $editDataNew->discount == 3 ? 'selected' : '' }}>3 %</option>
                                                                                <option value="4" {{ $editDataNew->discount == 4 ? 'selected' : '' }}>4 %</option>
                                                                                <option value="5" {{ $editDataNew->discount == 5 ? 'selected' : '' }}>5 %</option>
                                                                                <option value="6" {{ $editDataNew->discount == 6 ? 'selected' : '' }}>6 %</option>
                                                                                <option value="7" {{ $editDataNew->discount == 7 ? 'selected' : '' }}>7 %</option>
                                                                                <option value="8" {{ $editDataNew->discount == 8 ? 'selected' : '' }}>8 %</option>
                                                                                <option value="9" {{ $editDataNew->discount == 9 ? 'selected' : '' }}>9 %</option>
                                                                                <option value="10" {{ $editDataNew->discount == 10 ? 'selected' : '' }}>10 %</option>
                                                                                <option value="11" {{ $editDataNew->discount == 11 ? 'selected' : '' }}>11 %</option>
                                                                                <option value="12" {{ $editDataNew->discount == 12 ? 'selected' : '' }}>12 %</option>
                                                                                <option value="13" {{ $editDataNew->discount == 13 ? 'selected' : '' }}>13 %</option>
                                                                                <option value="14" {{ $editDataNew->discount == 14 ? 'selected' : '' }}>14 %</option>
                                                                                <option value="15" {{ $editDataNew->discount == 15 ? 'selected' : '' }}>15 %</option>
                                                                                <option value="16" {{ $editDataNew->discount == 16 ? 'selected' : '' }}>16 %</option>
                                                                                <option value="17" {{ $editDataNew->discount == 17 ? 'selected' : '' }}>17 %</option>
                                                                                <option value="18" {{ $editDataNew->discount == 18 ? 'selected' : '' }}>18 %</option>
                                                                                <option value="19" {{ $editDataNew->discount == 19 ? 'selected' : '' }}>19 %</option>
                                                                                <option value="20" {{ $editDataNew->discount == 20 ? 'selected' : '' }}>20 %</option>
                                                                                <option value="21" {{ $editDataNew->discount == 21 ? 'selected' : '' }}>21 %</option>
                                                                                <option value="22" {{ $editDataNew->discount == 22 ? 'selected' : '' }}>22 %</option>
                                                                                <option value="23" {{ $editDataNew->discount == 23 ? 'selected' : '' }}>23 %</option>
                                                                                <option value="24" {{ $editDataNew->discount == 24 ? 'selected' : '' }}>24 %</option>
                                                                                <option value="25" {{ $editDataNew->discount == 25 ? 'selected' : '' }}>25 %</option>
                                                                                <option value="26" {{ $editDataNew->discount == 26 ? 'selected' : '' }}>26 %</option>
                                                                                <option value="27" {{ $editDataNew->discount == 27 ? 'selected' : '' }}>27 %</option>
                                                                                <option value="28" {{ $editDataNew->discount == 28 ? 'selected' : '' }}>28 %</option>
                                                                                <option value="29" {{ $editDataNew->discount == 29 ? 'selected' : '' }}>29 %</option>
                                                                                <option value="30" {{ $editDataNew->discount == 30 ? 'selected' : '' }}>30 %</option>
                                                                                <option value="31" {{ $editDataNew->discount == 31 ? 'selected' : '' }}>31 %</option>
                                                                                <option value="32" {{ $editDataNew->discount == 32 ? 'selected' : '' }}>32 %</option>
                                                                                <option value="33" {{ $editDataNew->discount == 33 ? 'selected' : '' }}>33 %</option>
                                                                                <option value="34" {{ $editDataNew->discount == 34 ? 'selected' : '' }}>34 %</option>
                                                                                <option value="35" {{ $editDataNew->discount == 35 ? 'selected' : '' }}>35 %</option>
                                                                                <option value="36" {{ $editDataNew->discount == 36 ? 'selected' : '' }}>36 %</option>
                                                                                <option value="37" {{ $editDataNew->discount == 37 ? 'selected' : '' }}>37 %</option>
                                                                                <option value="38" {{ $editDataNew->discount == 38 ? 'selected' : '' }}>38 %</option>
                                                                                <option value="39" {{ $editDataNew->discount == 39 ? 'selected' : '' }}>39 %</option>
                                                                                <option value="40" {{ $editDataNew->discount == 40 ? 'selected' : '' }}>40 %</option>
                                                                                <option value="41" {{ $editDataNew->discount == 41 ? 'selected' : '' }}>41 %</option>
                                                                                <option value="42" {{ $editDataNew->discount == 42 ? 'selected' : '' }}>42 %</option>
                                                                                <option value="43" {{ $editDataNew->discount == 43 ? 'selected' : '' }}>43 %</option>
                                                                                <option value="44" {{ $editDataNew->discount == 44 ? 'selected' : '' }}>44 %</option>
                                                                                <option value="45" {{ $editDataNew->discount == 45 ? 'selected' : '' }}>45 %</option>
                                                                                <option value="46" {{ $editDataNew->discount == 46 ? 'selected' : '' }}>46 %</option>
                                                                                <option value="47" {{ $editDataNew->discount == 47 ? 'selected' : '' }}>47 %</option>
                                                                                <option value="48" {{ $editDataNew->discount == 48 ? 'selected' : '' }}>48 %</option>
                                                                                <option value="49" {{ $editDataNew->discount == 49 ? 'selected' : '' }}>49 %</option>
                                                                                <option value="50" {{ $editDataNew->discount == 50 ? 'selected' : '' }}>50 %</option>
                                                                            </select>
                                                                        </td>
                                                                        

                                                                        <td>
                                                                            <input type="text"
                                                                                name="amount_{{ $key }}"
                                                                                value="{{ $editDataNew->amount }}"
                                                                                placeholder="Enter Amount"
                                                                                class="form-control amount" />
                                                                        </td>

                                                                        <td>
                                                                            <a data-id="{{ $editDataNew->id }}"
                                                                                class="delete-btn btn btn-danger m-1" style="color: #fff;"
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
                                                                            <label for="transport_dispatch">Transport-Dispatch <span class="text-danger">*</span></label>
                                                                            <textarea class="form-control" name="transport_dispatch">@if (old('transport_dispatch')){{ old('transport_dispatch') }}@else{{ $editDataNew->transport_dispatch }}@endif</textarea>
                                                                        </div>

                                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                            <label for="note">Remark <span class="text-danger">*</span></label>
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


    <form method="POST" action="{{ route('delete-addmore') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> <!-- Include SweetAlert library -->
    <script>
        var jQuery321 = $.noConflict(true);
    </script>
    <script>
        $(document).ready(function() {
            var validator = $("#editDesignsForm").validate({
                ignore: [],
                rules: {
                    vendor_id: {
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
                    note: {
                        required: true,
                    },
                    transport_dispatch :{
                            required :true,
                        },
                    'part_no_id_0': {
                        required: true,
                    },
                    // 'description_0': {
                    //     required: true,
                    // },
                    'discount_0': {
                        required: true,
                    },
                    'quantity_0': {
                        required: true,
                        digits: true,
                    },
                    'unit_0': {
                        required: true,
                    },
                    'unit_0': {
                        required: true,
                        maxlength: 255
                    },
                    'rate_0': {
                        required: true,
                        number: true,
                    },
                    'amount_0': {
                        required: true,
                    },
                },
                messages: {
                    vendor: {
                        required: "Please Select the Vendor Company Name",
                    },
                    tax_id: {
                        required: "Please Enter the Tax",
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
                    //     required: "Please Enter the quote number",
                    //     number: "Please enter a valid number.",

                    // },
                    note: {
                        required: "Please Enter the Other Information",
                    },
                    transport_dispatch : {
                            required: "Please Enter the transport dispatch",
                        },
                    'part_no_id_0': {
                        required: "Please enter the Part Number",
                    },
                    // 'description_0': {
                    //     required: "Please enter the Description",
                    // },
                    'discount_0': {
                        required: "Please enter the Due Date",
                    },
                   
                    'quantity_0': {
                        required: "Please enter the Quantity",
                        digits: "Please enter only digits for Quantity",
                    },
                    'unit_0': {
                        required: "Please enter the Unit",
                    },
                    'hsn_id_0': {
                        required: "Please enter the hsn_id.",
                        maxlength: "hsn must be at most 255 characters long."
                    },
                    'rate_0': {
                        required: "Please enter the Rate",
                        number: "Please enter a valid number for Rate",
                    },
                    'amount_0': {
                        required: "Please enter the Amount",
                    },
                },
                errorPlacement: function(error, element) {
                    if (element.hasClass("part_no_id") ||
                        element.hasClass("discount") || 
                        element.hasClass("quantity") || element.hasClass("unit") || element.hasClass("rate") ||
                        element.hasClass("amount")) {
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
            '<select class="form-control part_no_id mb-2 select2" name="addmore[' + i + '][part_no_id]" id="" required>' +
                '<option value="" default>Select Description</option>' +
                '@foreach ($dataOutputPartItem as $data)' +
                    '<option value="{{ $data['id'] }}">{{ $data['description'] }}</option>' +
                '@endforeach' +
            '</select>' + 
'<td>'+'<input type="text" class="form-control hsn_name" placeholder=" " readonly />  <input type="hidden" name="addmore[' + i +'][hsn_id]" class="form-control hsn_id"  placeholder=" Amount" readonly /></td>'
                +
                    '<td><input type="text" class="form-control description" name="addmore[' + i +
                    '][description]" placeholder=" Description" /></td>' +
                    
                    '<td><input type="text" class="form-control quantity" name="addmore[' + i +
                        '][quantity]" placeholder=" Quantity" required /></td>' +
                        '<td>' +
                        '<select class="form-control unit mb-2" name="addmore[' + i + '][unit]" id="" required>' +
                '<option value="" default>Select Unit</option>' +
                '@foreach ($dataOutputUnitMaster as $data)' +
                    '<option value="{{ $data['id'] }}">{{ $data['name'] }}</option>' +
                '@endforeach' +
            '</select>'+
            '</td>' +
             '<td><input type="text" class="form-control rate" name="addmore[' + i +
                        '][rate]" placeholder=" rate" required /></td>'
                  +
                    ' <td><select class="form-control discount" name="addmore[' + i +'][discount] " ><option value="0" {{ $editDataNew->discount == 0 ? 'selected' : '' }}>0 %</option><option value="1" {{ $editDataNew->discount == 1 ? 'selected' : '' }}>1 %</option><option value="2" {{ $editDataNew->discount == 2 ? 'selected' : '' }}>2 %</option><option value="3" {{ $editDataNew->discount == 3 ? 'selected' : '' }}>3 %</option><option value="4" {{ $editDataNew->discount == 4 ? 'selected' : '' }}>4 %</option><option value="5" {{ $editDataNew->discount == 5 ? 'selected' : '' }}>5 %</option><option value="6" {{ $editDataNew->discount == 6 ? 'selected' : '' }}>6 %</option><option value="7" {{ $editDataNew->discount == 7 ? 'selected' : '' }}>7 %</option><option value="8" {{ $editDataNew->discount == 8 ? 'selected' : '' }}>8 %</option><option value="9" {{ $editDataNew->discount == 9 ? 'selected' : '' }}>9 %</option><option value="10" {{ $editDataNew->discount == 10 ? 'selected' : '' }}>10 %</option><option value="11" {{ $editDataNew->discount == 11 ? 'selected' : '' }}>11 %</option><option value="12" {{ $editDataNew->discount == 12 ? 'selected' : '' }}>12 %</option><option value="13" {{ $editDataNew->discount == 13 ? 'selected' : '' }}>13 %</option><option value="14" {{ $editDataNew->discount == 14 ? 'selected' : '' }}>14 %</option><option value="15" {{ $editDataNew->discount == 15 ? 'selected' : '' }}>15 %</option><option value="16" {{ $editDataNew->discount == 16 ? 'selected' : '' }}>16 %</option><option value="17" {{ $editDataNew->discount == 17 ? 'selected' : '' }}>17 %</option><option value="18" {{ $editDataNew->discount == 18 ? 'selected' : '' }}>18 %</option><option value="19" {{ $editDataNew->discount == 19 ? 'selected' : '' }}>19 %</option><option value="20" {{ $editDataNew->discount == 20 ? 'selected' : '' }}>20 %</option><option value="21" {{ $editDataNew->discount == 21 ? 'selected' : '' }}>21 %</option><option value="22" {{ $editDataNew->discount == 22 ? 'selected' : '' }}>22 %</option><option value="23" {{ $editDataNew->discount == 23 ? 'selected' : '' }}>23 %</option><option value="24" {{ $editDataNew->discount == 24 ? 'selected' : '' }}>24 %</option><option value="25" {{ $editDataNew->discount == 25 ? 'selected' : '' }}>25 %</option><option value="26" {{ $editDataNew->discount == 26 ? 'selected' : '' }}>26 %</option><option value="27" {{ $editDataNew->discount == 27 ? 'selected' : '' }}>27 %</option><option value="28" {{ $editDataNew->discount == 28 ? 'selected' : '' }}>28 %</option><option value="29" {{ $editDataNew->discount == 29 ? 'selected' : '' }}>29 %</option><option value="30" {{ $editDataNew->discount == 30 ? 'selected' : '' }}>30 %</option><option value="31" {{ $editDataNew->discount == 31 ? 'selected' : '' }}>31 %</option><option value="32" {{ $editDataNew->discount == 32 ? 'selected' : '' }}>32 %</option><option value="33" {{ $editDataNew->discount == 33 ? 'selected' : '' }}>33 %</option><option value="34" {{ $editDataNew->discount == 34 ? 'selected' : '' }}>34 %</option><option value="35" {{ $editDataNew->discount == 35 ? 'selected' : '' }}>35 %</option><option value="36" {{ $editDataNew->discount == 36 ? 'selected' : '' }}>36 %</option><option value="37" {{ $editDataNew->discount == 37 ? 'selected' : '' }}>37 %</option><option value="38" {{ $editDataNew->discount == 38 ? 'selected' : '' }}>38 %</option><option value="39" {{ $editDataNew->discount == 39 ? 'selected' : '' }}>39 %</option><option value="40" {{ $editDataNew->discount == 40 ? 'selected' : '' }}>40 %</option><option value="41" {{ $editDataNew->discount == 41 ? 'selected' : '' }}>41 %</option><option value="42" {{ $editDataNew->discount == 42 ? 'selected' : '' }}>42 %</option><option value="43" {{ $editDataNew->discount == 43 ? 'selected' : '' }}>43 %</option><option value="44" {{ $editDataNew->discount == 44 ? 'selected' : '' }}>44 %</option><option value="45" {{ $editDataNew->discount == 45 ? 'selected' : '' }}>45 %</option><option value="46" {{ $editDataNew->discount == 46 ? 'selected' : '' }}>46 %</option><option value="47" {{ $editDataNew->discount == 47 ? 'selected' : '' }}>47 %</option><option value="48" {{ $editDataNew->discount == 48 ? 'selected' : '' }}>48 %</option><option value="49" {{ $editDataNew->discount == 49 ? 'selected' : '' }}>49 %</option><option value="50" {{ $editDataNew->discount == 50 ? 'selected' : '' }}>50 %</option></select></td>'
                      +
                    '<td><input type="text" class="form-control amount" name="addmore[' + i +
                    '][amount]" placeholder=" Amount" readonly  required /></td>' +
                    '<td><a class="remove-tr delete-btn btn btn-danger m-1" title="Delete Tender"><i class="fas fa-archive" style="color: #fff;"></i></a></td>' +
                    '</tr>'
                );

                $("#dynamicTable").append(newRow);
                $('.select2').select2();
                // Reinitialize validation for the new row
                $('select[name="addmore[' + i + '][part_no_id]"]').rules("add", {
            required: true,
            messages: {
                required: "Please select the Part Number",
            }
        });


       

        $('select[name="addmore[' + i + '][hsn_id]"]').rules("add", {
            required: true,
            messages: {
                required: "Please select the hsn",
            }
        });
                // $('input[name="addmore[' + i + '][description]"]').rules("add", {
                //     required: true,
                //     messages: {
                //         required: "Please enter the Description",
                //     }
                // });
                $('input[name="addmore[' + i + '][discount]"]').rules("add", {
                    required: true,
                    messages: {
                        required: "Please enter the Due Date",
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
              
                $('select[name="addmore[' + i + '][unit]"]').rules("add", {
            required: true,
            messages: {
                required: "Please select the unit",
            }
        });

                $('input[name="addmore[' + i + '][rate]"]').rules("add", {
                    required: true,
                    number: true,
                    messages: {
                        required: "Please enter the Rate",
                        number: "Please enter a valid number for Rate",
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
                $('.discount').attr('min', today);
            }
            setMinDateForDueDates();

            $(document).on('focus', '.discount', function() {
                setMinDateForDueDates();
            });

            // $(document).on('keyup', '.quantity, .rate', function(e) {
            //     var currentRow = $(this).closest("tr");
            //     var quantity = currentRow.find('.quantity').val();
            //     var rate = currentRow.find('.rate').val();
            //     var amount = quantity * rate;
            //     currentRow.find('.amount').val(amount);
            // });
            $(document).on('keyup change', '.quantity, .rate, .discount', function() {
                                    var currentRow = $(this).closest("tr");

                                    // Fetch input values (convert to numbers and default to 0 if empty)
                                    var current_row_quantity = parseFloat(currentRow.find('.quantity').val()) || 0;
                                    var current_row_rate = parseFloat(currentRow.find('.rate').val()) || 0;
                                    var current_row_discount = parseFloat(currentRow.find('.discount').val()) || 0;

                                    // Calculate total price before discount
                                    var new_total_price = current_row_quantity * current_row_rate;

                                    // Calculate the discount amount
                                    var discount_amount = (new_total_price * current_row_discount) / 100;

                                    // Calculate final total amount after applying discount
                                    var final_total_amount = new_total_price - discount_amount;

                                    // Update the total_amount field (formatted to 2 decimal places)
                                    currentRow.find('.amount').val(final_total_amount);
                                });
            $('.delete-btn').click(function(e) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#delete_id").val($(this).attr("data-id"));
                        $("#deleteform").submit();
                    }
                });
            });
        });
    </script>
<script>
       $(document).ready(function() {
        var jQuery321 = $.noConflict(true);
     $(document).on('change', '.part_no_id', function() {
            // alert("hii");
    var partNoId = $(this).val(); // Get the selected part_no_id
    var currentRow = $(this).closest('tr'); // Get the current row

    if (partNoId) {
        // Make an AJAX request to fetch the HSN based on the part_no_id
        $.ajax({
            url: '{{ route('get-hsn-for-part') }}', // Use the Laravel route helper
            type: 'GET',
            data: { part_no_id: partNoId }, // Pass the part_no_id as a query parameter
            success: function(response) {
                console.log("HSN response:", response);

                if (response.part && response.part.length > 0) {
                    var hsnName = response.part[0].name;
                    var hsnId = response.part[0].id;

                    // Update the HSN inputs for the current row only
                    currentRow.find('.hsn_name').val(hsnName); // Set HSN name
                    currentRow.find('.hsn_id').val(hsnId); // Set HSN ID
                } else {
                    alert("HSN not found for the selected part.");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error: ", status, error);
                alert("Error fetching HSN. Please try again.");
            }
        });
    }
});
});
    </script>

@endsection
