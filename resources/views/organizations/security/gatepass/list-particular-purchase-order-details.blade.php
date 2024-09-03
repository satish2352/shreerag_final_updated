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


<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list" >
                  
                        <div style="border: 1px solid black; padding: 10px; width: 100%;">
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
                                        <td style="border: 1px solid black; padding: 5px;">{{ $item->name }}</td>
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
                                        <td>Sub Total</td>
                                        <td class="text-right">{{ $purchaseOrderDetails->sum('amount') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="2">
                                            <strong>Remark :- {{ $purchaseOrder->remark }}</strong>
                                        </td>
                                        <td class="no-border" colspan="3"></td>
                                        <td>Discount {{ $purchaseOrder->discount }}%</td>
                                        <td class="text-right">{{ $purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="5"></td>
                                        <td>Freight</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="5"></td>
                                        <td>{{ $purchaseOrder->tax_type }} {{ $purchaseOrder->tax_id }}%</td>
                                        <td class="text-right">{{ ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->tax_id / 100)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="no-border" colspan="5"></td>
                                        <td>NIL GST</td>
                                        <td class="text-right">0.00</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black;">
                                        <td class="no-border" colspan="3">
                                            <strong>Transport/Dispatch :-</strong>
                                        </td>
                                        <td class="no-border" colspan="2"></td>
                                        <td><strong>Net Total</strong></td>
                                        <td class="text-right">
                                            <strong>{{ $purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100) + ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * 0.09 * 2 }}</strong>
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
                    
                          
                    
                            <!-- Print Button -->
                            <a>
                                <button data-toggle="tooltip" onclick="printInvoice()" title="Accept Purchase Order" style="margin-top: 20px;">Print</button>
                            </a>
                        </div>
                    
                        <script>
                            function printInvoice() {
                                window.print();
                            }
                        </script>
                 
                    
               
                    </div>

                

               
                <script>
                    function printInvoice() {
                        window.print();
                    }
                </script>

            </div>
        </div>