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

        /* table,
        th,
        td {
            border: 1px solid black;
        } */
        .custom-datatable-overright table tbody tr td{
            padding: 0px !important;
            margin: 0px !important;
        }
        th,
        td {
            /* padding: 8px; */
            padding: 0px !important;
            margin: 0px !important;
            text-align: left;
        }

        .totals {
            /* margin-top: 20px; */
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

    <div class="data-table-area mg-tb-15" id="printableArea">
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

                                <table style="border-top: 1px solid black; border-right: 1px solid black; ">
                                    <thead>
                                        <tr  style="border-left: 1px solid black; border-bottom: 1px solid black;">
                                            <th  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">Sr. No.</span></th>
                                            <th  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">Part No.</span></th>
                                            <th  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">Description</span></th>
                                            <th  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">Due Date</span></th>
                                            <th  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">Quantity</span></th>
                                            <th style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">Rate</span></th>
                                            {{-- <th>Rate</th>
                                            <th>Amount</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchaseOrderDetails as $index => $detail)
                                            <tr  style="border-left: 1px solid black; ">
                                                <td  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">{{ $index + 1 }}</span></td>
                                                <td  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">{{ $detail->part_name }}</span></td>
                                                <td  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">{{ $detail->description }}</span></td>
                                                <td  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">{{ $detail->due_date }}</span></td>
                                                <td  style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">{{ $detail->quantity }} {{ $detail->unit_name }}</span></td>
                                                <td style="border-left: 1px solid black; border-bottom: 1px solid black;"><span style="padding: 5px;">{{ $detail->rate }}</span></td>
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

    <a style="padding-bottom: 100px; padding-left:20px;">
        <button data-toggle="tooltip" onclick="printInvoice()"  style="margin-top: 20px;">Print</button>
    </a>


    <!-- Print Button -->
   
    </div>
  
    <script>
            // function printInvoice() {
            //     window.print();
            // }
            function printInvoice() {
    // Get the content you want to print
    var contentToPrint = document.getElementById("printableArea").innerHTML;

    // Open a new window
    var printWindow = window.open('', '', 'height=600,width=800');

    // Write the content to the new window with proper styles
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 0; padding: 50px; }'); // Add padding to body
    printWindow.document.write('#printableArea { width: 100%; overflow: hidden; }'); // Ensure full width of content
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(contentToPrint);
    printWindow.document.write('</body></html>');

    // Close the document to render
    printWindow.document.close();
    printWindow.focus();

    // Trigger the print dialog
    printWindow.print();

    // Close the print window after printing
    printWindow.close();
}



        </script>
@endsection
