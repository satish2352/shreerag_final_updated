<style>
    * {
        box-sizing: border-box;
    }


    .header-no-border {
        border: none !important;
    }


    .font-family-page {
        font-family: 'Play', sans-serif !important;
    }

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

    table {
        width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
    }

    thead {
        display: table-header-group !important;
    }

    tfoot {
        display: table-footer-group !important;
    }

    @isset($is_pdf)
    thead {
        display: table-row-group !important;
    }
    tfoot {
        display: table-row-group !important;
    }
    @endisset

    tr {
        page-break-inside: avoid !important;
    }

    td,
    th {
        font-size: 14px !important;
        border: 1px solid #000;
        word-break: break-word !important;
        padding: 4px !important;
    }

    .description-column {
        font-size: 14px !important;
        word-break: break-word !important;
        white-space: normal !important;
    }

    .po-part-no-column {
        font-size: 13px !important;
        line-height: 1.25 !important;
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
    }


    p {
        font-size: 16px !important;
    }

    .sparkline13-list-new {
        background-color: #fff;
        padding: 22px;
        margin-top: 72px;
        /* margin-top: 0 !important; */
        margin-bottom: 80px;
    }

    @media screen {
        .print-button {
            display: inline-block;
        }
    }

    @media print {
        .biz-product-info {
            display: none !important;
        }
    }

    .span {
        font-family: sans-serif !important;
    }
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list-new border-page" id="printableArea"
                    style="padding: 10px; box-sizing: border-box;">
                    @isset($businessData, $businessDetailsData)
                    <div class="biz-product-info" style="margin-bottom: 8px; padding: 8px 12px; background:#f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
                        <table style="width:100%; border:none; margin:0;">
                            <tr>
                                <td style="border:none; padding:2px 6px; width:50%;">
                                    <span style="font-size:12px; color:#555; font-family:'Play',sans-serif;">Business / Project:</span>
                                    <strong style="font-size:13px; font-family:'Play',sans-serif;">
                                        {{ ucwords($businessData->project_name ?? '—') }}
                                    </strong>
                                </td>
                                <td style="border:none; padding:2px 6px; width:50%;">
                                    <span style="font-size:12px; color:#555; font-family:'Play',sans-serif;">Product Name:</span>
                                    <strong style="font-size:13px; font-family:'Play',sans-serif;">
                                        {{ ucwords($businessDetailsData->product_name ?? '—') }}
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @endisset
                    <div style="border: 1px solid black; width: 100%;">
                        <div style="border-bottom: 1px solid black; padding-bottom: 10px;">
                            <table style="width: 100%;">
                                <tr>
                                    <!-- Left Side: Logo -->
                                    <td style="width: 10%; text-align: left; vertical-align: middle;"
                                        class="header-no-border">
                                        <img src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') . $getOrganizationData->image }}"
                                            style="width:100px; padding:10px;">



                                        {{-- <img src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') }}{{ $getOrganizationData->image }}"
                                            alt="no image" style="width: 100px; padding: 10px;" /> --}}
                                    </td>

                                    <!-- Center: Company Name and Details -->
                                    <td style="width: 80%; text-align: center; vertical-align: middle;"
                                        class="header-no-border">
                                        <div {{-- style="font-size: 20px; font-weight: bold; text-transform: uppercase; font-family: sans-serif;" --}}
                                            style="
                                        font-size: 20px;    /* Set font size for smaller appearance */
                                        font-weight: bold;  /* Make it bold */
                                        text-transform: uppercase; /* Convert to uppercase */
                                        line-height: 1.2;   /* Adjust line height */
                                        font-family: Arial, sans-serif; /* Set clean font family */
                                         font-family: 'Play', sans-serif!important;
                                    ">
                                            {{ $getOrganizationData->company_name }}
                                        </div>
                                        <div
                                            style="margin-top: 5px; font-size: 12px;  font-family: 'Play', sans-serif!important;">
                                            {{ $getOrganizationData->address }}, CIN:
                                            {{ $getOrganizationData->cin_number }}<br>
                                            Phone No.: {{ $getOrganizationData->mobile_number }}, Email Id:
                                            {{ $getOrganizationData->email }}<br>
                                            GST No: {{ $getOrganizationData->gst_no }}
                                        </div>
                                    </td>

                                    <!-- Right Side: Empty (For spacing) -->
                                    <td style="width: 10%;" class="header-no-border"></td>
                                </tr>
                            </table>

                        </div>



                        <div class="d-flex justify-content-center align-items-center font-family-page"
                            style="font-size: 20px; font-weight: bold; text-align:center;border-bottom: 1px solid black;font-family: 'Play', sans-serif!important;">
                            PURCHASE ORDER</div>
                        <table
                            style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 13px;">
                            <tr>
                                <!-- Left Side Content -->
                                <td
                                    style="width: 50%; vertical-align: top; padding: 5px;  font-family: 'Play', sans-serif!important;">
                                    <div style="font-weight: bold;">To, {{ $purchaseOrder->vendor_company_name }}</div>
                                    <div>{{ $purchaseOrder->vendor_address }}</div>
                                    <div>GST No.: {{ $purchaseOrder->gst_no }}</div>
                                    <div>Mo. No.: {{ $purchaseOrder->contact_no }}</div>
                                    <div>Email Id: {{ $purchaseOrder->vendor_email }}</div>
                                </td>

                                <!-- Right Side Content -->
                                <td
                                    style="width: 25%; vertical-align: top; text-align: left; padding: 5px;  font-family: 'Play', sans-serif!important;">
                                    <div style="font-weight: bold;">PO. No.: {{ $purchaseOrder->purchase_orders_id }}
                                    </div>
                                    <div>Date:
                                        {{ $purchaseOrder->created_at ? $purchaseOrder->created_at->format('d-m-Y') : 'N/A' }}
                                    </div>
                                    <div>Quote Ref No.: {{ $purchaseOrder->quote_no }}</div>
                                    <div>Payment Terms: {{ $purchaseOrder->payment_terms }}</div>
                                    <div>Our Contact Person: {{ $purchaseOrder->contact_person_name }}</div>
                                    <div>Our Contact Person No.: {{ $purchaseOrder->contact_person_number }}</div>
                                </td>
                            </tr>
                        </table>

                        <div style="border-bottom: 1px solid black; padding: 10px;">
                            <div style="font-family: 'Play', sans-serif!important; font-size:11px;"><b>Dear Sir,
                                    Please arrange to supply the following Material as per quantity, specification, and
                                    schedule mentioned below</b></div>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                            <colgroup>
                                <col style="width:4%;">
                                <col style="width:23%;">
                                <col style="width:13%;">
                                <col style="width:24%;">
                                <col style="width:11%;">
                                <col style="width:10%;">
                                <col style="width:7%;">
                                <col style="width:8%;">
                            </colgroup>
                            <thead>
                                <tr style="bold; font-family: 'Play', sans-serif!important;font-size:14px;">
                                    <th class="pdf-font-size"
                                        style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left:0.1px solid black; padding: 5px; font-size:14px;">
                                        No.
                                    </th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:14px;">
                                        Description</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:14px;">HSN No.</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:14px;">Part No.</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:14px;">Quantity</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:14px;">Rate</th>
                                    <th class="pdf-font-size"
                                        style="width:100px; border: 1px solid black; padding: 5px; font-size:14px;">
                                        Discount</th>
                                    <th class="pdf-font-size"
                                        style="width:100px; border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black; border-right: 1px solid black;  padding: 5px; text-align: right; font-size:14px;">
                                        Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrderDetails as $index => $item)
                                    <tr style="bold; font-family: 'Play', sans-serif!important;">
                                        <td
                                            style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left:none; padding: 5px; text-align: center;">
                                            {{ $index + 1 }}</td>
                                        <td class="description-column"
                                            style="border: 1px solid black; padding: 5px; max-width: 200px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-word !important;
