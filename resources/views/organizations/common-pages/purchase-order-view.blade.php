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

    /* table tr td {
                                border: 1px solid red;
                            } */
</style>


<div class="data-table-area mg-tb-15" >
    <div class="container-fluid">
        <div class="row" >

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list" id="printableArea">
                  
                        <div style="border: 1px solid black; padding: 10px; width: 100%; margin-bottom:70px;">
                            <!-- Header Section -->
                            <div style="border-bottom: 1px solid black; padding-bottom: 10px;">
                                <div style="text-align: center; font-size: 20px; font-weight: bold;">Share</div>
                                <div style="text-align: center;">
                                    {{ $getOrganizationData->name }}: {{ $getOrganizationData->mobile_number }}, {{ $getOrganizationData->email }}
                                </div>
                            </div>
                    
                            <!-- Company and PO Details -->
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid black; padding-top: 10px; padding-bottom: 10px;">
                                <div>
                                    <div style="font-weight: bold;">{{ $purchaseOrder->vendor_company_name }}</div>
                                    <div>{{ $purchaseOrder->vendor_address }}</div>
                                    <div style="margin-top: 10px;">PO Number: {{ $purchaseOrder->purchase_orders_id }}</div>
                                    <div>Date: {{ $purchaseOrder->created_at }}</div>
                                </div>
                                <div>
                                    <div>P.O. No.: {{ $purchaseOrder->purchase_orders_id }}</div>
                                    <div>Date Ref No.: {{ $purchaseOrder->quote_no }}</div>
                                    <div>Payment Terms: {{ $purchaseOrder->payment_terms }} DAYS</div>
                                </div>
                            </div>
                    
                            <!-- Message Section -->
                            <div style="border-bottom: 1px solid black; padding-top: 10px; padding-bottom: 10px;">
                                <div><b>Dear Sir, Please arrange to supply the following Material as per quantity, specification, and schedule mentioned below</b></div>
                            </div>
                    
                            <!-- Table for PO Details -->
                            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 10px;">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid black; padding: 5px;">Sr. No.</th>
                                        <th style="border: 1px solid black; padding: 5px;">Part No.</th>
                                        <th style="border: 1px solid black; padding: 5px;">Description</th>
                                        <th style="border: 1px solid black; padding: 5px;">Due Date</th>
                                        <th style="border: 1px solid black; padding: 5px;">Quantity</th>
                                        <th style="border: 1px solid black; padding: 5px;">Rate</th>
                                        <th  style="border: 1px solid black; padding: 5px;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrderDetails as $index => $item)
                                    <tr>
                                        <td style="border: 1px solid black; padding: 5px; text-align: center;">{{ $index + 1 }}</td>
                                        <td style="border: 1px solid black; padding: 5px;">{{ $item->item_description }}</td>
                                        <td style="border: 1px solid black; padding: 5px; max-width: 150px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-all;">
                                            {{ $item->description }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: center;">{{ $item->due_date }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $item->quantity }} {{ $item->unit }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: left;">{{ $item->rate }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $item->amount }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="border: 1px solid black;">
                                    <tr>
                                        <td class="no-border" colspan="2">
                                            <strong>Terms & Condition :- {{ $getOrganizationData->terms_condition }}</strong>
                                        </td>
                                        <td class="no-border" colspan="3"></td>
                                        <td style="border: 1px solid black;">Sub Total</td>
                                        <td style="border: 1px solid black;" class="text-right">{{ $purchaseOrderDetails->sum('amount') }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <td class="no-border" colspan="2">
                                            <strong>Remark :- {{ $purchaseOrder->remark }}</strong>
                                        </td>
                                        <td class="no-border" colspan="3"></td>
                                        <td>Discount {{ $purchaseOrder->discount }}%</td>
                                        <td class="text-right">{{ $purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100) }}</td>
                                    </tr> --}}
                                    <tr>
                                        <td class="no-border" colspan="2"></td>
                                        <td class="no-border" colspan="3"></td>
                                        <td style="border: 1px solid black;">Discount Amount</td>
                                        <td class="text-right" style="border: 1px solid black;">
                                            {{ $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100) }} <!-- Discount Amount -->
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td class="no-border" colspan="2"></td>
                                        <td class="no-border" colspan="3"></td>
                                        <td>Total After Discount</td>
                                        <td class="text-right">
                                            {{ $purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100) }} <!-- Total After Discount -->
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td class="no-border" colspan="5"></td>
                                        <td style="border: 1px solid black;">Freight</td>
                                        <td style="border: 1px solid black;" class="text-right">0.00</td>
                                    </tr>
                                    {{-- <tr>
                                        <td class="no-border" colspan="5"></td>
                                        <td>{{ $purchaseOrder->tax_type }} {{ $purchaseOrder->tax_id }}%</td>
                                        <td class="text-right">{{ ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->tax_id / 100)) }}</td>
                                    </tr> --}}
                                    <tr>
                                        <td class="no-border" colspan="5"></td>
                                        <td style="border: 1px solid black;">{{ $purchaseOrder->tax_type }} {{ $purchaseOrder->tax_id }}%</td>
                                        <td style="border: 1px solid black;" class="text-right">
                                            {{ 
                                                ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * ($purchaseOrder->tax_id / 100) 
                                            }} <!-- GST Amount -->
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td class="no-border" colspan="2"></td>
                                        <td class="no-border" colspan="3"></td>
                                        <td>Total Amount Including GST</td>
                                        <td class="text-right">
                                            {{ 
                                                ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->tax_id / 100)) 
                                            }} <!-- Total Amount Including GST -->
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td class="no-border" colspan="5"></td>
                                        <td style="border: 1px solid black;">NIL GST</td>
                                        <td style="border: 1px solid black;" class="text-right">0.00</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black;">
                                        <td class="no-border" colspan="3">
                                            <strong>Transport/Dispatch :-</strong>
                                        </td>
                                        <td class="no-border" colspan="2"></td>
                                        <td style="border: 1px solid black;"><strong>Net Total (Including {{ $purchaseOrder->tax_type }})</strong></td>
                                        <td style="border: 1px solid black;" class="text-right">
                                            <strong>  {{ 
                                                ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->tax_id / 100)) 
                                            }} <!-- Total Amount Including GST --></strong>
                                          <div>
                                            @php
                                            echo convertToWords(($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->tax_id / 100)));
                                                                                      @endphp
                                          </div>
                                        </td>
                                    </tr>
                                   
                                    <tr>
                                        <td colspan="8" class="no-border">
                                            Delivery AS PER ATTACHED DELIVERY SCHEDULE
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" class="no-border">
                                            <div style="float: right;"><strong>For: {{ $getOrganizationData->company_name }}</strong></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="2">
                                            <strong>Prepared By</strong>
                                        </td>
                                        <td class="no-border" colspan="2">( Finance Signatory )</td>
                                        <td class="no-border" colspan="2">( Purchase Signatory )</td>
                                        <td class="no-border" colspan="1">( Authorized Signatory )</td>
                                    </tr>
                                </tfoot>
                            </table>
                    
                            <!-- Rules and Regulations -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color: #fff; border: 1px solid black;">
                                <div style="padding: 20px 10px 20px 10px;">
                                    <h3>{{ $getAllRulesAndRegulations->title }}</h3>
                                    <p>{{ $getAllRulesAndRegulations->description }}</p>
                                </div>
                            </div>
                    
                            <!-- Print Button -->
                            <a>
                                <button data-toggle="tooltip" onclick="printInvoice()" style="margin-top: 20px;">Print</button>
                            </a>
                        </div>
                    
                      
                    
               
                    </div>

                

               
                

            </div>
          
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