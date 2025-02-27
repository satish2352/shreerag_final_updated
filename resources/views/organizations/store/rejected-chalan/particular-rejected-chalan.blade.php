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
        }

        table th {
            background-color: #f2f2f2;
        }

        .sparkline13-list {
            margin-top: 60px !important;
            margin-bottom: 10px !important;
        }

        @media print {
            img {
                width: 10px !important;
                height: auto !important;
            }
          
            .img-size {
                width: 100px;
            }

            .delivery-challan {
                text-align: center !important;
                font-size: 20px;
                font-weight: bold;
            }
        }
        .signImage{
    display: flex;
    justify-content: center;
    padding-bottom: 10px;
   }
    </style>

    <div class="data-table-area mg-tb-15" id="printableArea">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">

                        <div style="border: 1px solid black; padding: 10px; width: 100%;">
                            <!-- Header Section -->
                            <div style="border-bottom: 1px solid black; padding-bottom: 10px;">
                                <!-- Flex Container for Logo and Title -->
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <!-- Logo Section (Left Aligned) -->
                                    <div style="width: 20%; text-align: left;">
                                        <img src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') }}{{ $getOrganizationData->image }}"
                                            alt="No Image" style="width: 100px; padding: 10px;" />
                                    </div>
                                    <!-- Title Section (Centered) -->
                                    <div style="width: 60%; text-align: center;font-family: 'Font Awesome 5 Free ">
                                        <span style="font-size: 20px; font-weight: bold;">REJECTED CHALLAN</span>
                                        <div style="text-align: center; margin-top: 5px;">
                                            {{-- <span style="font-size: 11px;"> (In Case of goods sent for the Job Work Under
                                                Section 143 of GST Act)</span> --}}
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
                                        @foreach($all_gatepass as $gatepass)
                                        <div class="top-spacing" style="padding: 2px; width: 100%; display: flex; justify-content: space-between;">
                                            <span><strong>GRN No. :</strong> {{ $gatepass->grn_no }}</span>
                                        </div>
                                        <div class="top-spacing" style="padding: 2px; width: 100%; display: flex; justify-content: space-between;">
                                            <span><strong>PO No. :</strong> {{ $gatepass->purchase_orders_id }}</span>
                                        </div>
                                        
                                        <div class="top-spacing" style="padding: 2px; width: 100%; display: flex; justify-content: space-between;">
                                            <span style="padding-right: 20px;">
                                                <strong>PO Date:</strong> {{ $gatepass->po_date }}
                                            </span>
                                        </div>
                                        <div class="top-spacing" style="padding: 2px; width: 100%; display: flex; justify-content: space-between;">
                                            <span style="padding-right: 20px;">
                                                <strong>GRN Date:</strong> {{ $gatepass->grn_date }}
                                            </span>
                                        </div>
                                        <div class="top-spacing" style="padding: 2px; width: 100%; display: flex; justify-content: space-between;">
                                            <span><strong>Chalan No. :</strong> {{ $gatepass->chalan_no }}</span>
                                        </div>


                                        <div class="top-spacing" style="padding: 2px; width: 100%; display: flex; justify-content: space-between;">
                                            <span><strong>Customer Name. :</strong> {{ $gatepass->gatepass_name }}</span>
                                        </div>
                                        <div class="top-spacing" style="padding: 2px; width: 100%; display: flex; justify-content: space-between;">
                                            <span style="padding-right: 20px;">
                                                <strong>Reference No:</strong> {{ $gatepass->reference_no }}
                                            </span>
                                        </div>
                                    @endforeach
                                    </td>
                                </tr>
                            </table>
                            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;">
                                <thead>
                                    <tr class="font-size-delivery" style="font-family: 'Font Awesome 5 Free">
                                        <th style="border: 1px solid black; padding: 5px;">No.</th>
                                        <th style="border: 1px solid black; padding: 5px; width:70px;">Description</th>
                                        <th style="border: 1px solid black; padding: 5px;">Chalan Quantity</th>
                                        <th style="border: 1px solid black; padding: 5px;">Actual Quantity</th>
                                        {{-- <th style="border: 1px solid black; padding: 5px;">Unit</th> --}}
                                        <th style="border: 1px solid black; padding: 5px;">Accepted Quantity</th>

                                        <th style="border: 1px solid black; padding: 5px;">Rejected Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchase_order_details_data as $index => $item)
                                        <tr class="font-size-delivery" style="font-family: 'Font Awesome 5 Free">
                                            <td style="border: 1px solid black; padding: 5px; text-align: center;">
                                                {{ $index + 1 }}</td>
                                                <td style="border: 1px solid black; padding: 5px; text-align: left; width:250px;">
                                                    {{ $item->part_description }}</td>
                                                <td style="border: 1px solid black; padding: 5px; text-align: left;">
                                                    {{ $item->max_quantity }}</td>
                                            <td style="border: 1px solid black; padding: 5px;">{{ $item->sum_actual_quantity }}
                                            </td>
                                            <td style="border: 1px solid black; padding: 5px;">{{ $item->tracking_accepted_quantity }}
                                            </td>
                                            <td style="border: 1px solid black; padding: 5px;">{{ $item->tracking_rejected_quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="border: 1px solid black;">
                                    <tr style="padding-top:10px font-family: 'Font Awesome 5 Free">
                                        <td colspan="2" class="no-border">
                                        </td>
                                        <td colspan="7" class="no-border" style="padding-bottom: 40px;">
                                            <div class="company-name-size" style="display: flex; justify-content: end; font-size:18px; text-transform: uppercase; font-family: 'Font Awesome 5 Free'; padding-top:20px;"><strong>For:
                                            <span class="company-name-size"> {{ $getOrganizationData->company_name }}</span></strong></div>
                                        </td>
                                    </tr>
                                    <tr style="height:80px; font-family: 'Font Awesome 5 Free">

                                        <td class="no-border" colspan="3"><strong>Signature of Receiver/Job
                                                Worker</strong></td>
                                        <td class="no-border" style="padding-left:24px;" colspan="5">
                                            <div class="signImage">
                                                {{-- <img style="max-width:70px; max-height:70px; margin:50px 0px 0px 20px;" src="{{ Config::get('DocumentConstant.DELIVERY_CHALAN_VIEW') . $showData['purchaseOrder']->image}}" alt="{{ strip_tags($showData['purchaseOrder']->image) }} Image" /> --}}
                                            </div>
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
        <button data-toggle="tooltip" onclick="printInvoice()" title="Rejected Chalan" style="margin-top: 20px;"
            class="accept-btn">Print</button>
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
