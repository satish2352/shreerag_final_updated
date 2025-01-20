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
    .sparkline13-list {
            margin-top: 60px !important;
            margin-bottom: 10px !important;
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
   .signImage{
    display: flex;
    justify-content: center;
    padding-bottom: 10px;
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
                                     <div style="border-bottom: 1px solid black; padding-bottom: 10px;">
                                        <!-- Flex Container for Logo and Title -->
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <!-- Logo Section (Left Aligned) -->
                                            <div style="width: 20%; text-align: left;">
                                                <img 
                                                    src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') }}{{ $getOrganizationData->image }}" 
                                                    alt="No Image" 
                                                    style="width: 100px; padding: 10px;" 
                                                />
                                            </div>
                                            <!-- Title Section (Centered) -->
                                            <div style="width: 60%; text-align: center; ">
                                                <span style="font-size: 20px; font-weight: bold;">RETURNABLE DELIVERY CHALLAN</span>
                                                <div style="text-align: center; margin-top: 5px;">
                                                    <span style="font-size: 11px;"> (In Case of goods sent for the Job Work Under Section 143 of GST Act)</span>
                                                </div>
                                            </div>
                                            <!-- Empty Space to Maintain Flexbox Alignment -->
                                            <div style="width: 20%;"></div>
                                        </div>
                                        <!-- Details Section (Centered) -->
                                    </div>

                                    <table
                                    style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px; font-family: 'Font Awesome 5 Free">
                                    <tr>
                                        <td style="width: 56%; padding: 10px; border: 1px solid black; padding: 5px">
                                            <strong>
                                                <p class="company-name-size" style="font-size: 20px; margin-bottom:0px;text-transform: uppercase;">
                                                    {{ $getOrganizationData->company_name }}</p>
                                            </strong>
                                           <p class="font-size-delivery"> {{ $getOrganizationData->address }}</br>
                                            CIN No. : {{ $getOrganizationData->cin_number }}</br>
                                            Email Id : {{ $getOrganizationData->email }},<br />
                                            <strong>Mo No. : {{ $getOrganizationData->mobile_number }},</strong><br>
                                            {{-- {{ $getOrganizationData->mobile_number }},</br> --}}
                                            <strong>GST No.: {{ $getOrganizationData->gst_no }},</strong></p>
                                        </td>
                                        <td
                                            style="width: 44%; vertical-align: top; padding-top:0px; padding-left:0px; padding-right:0px; border: 1px solid black; ">
                                            <div class="top-spacing"
                                                style=" padding: 10px; width: 100%; display: flex; justify-content: space-between;">
                                                <span><strong>DC No. : </strong>
                                                    {{ $showData['purchaseOrder']->dc_number }}</span>
                                                <span style="padding-right: 20px;">
                                                    <strong>Date: </strong>
                                                    {{ $showData['purchaseOrder']->dc_date ? \Carbon\Carbon::parse($showData['purchaseOrder']->dc_date)->format('Y-m-d') : 'N/A' }}
                                                </span>
                                            </div>
    
                                            <p class="company-name-size" style="font-size: 20px; margin-bottom:0px; padding:20px 5px 0px 10px; text-transform: uppercase; border-top: 1px solid black;"><strong>To :
                                                    {{ $showData['purchaseOrder']->vendor_company_name }}</strong></p>
                                            <p class="font-size-delivery" style="padding-left:10px;  margin-bottom:0px;">
                                                {{ $showData['purchaseOrder']->vendor_address }}</p>
                                                <span class="font-size-delivery" style="padding-left:10px;">Email Id : {{$showData['purchaseOrder']->vendor_email}}</span><br>
                                                <strong> <span class="font-size-delivery" style="padding-left:10px;">Mo No. : {{$showData['purchaseOrder']->contact_no}}</span> <br>
                                                    <span class="font-size-delivery" style="padding-left:10px;"> GST No.: {{ $showData['purchaseOrder']->gst_no }}</span></strong>
                                        </td>
                                    </tr>
                                </table>
                                <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;">
                                    <thead>
                                        <tr class="font-size-delivery" style="font-family: 'Font Awesome 5 Free">
                                            <th style="border: 1px solid black; padding: 5px;">No.</th>
                                            <th style="border: 1px solid black; padding: 5px; width:70px;">Part Item.</th>
                                            <th style="border: 1px solid black; padding: 5px;">Particulars</th>
                                            <th style="border: 1px solid black; padding: 5px;">Process</th>
                                            {{-- <th style="border: 1px solid black; padding: 5px;">Unit</th> --}}
                                            <th style="border: 1px solid black; padding: 5px;">HSN</th>
    
                                            <th style="border: 1px solid black; padding: 5px;">Size</th>
                                            <th style="border: 1px solid black; padding: 5px;">Quantity</th>
                                            <th style="border: 1px solid black; padding: 5px;">Rate</th>
                                            <th style="border: 1px solid black; padding: 5px;">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($showData['purchaseOrderDetails'] as $index => $item)
                                            <tr class="font-size-delivery" style="font-family: 'Font Awesome 5 Free">
                                                <td style="border: 1px solid black; padding: 5px; text-align: center;">
                                                    {{ $index + 1 }}</td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $item->part_number }}
                                                </td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $item->description }}
                                                </td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $item->process_name }}
                                                </td>
                                                <td style="border: 1px solid black; padding: 5px;">{{ $item->hsn_name }}</td>
    
                                                <td style="border: 1px solid black; padding: 5px; text-align: left;">
                                                    {{ $item->size }}</td>
                                                <td style="border: 1px solid black; padding: 5px; text-align: right;">
                                                    {{ $item->quantity }} {{ $item->name }}</td>
                                                <td style="border: 1px solid black; padding: 5px; text-align: left;">
                                                    {{ $item->rate }}</td>
    
                                                <td style="border: 1px solid black; padding: 5px; text-align: right;">
                                                    {{ $item->amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot style="border: 1px solid black;">
                                        <tr style="font-family: 'Font Awesome 5 Free">
                                            <td class="no-border" colspan="3">
                                                {{-- <strong>Terms & Condition :- {{ $getOrganizationData->terms_condition }}</strong> --}}
                                                <strong>Remark :- {{ $showData['purchaseOrder']->remark }}</strong>
                                            </td>
                                            <td class="no-border" colspan="3"></td>
                                            <td style="border: 1px solid black;" colspan="2">Total</td>
                                            <td class="text-right" style="border: 1px solid black;" colspan="2">
                                                {{ $showData['purchaseOrderDetails']->sum('amount') }} </td>
                                        </tr>
    
                                        <tr style="font-family: 'Font Awesome 5 Free">
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
                                            <td colspan="2" style="border: 1px solid black;" class="text-left">
                                                {{ $taxType }} {{ $showData['purchaseOrder']->tax_number }}%
                                            </td>
                                            <td colspan="2" style="border: 1px solid black;" class="text-right">
                                                {{ number_format($taxAmount, 2) }}
                                            </td>
                                        </tr>
                                        <tr style="border-bottom: 1px solid black; font-family: 'Font Awesome 5 Free">
                                            <td class="no-border" colspan="6">
                                                <div>
                                                    <p style="font-size: 15px;"> <strong>Vehicle
                                                            No.:-{{ $showData['purchaseOrder']->vehicle_number }}</strong> </p>
                                                </div>
                                            </td>
    
                                            <td colspan="2" style="border: 1px solid black;"><strong>Grand Total</strong>
                                            </td>
                                            <td class="text-right" style="border: 1px solid black;" colspan="2">
                                                <strong>{{ number_format($finalAmount, 2) }}</strong>
                                                {{-- <strong>{{ $showData['purchaseOrderDetails']->sum('amount') - $showData['purchaseOrderDetails']->sum('amount') * ($showData['purchaseOrder']->discount / 100) + ($showData['purchaseOrderDetails']->sum('amount') - $showData['purchaseOrderDetails']->sum('amount') * ($showData['purchaseOrder']->discount / 100)) * 0.09 * 2 }}</strong> --}}
                                            </td>
                                        </tr>
                                        <tr style="padding-top:10px font-family: 'Font Awesome 5 Free">
                                            <td colspan="2" class="no-border">
                                                
                                            </td>
                                            <td colspan="8" class="no-border" style="padding-bottom: 40px;">
                                                <div class="company-name-size" style="display: flex; justify-content: end; font-size:18px; text-transform: uppercase; font-family: 'Font Awesome 5 Free'; padding-top:20px;"><strong>For:
                                                       <span class="company-name-size"> {{ $getOrganizationData->company_name }}</span></strong></div>
                                            </td>
                                        </tr>
                                        <tr style="height:80px; font-family: 'Font Awesome 5 Free">
    
                                            <td class="no-border" colspan="3"><strong>Signature of Receiver/Job
                                                    Worker</strong></td>
                                            <td class="no-border " style="padding-left:24px;" colspan="5">
                                               {{-- <div class="signImage">
                                                <img style="max-width:70px; max-height:70px; text-align: center;" src="{{ Config::get('DocumentConstant.RETURNABLE_CHALAN_VIEW') . $showData['purchaseOrder']->image}}" alt="{{ strip_tags($showData['purchaseOrder']->image) }} Image" />
                                               </div> --}}
                                               
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
            <button data-toggle="tooltip" class="accept-btn" onclick="printInvoice()" title="Accept Purchase Order" style="margin-top: 20px;">Print</button>
        </a>
        <script>
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
                printWindow.document.write('.company-name-size { font-size: 15px !important; }'); // Corrected CSS for company-name-size
                printWindow.document.write('.font-size-delivery { font-size: 14px !important; }'); // Corrected CSS for company-name-size     
                printWindow.document.write('table td {padding: 8px;}'); // Corrected CSS for company-name-size     
                printWindow.document.write('.top-spacing {padding: 0px; width: 100%; display: flex; justify-content: space-between;}'); // Corrected CSS for company-name-size     
                printWindow.document.write('.signImage{display: flex; justify-content: center; padding-bottom: 10px; }'); // Corrected CSS for company-name-size     

                
                   
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