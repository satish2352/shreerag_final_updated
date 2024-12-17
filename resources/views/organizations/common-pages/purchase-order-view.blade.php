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
    p{
    font-size:16px !important;
    }
     .sparkline13-list-new{
    background-color: #fff;
    padding: 22px;
    margin-top: 72px;
    margin-bottom: 80px;
    } 
    @media screen {
        .print-button {
            display: inline-block; /* Ensure it's visible in the browser */
        }
    }
</style>


<div class="data-table-area mg-tb-15" >
    <div class="container-fluid">
        <div class="row" >

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list-new border-page"  id="printableArea" style="padding: 10px; box-sizing: border-box;">
                  
                        <div style="border: 1px solid black; width: 100%;">
                            <div style="border-bottom: 1px solid black; padding-bottom: 10px;">
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <div style="width: 20%; text-align: left;">
                                        <img 
                                            src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') }}{{ $getOrganizationData->image }}" 
                                            alt="" 
                                            style="width: 100px; padding: 10px;" 
                                        />
                                    </div>
                                    <div style="width: 60%; text-align: center; ">
                                        <span style="font-size: 20px; font-weight: bold; text-transform: uppercase;">{{ $getOrganizationData->company_name }}</span>

                                        <div style="text-align: center; margin-top: 5px;">
                                            <span style="font-size: 15px;">{{ $getOrganizationData->address }} {{ $getOrganizationData->mobile_number }}, {{ $getOrganizationData->email }}</span>
                                        </div>
                                    </div>
                                    <div style="width: 20%;"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center" style="font-size: 20px; font-weight: bold; text-align:center;border-bottom: 1px solid black; padding: 10px;">PURCHASE ORDER</div>
                            <div  style="display: flex; justify-content: space-between; border-bottom: 1px solid black; padding:10px; ">
                                <div style="width:60%;">
                                    <div style="font-weight: bold;">To, {{ $purchaseOrder->vendor_company_name }}</div>
                                    <div>{{ $purchaseOrder->vendor_address }}</div>
                                    <div>GST No. : {{ $purchaseOrder->gst_no }}</div>
                                    <div>Mo. No. : {{ $purchaseOrder->contact_no }}</div>
                                    <div>Email Id : {{ $purchaseOrder->vendor_email }}</div>                                   
                                </div>
                                <div style="width:40%;display: grid;justify-content: end;">
                                    <div>P.O. No.: {{ $purchaseOrder->purchase_orders_id }}</div>
                                    <div>Date: {{ $purchaseOrder->created_at ? $purchaseOrder->created_at->format('Y-m-d') : 'N/A' }}</div>
                                    <div>Quote Ref No. : {{ $purchaseOrder->quote_no }}</div>
                                    <div>Payment Terms : {{ $purchaseOrder->payment_terms }} DAYS</div>
                                </div>
                            </div>
                            <div style="border-bottom: 1px solid black; padding: 10px;">
                                <div><b>Dear Sir, Please arrange to supply the following Material as per quantity, specification, and schedule mentioned below</b></div>
                            </div>
                            <table  style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid black; padding: 5px;">Sr. No.</th>
                                        <th style="border: 1px solid black; padding: 5px;">Part No.</th>
                                        <th style="border: 1px solid black; padding: 5px;">HSN No.</th>
                                        <th style="border: 1px solid black; padding: 5px;">Description</th>
                                        <th style="border: 1px solid black; padding: 5px;">Due Date</th>
                                        <th style="border: 1px solid black; padding: 5px;">Quantity</th>
                                        <th style="border: 1px solid black; padding: 5px;">Rate</th>
                                        <th style="border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black; border-right: 1px solid black;  padding: 5px; text-align: right;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrderDetails as $index => $item)
                                    <tr>
                                        <td style="border: 1px solid black; padding: 5px; text-align: center;">{{ $index + 1 }}</td>
                                        <td style="border: 1px solid black; padding: 5px;">{{ $item->item_description }}</td>
                                        <td style="border: 1px solid black; padding: 5px;">{{ $item->hsn_name }}</td>
                                        <td style="border: 1px solid black; padding: 5px; max-width: 150px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-all;">
                                            {{ $item->description }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: center;">{{ $item->due_date }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $item->quantity }} {{ $item->unit_name }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: left;">{{ $item->rate }}</td>
                                        <td style="border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black; border-right: none;  padding: 5px; text-align: right;">{{ $item->amount }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="border: 1px solid black;">
                                    <tr>
                                        <td class="no-border" colspan="5" style="padding-top:10px;">
                                            <strong>Remark:- {{ $purchaseOrder->note }}</strong>
                                        </td>
                                        <td class="no-border" colspan="1"></td>
                                        <td style="border: 1px solid black;">Sub Total</td>
                                        <td style="border-top: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black; border-right: none;  padding: 5px; text-align: right;" class="text-right">{{ $purchaseOrderDetails->sum('amount') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="3"></td>
                                        <td class="no-border" colspan="3"></td>
                                        <td style="border: 1px solid black;">Discount Amount</td>
                                        <td class="text-right" style="border: 1px solid black; text-align:right;">
                                            {{ $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100) }} <!-- Discount Amount -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="6"></td>
                                        <td style="border: 1px solid black;">Freight</td>
                                        <td style="border: 1px solid black; text-align:right;" class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="6"></td>
                                        <td style="border: 1px solid black;">{{ $purchaseOrder->tax_type }} {{ $purchaseOrder->name }}%</td>
                                        <td style="border-top: 1px solid black;border-bottom:1px solid black;border-left:1px solid black; border-right:none;padding:5px; text-align:right;" class="text-right">
                                            {{ 
                                                ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * ($purchaseOrder->name / 100) 
                                            }} 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="6"></td>
                                        <td style="border: 1px solid black;">NIL GST</td>
                                        <td style="border-top: 1px solid black;border-bottom:1px solid black;border-left:1px solid black; border-right:none;padding:5px; text-align:right;" class="text-right">0.00</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black;">
                                        <td class="no-border" colspan="5">
                                            <strong>Transport/Dispatch :- {{ $purchaseOrder->transport_dispatch }}</strong>
                                        </td>
                                        <td class="no-border" colspan="1"></td>
                                        <td style="border: 1px solid black;"><strong>Net Total (Including {{ $purchaseOrder->tax_type }})</strong></td>
                                        <td style="border-top: 1px solid black;border-bottom:1px solid black;border-left:1px solid black; border-right:none;padding:5px; text-align:right;" class="text-right">
                                            <strong>  {{ 
                                                ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->name / 100)) 
                                            }} </strong>
                                          <div>
                                            @php
                                            echo convertToWords(($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->name / 100)));
                                                                                      @endphp
                                          </div>
                                        </td>
                                    </tr>
                                    <tr style="padding-bottom: 20px;">
                                        <td colspan="8" class="no-border" style="padding-top: 10px;">
                                            Delivery AS PER ATTACHED DELIVERY SCHEDULE
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" class="no-border" style="height: 100px; padding: 5px;">
                                            <div style="float: right; font-size:18px;"><strong>For: {{ $getOrganizationData->company_name }}</strong></div>
                                         </td>
                                         
                                        {{-- <td colspan="8" class="no-border" style="height: 20vh;">
                                            <div style="float: right; font-size:18px;"><strong>For: {{ $getOrganizationData->company_name }}</strong></div>
                                        </td> --}}
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="2" style="padding-bottom: 10px;">
                                            <strong>Prepared By</strong>
                                        </td>
                                        <td class="no-border" colspan="2" style="padding-bottom: 10px; text-align:center;">( Finance Signatory )</td>
                                        <td class="no-border" colspan="3" style="padding-bottom: 10px; text-align:center;">( Purchase Signatory )</td>
                                        <td class="no-border" colspan="2" style="display: block; text-align: center; padding-bottom: 10px;">(Authorized Signatory)</td>
                                    </tr>
                                </tfoot>
                            </table>
                          
                           
                            <a>
                                <button data-toggle="tooltip" onclick="printInvoice()" style="margin: 20px;"   type="button" class="btn btn-primary print-btn m-4 print-button" >Print</button>
                            </a>
                            </div>
                        </div>
                    </div>
            </div>


            
        </div>
        <script>
            function printInvoice() {
                var contentToPrint = document.getElementById("printableArea").innerHTML;
                var printWindow = window.open('', '', 'height=auto,width=auto');
                printWindow.document.write('<html><head><title>Print Invoice</title>');
                printWindow.document.write('<style>');
                printWindow.document.write(`
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                        }
                        #printableArea {
                            width: 100%;
                            margin: 0px;
                            padding: 20px 20px 10px 20px;
                            border-right: 1px solid black;
                            box-sizing: border-box;
                          
                        }
                            .amountBorder{
                           border-right: 1px solid black;
                           }
                        .print-btn{
                            display:none;
                            }
                        .border-page {
                            border: 1px solid red;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin: 0;
                        }
                        th, td {
                            padding: 5px;
                            text-align: left;
                            word-wrap: break-word;
                            
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                        @page {
                            size: A4; /* Set page size to A4 */
                            margin: 0; /* Remove all margins for print area */
                        }
                        html, body {
                            width: 100%;
                            height: 100%;
                            margin: 0;
                            padding: 0;
                        }
                            .print-button {
            display: none;
        }
                    }
                `);
                printWindow.document.write('</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write('<div id="printableArea">');
                printWindow.document.write(contentToPrint);
                printWindow.document.write('</div>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }
        </script>