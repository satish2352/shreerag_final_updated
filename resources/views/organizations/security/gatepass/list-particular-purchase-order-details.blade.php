<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .company-info,
        .purchase-info {
            width: 100%;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .purchase-info {
            font-size: 0.9em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        .totals {
            margin-top: 20px;
            float: right;
            width: 40%;
        }

        .totals table {
            border: none;
        }

        .totals th,
        .totals td {
            border: none;
        }

        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signatures div {
            width: 30%;
            text-align: center;
            border-top: 1px solid black;
            padding-top: 10px;
        }
    </style>

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1 style="display: flex; justify-content: center;">Purchase Order</h1>
                                
                            </div>
                        </div>
                        <div class="sparkline13-graph" style="border: 1px solid black; padding:20px;">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div >
                                    <div style="text-align: center; font-size: 20px; font-weight: bold;"> {{ $getOrganizationData->company_name }}</div>
                                    <div style="text-align: center;">
                                        {{ $getOrganizationData->mobile_number }}, {{ $getOrganizationData->email }}
                                    </div>
                                </div>
                        

                                <div class="company-info">
                                    <div>
                                        <p><strong>{{ $purchaseOrder->vendor_company_name }}</strong></p>
                                        <p>{{ $purchaseOrder->vendor_address }}</p>
                                    </div>
                                    <div>
                                        <p>PO Number: {{ $purchaseOrder->purchase_orders_id }}</p>
                                        <p>Date: {{ $purchaseOrder->po_date }}</p>
                                        <p>Date Ref No: {{ $purchaseOrder->date_ref }}</p>
                                        <p>Payment Terms: {{ $purchaseOrder->payment_terms }}</p>
                                    </div>
                                </div>

                                <p>Dear Sir, Please arrange to supply the following Material as per quantity, specification,
                                    and
                                    schedule mentioned below:</p>

                                <table>
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Part No.</th>
                                            <th>Description</th>
                                            <th>Due Date</th>
                                            <th>Quantity</th>
                                            {{-- <th>Rate</th>
                                            <th>Amount</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseOrderDetails as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $detail->part_no_id }}</td>
                                                <td>{{ $detail->description }}</td>
                                                <td>{{ $detail->due_date }}</td>
                                                <td>{{ $detail->quantity }} {{ $detail->unit }}</td>
                                                {{-- <td>{{ $detail->rate }}</td>
                                                <td>{{ $detail->amount }}</td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>


                            </div>
                            {{-- <a>
                                <button data-toggle="tooltip" onclick="printInvoice()" title="Accept Purchase Order"
                                    style="margin-top: 20px;">Print</button>
                            </a> --}}
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Print Button -->
   
    </div>

    <script>
        function printInvoice() {
            window.print();
        }
    </script>
@endsection