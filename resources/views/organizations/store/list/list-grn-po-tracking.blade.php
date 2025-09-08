@extends('admin.layouts.master')
@section('content')
    <style>
        .remaining_quantity {
            background-color: #8cd9b3 !important;
        }
    </style>
    <div class="row" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1> GRN Details</h1>
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
                                        <form action="{{ route('store-grn') }}" method="POST" id="addDesignsForm"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group-inner">
                                                <div class="container-fluid">
                                                    @if ($errors->any())
                                                        <div class="alert alert-danger">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                    <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                    @if (Session::has('success'))
                                                        <div class="alert alert-success text-center">
                                                            <a href="#" class="close" data-dismiss="alert"
                                                                aria-label="close">×</a>
                                                            <p>{{ Session::get('success') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="po_date">GRN No. :</label>
                                                        <input type="text" class="form-control" id="grn_no"
                                                            name="grn_no" placeholder=""
                                                            value="{{ $grn_data->grn_no_generate }}" readonly>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="grn_date">GRN Date:</label>
                                                        <input type="date" class="form-control" id="grn_date"
                                                            name="grn_date" placeholder="Enter GRN Date"
                                                            value="{{ $grn_data->grn_date }}" readonly>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="purchase_orders_id">PO No.:</label>
                                                        <input type="text" class="form-control" id="purchase_orders_id"
                                                            name="purchase_orders_id" placeholder="Enter Purchase No."
                                                            value="{{ $purchase_order_data->purchase_orders_id }}" readonly>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="po_date">PO Date :</label>
                                                        <input type="text" class="form-control" id="po_date"
                                                            name="po_date" placeholder="Enter PO Date"
                                                            value="{{ $purchase_order_data->created_at->format('d-m-Y') }}"
                                                            readonly>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="bill_no">Bill No. :</label>
                                                        <input type="text" class="form-control" id="bill_no"
                                                            name="bill_no" placeholder="" value="{{ $grn_data->bill_no }}"
                                                            readonly>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="bill_date">Bill Date :</label>
                                                        <input type="date" class="form-control" id="bill_date"
                                                            name="bill_date" placeholder=""
                                                            value="{{ $grn_data->bill_date }}" readonly>
                                                    </div>
                                                </div>
<div id="printableArea">
                                                <div style="margin-top:20px" >
                                                    <table class="table table-bordered" id="dynamicTable">
                                                        <tr>
                                                            <th>Description</th>
                                                            <th style="width: 100px;">Part No.</th>
                                                            <th style="width: 100px;">PO Quantity</th>
                                                            <th style="width: 70px;">Unit</th>
                                                            {{-- <th style="width: 100px;">HSN</th> --}}
                                                            <th style="width: 70px;">Rate</th>
                                                            <th style="width: 80px;">Discount</th>
                                                            <th style="width: 100px;">Actual Quantity</th>
                                                            <th style="width: 100px;">Accepted Quantity</th>
                                                            <th style="width: 100px;">Rejected Quantity</th>
                                                            <th style="width: 100px;">Balance Quantity</th>
                                                            {{-- <th>Action</th> --}}
                                                        </tr>
                                                        @foreach ($purchase_order_details_data as $item)
                                                            <tr>
                                                                <input type="hidden" name="addmore[0][edit_id]"
                                                                    placeholder="Enter Description" class="form-control"
                                                                    value="{{ $item->id }}" readonly />
                                                                <td><input type="text" name="addmore[0][description]"
                                                                        placeholder="Enter Description"
                                                                        class="form-control"
                                                                        value="{{ $item->part_description }}" readonly />
                                                                </td>
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[0][po_description]"
                                                                        placeholder="Enter part_number"
                                                                        class="form-control"
                                                                        value="{{ $item->po_description }}" readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[0][chalan_quantity]"
                                                                        placeholder="Enter Chalan Qty"
                                                                        class="form-control"
                                                                        value="{{ $item->max_quantity }}" readonly />
                                                                <td><input type="text" name="addmore[0][unit_name]"
                                                                        placeholder="Enter" class="form-control unit_name"
                                                                        value="{{ $item->unit_name }}" readonly />
                                                                </td>
                                                                <td><input type="text" name="addmore[0][po_rate]"
                                                                        placeholder="Enter" class="form-control rate"
                                                                        value="{{ $item->po_rate }}" readonly />
                                                                </td>
                                                                <td><input type="text" name="addmore[0][discount]"
                                                                        placeholder="Enter" class="form-control discount"
                                                                        value="{{ $item->po_discount }}%" readonly />
                                                                </td>

                                                                <td><input type="text"
                                                                        name="addmore[0][actual_quantity]"
                                                                        placeholder="Enter Actual Qty"
                                                                        class="form-control actual_quantity"
                                                                        value="{{ $item->sum_actual_quantity }}"
                                                                        readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[0][accepted_quantity]"
                                                                        placeholder="Enter Accepted Qty"
                                                                        class="form-control accepted_quantity"
                                                                        value="{{ $item->tracking_accepted_quantity }}"
                                                                        readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[0][rejected_quantity]"
                                                                        placeholder="Enter Rejected Qty"
                                                                        class="form-control rejected_quantity"
                                                                        value="{{ $item->tracking_rejected_quantity }}"
                                                                        readonly />
                                                                </td>
                                                                <td><input type="text"
                                                                        name="addmore[0][remaining_quantity]"
                                                                        placeholder="Balance Qty"
                                                                        value="{{ $item->remaining_quantity }}"
                                                                        class="form-control remaining_quantity" readonly />
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 remark-section">
                                                        <label for="remark">Remark:</label>
                                                        <textarea class="form-control" rows="3" type="text" class="form-control" id="remark" name="remark"
                                                            placeholder="Enter Remark" readonly>{{ $grn_data->grn_remark }}</textarea>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 signature-section" >
                                                        <label for="image">Signature:</label><br>
                                                        <img src="{{ Config::get('DocumentConstant.GRN_VIEW') }}{{ $grn_data->image }}"
                                                            style="width:150px; height:150px; background-color: aliceblue;"
                                                            alt=" No Signature" class="signature-section" />
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-center mb-5">
                                                         <button type="button" data-toggle="tooltip" onclick="printGRN()"
        class="btn btn-sm btn-bg-colour mt-3">Print </button>
                                                        {{-- <button data-toggle="tooltip" onclick="printGRN()"
                                                            class="btn btn-sm btn-bg-colour mt-3">
                                                            Print
                                                        </button> --}}
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
     <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
    function printGRN() {
        var contentToPrint = document.getElementById("printableArea").innerHTML;
        var printWindow = window.open('', '', 'height=800,width=1200');
        printWindow.document.write('<html><head><title>GRN Details</title>');
        printWindow.document.write(`
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 1px !important;
                font-size: 12px;
                color: #000;
                    border: 1px solid black;
                    padding: 20px;
            }
            h1 {
                text-align: center;
                margin-bottom: 20px;
                font-size: 20px;
                font-weight: bold;
                
            }
            /* Header info grid */
            .header-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 5px 20px;
                margin-bottom: 20px;
            }
            .header-grid div {
                font-size: 13px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
            }
            th, td {
                border: 1px solid #000;
                padding: 6px 8px;
                font-size: 12px;
                text-align: center;
                vertical-align: middle;
            }
            th:first-child, td:first-child {
                text-align: left;
                width: 30%;
            }
            input, textarea {
                border: none !important;
                background: transparent !important;
                font-size: 12px;
                width: 100%;
            }
            textarea {
                resize: none;
            }
            .remark-section {
                margin-top: 20px;
                  margin-top: 30px; /* more spacing above */
    margin-bottom: 10px; /* space below */
            }
            .remark-section label {
                font-weight: bold;
            }
            .signature-box {
                margin-top: 20px;
                width: 180px;
                height: 100px;
                border: 1px solid #000;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
            }
            img {
                max-width: 150px;
                height: auto;
            }
            /* Hide buttons in print */
            button, .btn, .no-print {
                display: none !important;
            }
            @media print {
                @page {
                    size: A4 landscape;
                    margin: 15mm;
                }
            }


.signature-box, .signature-section {
    margin-top: 10px; /* space above signature */
   
}
        </style>`);
        printWindow.document.write('</head><body>');
        
        // Replace top details into a grid format
        let headerHTML = `
        <h1>GRN Details</h1>
        <div class="header-grid">
            <div><strong>GRN No. :</strong> ${document.getElementById('grn_no').value}</div>
            <div><strong>GRN Date :</strong> ${document.getElementById('grn_date').value}</div>
            <div><strong>PO No. :</strong> ${document.getElementById('purchase_orders_id').value}</div>
            <div><strong>PO Date :</strong> ${document.getElementById('po_date').value}</div>
            <div><strong>Bill No. :</strong> ${document.getElementById('bill_no').value}</div>
            <div><strong>Bill Date :</strong> ${document.getElementById('bill_date').value}</div>
        </div>`;
        
        // Replace only once
        printWindow.document.write(headerHTML);
        printWindow.document.write(contentToPrint);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }
</script>



@endsection
