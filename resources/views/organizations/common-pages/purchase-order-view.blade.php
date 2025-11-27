<style>
    * {
        box-sizing: border-box;
    }

    td.description-column,
    th.description-column {
        white-space: normal !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }

    .description-column {
    width: 200px !important;
    word-break: break-word !important;
    white-space: normal !important;
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

    table th,
    table td {
        /* border: 1px solid black; */
        padding: 8px;
    }

    table th {
        background-color: #f2f2f2;
    }

    p {
        font-size: 16px !important;
    }

    .sparkline13-list-new {
        background-color: #fff;
        padding: 22px;
        margin-top: 72px;
        margin-bottom: 80px;
    }

    @media screen {
        .print-button {
            display: inline-block;
        }
    }

    .span {
        font-family: sans-serif !important;
    }

    @media print {

        /* General print styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .logo-size {
            width: 10% !important;
        }

        .middle-size {
            width: 80% !important;
        }

        .last-size {
            width: 10% !important;
        }

        .header-size {
            font-size: 10px !important;
        }

        #printableArea {
            width: 100%;
            margin: 0px;
            padding: 20px 20px 10px 20px;
            border-right: 1px solid black;
            box-sizing: border-box;
        }

        .amountBorder {
            border-right: 1px solid black;
        }

        .print-btn {
            display: none;
        }

        .border-page {
            border: 1px solid red;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        @page {
            size: A4;
            /* Set page size to A4 */
           margin: 10mm !important;
            /* Remove all margins for print area */
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .print-button {
            display: none;
        }
    }
</style>

<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list-new border-page" id="printableArea"
                    style="padding: 10px; box-sizing: border-box;">
                    <div style="border: 1px solid black; width: 100%;">
                        <div style="border-bottom: 1px solid black; padding-bottom: 10px;">
                            <table style="width: 100%;">
                                <tr>
                                    <!-- Left Side: Logo -->
                                    <td style="width: 10%; text-align: left; vertical-align: middle;">
                                        <img src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') }}{{ $getOrganizationData->image }}"
                                            alt="no image" style="width: 100px; padding: 10px;" />
                                    </td>

                                    <!-- Center: Company Name and Details -->
                                    <td style="width: 80%; text-align: center; vertical-align: middle;">
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
                                    <td style="width: 10%;"></td>
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
                            <thead>
                                <tr style="bold; font-family: 'Play', sans-serif!important;font-size:14px;">
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:12px;">No.</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:12px;">Description</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:12px;">HSN No.</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:11px;">Part No.</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:12px;">Quantity</th>
                                    <th class="pdf-font-size"
                                        style="border: 1px solid black; padding: 5px; font-size:12px;">Rate</th>
                                    <th class="pdf-font-size"
                                        style="width:100px; border: 1px solid black; padding: 5px; font-size:12px;">
                                        Discount</th>
                                    <th class="pdf-font-size"
                                        style="width:100px; border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black; border-right: 1px solid black;  padding: 5px; text-align: right; font-size:12px;">
                                        Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseOrderDetails as $index => $item)
                                    <tr style="bold; font-family: 'Play', sans-serif!important;">
                                        <td style="border: 1px solid black; padding: 5px; text-align: center;">
                                            {{ $index + 1 }}</td>
                                        <td class="description-column"
                                            style="border: 1px solid black; padding: 5px; max-width: 200px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-all;">
                                            {{ $item->item_description }}</td>
                                        <td style="border: 1px solid black; padding: 5px;">{{ $item->hsn_name }}</td>
                                        <td style="border: 1px solid black; padding: 5px;">{{ $item->description }}
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
                            <tfoot style="border: 1px solid black;">
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
                                    <td style="border: 1px solid black;"><strong>Net Total (Including
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
                                <tr
                                    style="padding-bottom: 20px; bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td colspan="8" class="no-border" style="padding: 0px;">
                                        {{-- Delivery AS PER ATTACHED DELIVERY SCHEDULE --}}
                                    </td>
                                </tr>
                                <tr style="bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td colspan="8" class="no-border" style="height: 10px; padding: 5px;">
                                        <div style="float: right; font-size:12px;"><strong>
                                                @php echo convertToWords(($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->name / 100)));  @endphp
                                            </strong></div>
                                    </td>
                                </tr>

                                <tr style="bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td colspan="8" class="no-border" style="height: 100px; padding: 5px;">
                                        <div style="float: right; font-size:18px; font-size:12px;"><strong>For: <span
                                                    style="text-transform: uppercase;">{{ $getOrganizationData->company_name }}</span></strong>
                                        </div>
                                    </td>

                                    {{-- <td colspan="8" class="no-border" style="height: 20vh;">
                                            <div style="float: right; font-size:18px;"><strong>For: {{ $getOrganizationData->company_name }}</strong></div>
                                        </td> --}}
                                </tr>
                                <tr style="bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td class="no-border" colspan="2"
                                        style="padding-bottom: 10px;  font-size:11px;">

                                    </td>
                                    <td class="no-border" colspan="2"
                                        style="padding-bottom: 10px; text-align:center; font-size:11px;"></td>
                                    <td class="no-border" colspan="3"
                                        style="padding-bottom: 10px; text-align:center; font-size:11px;"></td>
                                    <td class="no-border" colspan="2"
                                        style="display: block; text-align: center; padding-bottom: 10px; font-size:11px;">
                                        @if ($purchaseOrder->purchase_status_from_owner == 1127 || $purchaseOrder->purchase_status_from_owner == 1129)
                                            <div style="font-size: 26px; color: green;">
                                                {{-- <i class="fa fa-check" aria-hidden="true"></i> --}}
                                                <img src="{{ asset('website/assets/img/tick.png') }}"
                                                    style="width: 44px;" alt="">
                                            </div>
                                        @else
                                        @endif
                                    </td>
                                </tr>


                                <tr style="bold; font-family: 'Play', sans-serif!important; font-size:12px;">
                                    <td class="no-border" colspan="2"
                                        style="padding-bottom: 10px;  font-size:11px;">
                                        <strong>Prepared By</strong>
                                    </td>
                                    <td class="no-border" colspan="2"
                                        style="padding-bottom: 10px; text-align:center; font-size:11px;">( Finance
                                        Signatory )</td>
                                    <td class="no-border" colspan="3"
                                        style="padding-bottom: 10px; text-align:center; font-size:11px;">( Purchase
                                        Signatory )</td>
                                    <td class="no-border" colspan="2"
                                        style="display: block; text-align: center; padding-bottom: 10px; font-size:11px;">
                                        (Authorized Signatory)</td>
                                </tr>
                            </tfoot>
                        </table>
                        <div><span
                                style="padding: 10px 10px 10px 8px;font-family: 'Play', sans-serif!important; font-size:13px;">This
                                is a computer-generated document No signature is required</span></div>
                        <div><span
                                style="padding: 10px 10px 10px 8px;font-family: 'Play', sans-serif!important; font-size:13px;">Subject
                                To Nashik Jurisdiction. PO Terms and Conditions As per attached PO Annexture</span>
                        </div>
                        @if (empty($is_pdf))
                            <a>
                                <button onclick="printInvoice()" style="margin: 20px;" type="button"
                                    class="btn  print-btn m-4 print-button btn-bg-colour">Print</button>
                            </a>
                        @endif
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

            // Open new print window
            var printWindow = window.open('', '', 'height=auto,width=auto');
            printWindow.document.write('<html><head><title>Print Invoice</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
                    @media print {
                        html, body {
                            width: 100%;
                            height: 100%;
                            font-family: Font Awesome 5 Free;
                            margin: 0;
                            padding: 0;
                        }
                        .logo-size {
                            width: 10%;
                        }
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
                            border-right: 1px solid black;
                            box-sizing: border-box;
                        }
                            .pdf-font-size{
                            font-size:9px !important;
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
                            padding: 5px;
                            text-align: left;
                            word-wrap: break-word;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                        @page {
                            size: A4;
                            margin: 0;
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

   
