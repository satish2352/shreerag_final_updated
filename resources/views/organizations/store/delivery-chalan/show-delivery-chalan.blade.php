<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
<style>
    .form-control {
        border: 2px solid #ced4da;
        border-radius: 4px;
    }

    .error {
        color: red;
    }

    .no-print {
        display: none !important;
    }

    body {
        font-size: 12px;
    }

    .selfProfile {
        float: left;
        width: 50%;
    }

    .imgLogo {
        float: left;
        width: 30%;
    }

    .profile {
        /* float: right; */
        /* width: 70%; */
    }

    .data {
        float: right;
        width: 50%;
    }

    .bordersBottom {
        border-top: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .borders {
        border: 1px solid black;
    }

    .no-border {
        border: none !important;
    }

    .invoice-payments {
        float: left;
        width: 60%;
    }

    .tops {
        margin-top: -63px;
    }

    table th,
    table td {
        border: 1px solid black;
        padding: 8px;
        /* Optional: add padding for better readability */
    }

    table th {
        background-color: #f2f2f2;
        /* Optional: add background color for table header */
    }
    img{
        max-width: 90px !important;
    }
    @media print {
    img {
        width: 10px !important;
        height: auto !important;
    }
    /* Center text for print */
    .img-size {
        width: 100px;
    }
    .delivery-challan {
        text-align: center !important;
        font-size: 20px;
        font-weight: bold;
    }
}


    /* table tr td {
                                border: 1px solid red;
                            } */
</style>

        <div class="data-table-area mg-tb-15" id="printableArea">
            <div class="container-fluid">
                <div class="row">
        
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline13-list" >
                          
                                <div style="border: 1px solid black; padding: 10px; width: 100%;">
                                    <!-- Header Section -->
                                    <div style="display: flex; align-items: center; padding-bottom: 10px; border: 1px solid black;">
                                        <!-- Left Side: Logo -->
                                        <div style="width: 20%; text-align: left; padding-left: 10px;">
                                            <img src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') }}{{ $getOrganizationData->image }}" 
                                                 alt="{{ strip_tags($getOrganizationData['company_name']) }} Image" 
                                                 style="width: 100px;" />
                                        </div>
                                    
                                        <!-- Center: Delivery Challan Title -->
                                        <div style="width: 80%; text-align: center;">
                                            <div style="font-size: 20px; font-weight: bold; text-align: center;">
                                                DELIVERY CHALLAN
                                            </div>
                                            <div style="font-size: 14px;">
                                                (In Case of goods sent for the Job Work Under Section 143 of GST Act)
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- <div style=" padding-bottom: 10px; display: flex; align-items: center;">
                                        <!-- Left Side: Image -->
                                        <div style="width:20%;">
                                        <img class="img-size"
                                        src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') }}{{ $getOrganizationData->image }}"
                                        alt="{{ strip_tags($getOrganizationData['company_name']) }} Image"
                                        style="width: 100px; padding:10px;" /> <!-- Inline style here may override print styles -->
                                        </div>
                                    <div style="width:80%; justify-content: center; align-items: center; text-align: center;">
                                        <!-- Center: Delivery Chalan Text -->
                                        <div style="flex: 2; ">
                                       <div class="text-align:center;">
                                        <span style="text-align: center; font-size: 20px; font-weight: bold;">
                                            DELIVERY CHALLAN</br>
                                            </span>
                                       </div>
                                            <span>(In Case of goods sent for the Job Work Under Section 143 of GST Act) </span>
                                        </div>
                                    </div>
                                    </div> --}}
                                <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;">
                                        <tr>
                                            <td style="width: 50%; padding: 10px; border: 1px solid black; padding: 5px">
                                                <strong><p style="font-size: 20px; margin-bottom:0px;">{{ $getOrganizationData->company_name }}</p></strong><br>
                                                {{ $getOrganizationData->address }}</br>
                                                {{ $getOrganizationData->email }},<br/>
                                                 {{ $getOrganizationData->mobile_number }},</br>
                                                <strong>GST No.:   {{ $getOrganizationData->mobile_number }},</strong>
                                            </td>
                                            <td style="width: 50%; vertical-align: top; padding-top:0px; padding-left:0px; padding-right:0px; border: 1px solid black; ">
                                                {{-- <div style="border-bottom: 1px solid black; padding: 10px; width: 100%; display: flex; justify-content: space-between;">
                                                    <span><strong>Sr. No.: </strong></span>
                                                    <span><strong>Date: </strong></span>
                                                </div> --}}
                                                <div style="border-bottom: 1px solid black; padding: 10px; width: 100%; display: flex; justify-content: space-between;">
                                                    <span><strong>DC No. : </strong> {{ $showData['purchaseOrder']->dc_number }}</span>
                                                    <span style="padding-right: 20px;"><strong>Date: </strong> {{ $showData['purchaseOrder']->dc_date }}</span>
                                                </div>
                                                
                                                <p style="font-size: 20px; margin-bottom:0px; padding-left:10px;"><strong>To : {{ $showData['purchaseOrder']->vendor_company_name }}</strong></p>
                                               <p style="padding-left:10px;  margin-bottom:0px;"> {{ $showData['purchaseOrder']->vendor_address }}</p>
                                                <div style="margin-top: 10px; padding-left:10px;  margin-bottom:0px;">
                                                   <strong> GST No.:  {{ $showData['purchaseOrder']->gst_no }}</strong>
                                                </div>
                                            </td>
                                        </tr>
                                      </table>
                                    {{-- </table> --}}
                                    <!-- Table for PO Details -->
                                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;">
                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid black; padding: 5px;">Sr. No.</th>
                                                <th style="border: 1px solid black; padding: 5px;">Part Item.</th>
                                                <th style="border: 1px solid black; padding: 5px;">Particulars</th>
                                                <th style="border: 1px solid black; padding: 5px;">Process</th>
                                                {{-- <th style="border: 1px solid black; padding: 5px;">Unit</th> --}}
                                                <th style="border: 1px solid black; padding: 5px;">HSN</th>
                                              
                                                <th style="border: 1px solid black; padding: 5px;">Size</th>
                                                <th style="border: 1px solid black; padding: 5px;">Quantity</th>
                                                <th style="border: 1px solid black; padding: 5px;">Rate</th>
                                                <th  style="border: 1px solid black; padding: 5px;">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($showData['purchaseOrderDetails'] as $index => $item)
                                            <tr>
                                                <td style="border: 1px solid black; padding: 5px; text-align: center;">{{ $index + 1 }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $item->part_number }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $item->description }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $item->process_name }}</td>
                                                    <td style="border: 1px solid black; padding: 5px;">{{ $item->hsn_name }}</td>

                                                <td style="border: 1px solid black; padding: 5px; text-align: left;">{{ $item->size }}</td>
                                                <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $item->quantity }} {{ $item->name }}</td>
                                                <td style="border: 1px solid black; padding: 5px; text-align: left;">{{ $item->rate }}</td>

                                                <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $item->amount }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot style="border: 1px solid black;">
                                            <tr>
                                                <td class="no-border" colspan="2">
                                                    {{-- <strong>Terms & Condition :- {{ $getOrganizationData->terms_condition }}</strong> --}}
                                                    <strong>Remark :- {{ $showData['purchaseOrder']->remark }}</strong>
                                                </td>
                                                <td class="no-border" colspan="4"></td>
                                                <td style="border: 1px solid black;"  colspan="2">Total</td>
                                                <td class="text-right"  style="border: 1px solid black;"  colspan="2">{{ $showData['purchaseOrderDetails']->sum('amount') }} </td>
                                                </tr>
                                                
                                            <tr>
                                                <?php
                                                // dd($showData);
                                                // die();
                                                ?>
                                                @php
                                                    // Get the total amount from purchase order details
                                                    $totalAmount = $showData['purchaseOrderDetails']->sum('amount');

                                                    // Get tax percentage and type
                                                    $taxPercentage = $showData['purchaseOrder']->tax_number; // The tax percentage, e.g., 1%, 5%, etc.
                                                    $taxType = $showData['purchaseOrder']->tax_type; // The tax type (GST, SGST, etc.)

                                                    // Calculate the tax amount
                                                    $taxAmount = ($totalAmount * $taxPercentage) / 100;

                                                    // Calculate the final amount including tax
                                                    $finalAmount = $totalAmount + $taxAmount;
                                                @endphp
                                                {{-- <td class="no-border" colspan="2">
                                                   <div><p style="font-size: 15px;"> <strong>Vehicle No.:-{{ $showData['purchaseOrder']->vehicle_number }}</strong> </p></div>
                                                </td> --}}
                                                <td class="no-border" colspan="6"></td>
                                                <td colspan="2"  style="border: 1px solid black;" class="text-left">
                                                 {{ $taxType }} {{ $showData['purchaseOrder']->tax_number }}% 
                                                </td>
                                                <td colspan="2"  style="border: 1px solid black;" class="text-right">
                                                    {{ number_format($taxAmount, 2) }}
                                                </td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid black;">
                                                <td class="no-border" colspan="6">
                                                    <div><p style="font-size: 15px;"> <strong>Vehicle No.:-{{ $showData['purchaseOrder']->vehicle_number }}</strong> </p></div>
                                                 </td>
                                               
                                                <td colspan="2"  style="border: 1px solid black;"><strong>Grand Total</strong></td>
                                                <td class="text-right"  style="border: 1px solid black;" colspan="2">
                                                    <strong>{{ number_format($finalAmount, 2) }}</strong>
                                                    {{-- <strong>{{ $showData['purchaseOrderDetails']->sum('amount') - $showData['purchaseOrderDetails']->sum('amount') * ($showData['purchaseOrder']->discount / 100) + ($showData['purchaseOrderDetails']->sum('amount') - $showData['purchaseOrderDetails']->sum('amount') * ($showData['purchaseOrder']->discount / 100)) * 0.09 * 2 }}</strong> --}}
                                                </td>
                                            </tr>
                                            <tr style="padding-top:10px">
                                                <td colspan="5" class="no-border" ></td>
                                                <td colspan="4" class="no-border" style="padding-bottom: 40px;">
                                                    <div style="text-align: center; font-size:18px;"><strong>For: {{ $getOrganizationData->company_name }}</strong></div>
                                                </td>
                                            </tr>
                                            <tr style="padding-bottom:10px">
                                             
                                                <td class="no-border" colspan="5"><strong>Signature of Processor/Job Worker</strong></td>
                                                <td class="no-border" style="padding-left:24px;" colspan="4">
                                                   <div style="text-align: center; "> <strong>(Authorized Signatory)</strong></div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- Print Button -->
                                  
                                </div>
                                 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a style="padding-bottom: 100px; padding-left:20px;">
            <button data-toggle="tooltip" onclick="printInvoice()" title="Delivery Chalan" style="margin-top: 20px;">Print</button>
        </a>
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