overflow-wrap: anywhere !important;">
                                            {{ $item->item_description }}</td>
                                        <td style="border: 1px solid black; padding: 5px;">{{ $item->hsn_name }}</td>
                                        <td class="po-part-no-column" style="border: 1px solid black; padding: 5px;">
                                            {{ $item->description }}
                                        </td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: left;">
                                            {{ $item->quantity }} {{ $item->unit_name }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: left;">
                                            {{ $item->rate }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: left;">
                                            {{ $item->discount }} %</td>
                                        <td
                                            style="border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black; border-right: none;  padding: 5px; text-align: right;">
                                            {{ $item->amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot
                                style="border-top: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; border-left:none;">
                                <tr style="bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td class="no-border" colspan="5" style="padding-top:10px;">
                                        <strong>Remark:- {{ $purchaseOrder->note }}</strong>
                                    </td>
                                    <td class="no-border" colspan="1"></td>
                                    <td style="border: 1px solid black;"><b>Sub Total</b></td>
                                    <td style="border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black; border-right: none;  padding: 5px; text-align: right;"
                                        class="text-right"><b>{{ $purchaseOrderDetails->sum('amount') }}</b></td>
                                </tr>
                                <tr style="bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td class="no-border" colspan="6"></td>
                                    <td style="border: 1px solid black;"><b>Freight</b></td>
                                    <td style="border: 1px solid black; text-align:right;" class="text-right">
                                        <b>0.00</b>
                                    </td>
                                </tr>
                                <tr style="bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td class="no-border" colspan="6"></td>
                                    <td style="border: 1px solid black;"><b>{{ $purchaseOrder->tax_type }}
                                            {{ $purchaseOrder->name }}%</b></td>

                                    <td style="border-top: 1px solid black;border-bottom:1px solid black;border-left:1px solid black; border-right:none;padding:5px; text-align:right;"
                                        class="text-right">
                                        <b> {{ $purchaseOrderDetails->sum('amount') * ($purchaseOrder->name / 100) }}
                                        </b>
                                    </td>

                                </tr>
                                <tr style="bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td class="no-border" colspan="6"></td>
                                    <td style="border: 1px solid black;"><b>NIL GST</b></td>
                                    <td style="border-top: 1px solid black;border-bottom:1px solid black;border-left:1px solid black; border-right:none;padding:5px; text-align:right;"
                                        class="text-right"><b>0.00</b></td>
                                </tr>
                                <tr
                                    style="border-bottom: 1px solid black; bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td class="no-border" colspan="5">
                                        <strong>Transport/Dispatch :- {{ $purchaseOrder->transport_dispatch }}</strong>
                                    </td>
                                    <td class="no-border" colspan="1"></td>
                                    <td style="border: 1px solid black;"><strong>Net Total<br> (Including
                                            {{ $purchaseOrder->tax_type }})</strong></td>
                                    <td style="border-top: 1px solid black;border-bottom:1px solid black;border-left:1px solid black; border-right:none;padding:5px; text-align:right;"
                                        class="text-right">
                                        <strong>
                                            {{ ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + $purchaseOrder->name / 100) }}
                                        </strong>
                                        <div>

                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- Signature block — outside main table so it never gets split across pages --}}
                        <div style="page-break-inside: avoid; border-top: 1px solid black; padding: 5px 0;">
                            {{-- In-words amount --}}
                            <div style="text-align: right; font-size:12px; font-family:'Play',sans-serif; padding: 4px 5px;">
                                <strong>
                                    @php echo convertToWords(($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->name / 100))); @endphp
                                </strong>
                            </div>
                            {{-- For: company + tick --}}
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; min-height: 80px; padding: 5px;">
                                <div></div>
                                <div style="text-align: right; font-size:12px; font-family:'Play',sans-serif;">
                                    @if ($purchaseOrder->purchase_status_from_owner == 1127 || $purchaseOrder->purchase_status_from_owner == 1129)
                                        <img src="{{ asset('website/assets/img/tick.png') }}" style="width:40px; display:block; margin-left:auto;" alt="">
                                    @endif
                                    <strong>For: <span style="text-transform:uppercase;">{{ $getOrganizationData->company_name }}</span></strong>
                                </div>
                            </div>
                            {{-- Signatory row --}}
                            <table style="width:100%; border:none; margin-top:4px;">
                                <tr>
                                    <td style="border:none; padding:4px 5px; font-size:11px; font-family:'Play',sans-serif; width:25%;"><strong>Prepared By</strong></td>
                                    <td style="border:none; padding:4px 5px; font-size:11px; font-family:'Play',sans-serif; width:25%; text-align:center;">( Finance Signatory )</td>
                                    <td style="border:none; padding:4px 5px; font-size:11px; font-family:'Play',sans-serif; width:25%; text-align:center;">( Purchase Signatory )</td>
                                    <td style="border:none; padding:4px 5px; font-size:11px; font-family:'Play',sans-serif; width:25%; text-align:right;">(Authorized Signatory)</td>
                                </tr>
                            </table>
                        </div>

                        @if (!empty($is_pdf))
                            <!-- PDF only spacing -->
                            <div style="margin-top:15px; margin-bottom:15px;">
                                <p
                                    style="margin-top:15px; margin-bottom:10px; margin-left:5px; font-size:12px; font-family:'Play', sans-serif;">
                                    This is a computer-generated document No signature is required
                                </p>

                                <p
                                    style="margin-top:10px; margin-bottom:20px; margin-left:5px; font-size:12px; font-family:'Play', sans-serif;">
                                    Subject To Nashik Jurisdiction. PO Terms and Conditions As per attached PO Annexture
                                </p>
                            </div>
                        @else
                            <!-- Browser view (no extra spacing) -->
                            <div class="print-spacing">
                                <div>This is a computer-generated document No signature is required</div>
                                <div>Subject To Nashik Jurisdiction. PO Terms and Conditions As per attached PO
                                    Annexture</div>
                            </div>
                        @endif

                        {{-- <div class="print-spacing">
                            <div><span
                                    style="padding: 10px 10px 10px 8px;font-family: 'Play', sans-serif!important; font-size:13px;">This
                                    is a computer-generated document No signature is required</span></div>
                            <div><span
                                    style="padding: 10px 10px 10px 8px;font-family: 'Play', sans-serif!important; font-size:13px;">Subject
                                    To Nashik Jurisdiction. PO Terms and Conditions As per attached PO Annexture</span>
                            </div>
                        </div> --}}
                        
                        {{-- <a>
                                <button data-toggle="tooltip" onclick="printInvoice()" style="margin: 20px;"   type="button" class="btn btn-primary print-btn m-4 print-button" >Print</button>
                            </a> --}}
                    </div>
                </div>
            </div>
        </div>



    </div>
    <script>
        function printInvoice() {
            // Clone the printable content area
            var contentToPrint = document.getElementById("printableArea").cloneNode(true);

            // Remove print button from the cloned content
            var printButtons = contentToPrint.getElementsByClassName("print-button");
            while (printButtons.length > 0) {
                printButtons[0].parentNode.removeChild(printButtons[0]);
            }

            // Remove business/product info bar from print
            var bizInfoBars = contentToPrint.getElementsByClassName("biz-product-info");
            while (bizInfoBars.length > 0) {
                bizInfoBars[0].parentNode.removeChild(bizInfoBars[0]);
            }

            // Open new print window
            var printWindow = window.open('', '', 'height=auto,width=auto');
            printWindow.document.write('<html><head><title>Print Invoice</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
                    @media print {
                        html, body {
                            width: 100%;
                            height: 100%;
                           font-family: Arial, sans-serif !important;
                            margin: 0;
                            padding: 0;
                        }
                        .logo-size {
                            width: 10%;
                        }
                            .print-spacing{
                            padding: 15px 0px;}
                        .middle-size {
                            width: 80%;
                        }
                        .last-size {
                            width: 10%;
                        }
                        .header-size {
                            font-size: 13px;
                        }
                            
                        #printableArea {
                            width: 100%;
                            margin: 0px;
                            padding: 20px 20px 10px 20px;
                        
                            box-sizing: border-box;
                        }
                            .pdf-font-size{
                            font-size:13px !important;
                            }
                           /* 🔥 FIXED DESCRIPTION FONT SIZE */
            .description-column{
                font-size:11px !important;
                line-height: 1.2 !important;
                word-break: break-word !important;
                white-space: normal !important;
            }
                                table {
                table-layout: fixed !important;
                width: 100% !important;
                word-wrap: break-word !important;
                overflow-wrap: break-word !important;
            }

            tr {
                page-break-inside: avoid !important;
            }
            thead { display: table-header-group !important; }
            tfoot { display: table-footer-group !important; }
                            

                          th, td {
                         font-family: Arial, sans-serif !important;
        font-size: 14px !important;  
        padding: 5px !important;
           text-align: left;
        vertical-align: top !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
        white-space: normal !important;
    }
                        th {
                            background-color: #f2f2f2;
                        }
                        @page {
                          size: A4;
    margin: 5mm;
                            padding:0;
                        }
                        .print-button {
                            display: none;
                        }
                    }
                `);
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(contentToPrint.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script>
