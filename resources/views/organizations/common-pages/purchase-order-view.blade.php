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
                            <div style="display: flex; border-bottom: 1px solid black; padding-bottom: 10px;">
                                <div style="width:20%;">
                                    <img class="img-size"
                                    src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') }}{{ $getOrganizationData->image }}"
                                    alt="No Image"
                                    style="width: 100px; padding:10px;" /> <!-- Inline style here may override print styles -->
                                    </div>
                                <div style="width:80%;">
                                    <div style="text-align: center; font-size: 20px; font-weight: bold;">{{ $getOrganizationData->company_name }}</div>
                                    <div style="text-align: center;">
                                        {{ $getOrganizationData->name }}: {{ $getOrganizationData->mobile_number }}, {{ $getOrganizationData->email }}
                                    </div>
                                </div>
                            </div>
                    
                            <!-- Company and PO Details -->
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid black; padding-top: 10px; padding-bottom: 10px; ">
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
                                        <th style="border: 1px solid black; padding: 5px;">HSN No.</th>
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
                                        <td style="border: 1px solid black; padding: 5px;">{{ $item->hsn_name }}</td>
                                        <td style="border: 1px solid black; padding: 5px; max-width: 150px; overflow-wrap: break-word; word-wrap: break-word; word-break: break-all;">
                                            {{ $item->description }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: center;">{{ $item->due_date }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $item->quantity }} {{ $item->unit_name }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: left;">{{ $item->rate }}</td>
                                        <td style="border: 1px solid black; padding: 5px; text-align: right;">{{ $item->amount }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="border: 1px solid black;">
                                    <tr>
                                        <td class="no-border" colspan="3">
                                            <strong>Remark:- {{ $purchaseOrder->note }}</strong>
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
                                        <td class="no-border" colspan="3"></td>
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
                                        <td class="no-border" colspan="6"></td>
                                        <td style="border: 1px solid black;">Freight</td>
                                        <td style="border: 1px solid black;" class="text-right">0.00</td>
                                    </tr>
                                    {{-- <tr>
                                        <td class="no-border" colspan="5"></td>
                                        <td>{{ $purchaseOrder->tax_type }} {{ $purchaseOrder->tax_id }}%</td>
                                        <td class="text-right">{{ ($purchaseOrderDetails->sum('amount') - $purchaseOrderDetails->sum('amount') * ($purchaseOrder->discount / 100)) * (1 + ($purchaseOrder->tax_id / 100)) }}</td>
                                    </tr> --}}
                                    <tr>
                                        <td class="no-border" colspan="6"></td>
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
                                        <td class="no-border" colspan="6"></td>
                                        <td style="border: 1px solid black;">NIL GST</td>
                                        <td style="border: 1px solid black;" class="text-right">0.00</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid black;">
                                        <td class="no-border" colspan="3">
                                            <strong>Transport/Dispatch :- {{ $purchaseOrder->transport_dispatch }}</strong>
                                        </td>
                                        <td class="no-border" colspan="3"></td>
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
                                        <td class="no-border" colspan="3">( Purchase Signatory )</td>
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

                            <div>
                               
                                <p>Purchase Order<br></br>
                                    Standard Terms & Conditions<br></br>
                                    General:<br></br>
                                    Acceptance of this Purchase/ Work Order including revision (hereinafter referred to as “PO/Order”)
                                    includes the acceptance of the following terms & conditions and is made expressly conditional on
                                    Seller’s (hereinafter also referred to as “Vendor(s)” or “Supplier(s)”) assent to the exact terms contained
                                    herein. None of the terms in the Order may be modified, added to, or superseded, except with the written
                                    consent of Shreerag Engineering and Auto Pvt Ltd<br></br>
                                    (hereinafter also referred to as “ SREAPL” or “Buyer”). It is understood by the Seller that this document goes through various changes periodically and hence they will ensure that they keep checking for the latest copy regularly and keep themselves abreast about the same.
                                    1. Placing order:<br></br>
                                    Only orders in traceable form (in writing, by email only from the email ID purchase@shreeragengg.com) are
                                    binding. Verbal orders or orders by phone as well as changes and additions to our order shall be binding
                                    only if confirmed by us in traceable form. Terms at variance with our General Purchase Conditions and
                                    additional terms, including reservations regarding price or exchange rates, as well as deviating General
                                    Conditions of Sale and Delivery of the Supplier shall be valid only if accepted by us in traceable form.
                                    2. Price:<br></br>
                                    The prices mentioned in this Order are the prices at which Buyer has agreed to purchase the Goods or
                                    Services (as applicable). No escalation in the aforesaid prices shall be binding on Buyer,
                                    notwithstanding anything that may be mentioned in Seller’s terms of acceptance of Order.
                                    3. Advice of Dispatch:<br></br>
                                    A full and comprehensive dispatch advice notice shall be sent to concerned office
                                    of the Buyer (“Buyer”) as communicated. Instructions regarding dispatch & Insurance
                                    as mentioned in this Order should be complied with and the packing slips and relevant documents like
                                    MSDS sheets or certificates as mandated in the Buyers Order shall be included securely with the goods
                                    in closed envelopes. All the Documents shall have the Buyers Order numbers stated on them.
                                    4. Delivery Terms:<br></br>
                                    (a) Delivery Date: Time is of the essence. Time of delivery/performance as mentioned in this Order
                                    shall be the essence of the Agreement and no variations shall be permitted except with prior
                                    authorization in writing from the Buyer. Price and delivery basis will be as per stated<br></br>
                                    on the Order, in the absence of which Ex-Works terms will apply.<br></br>
                                    (b) Place of Delivery: The goods/services shall be delivered/ performed strictly as per the instructions
                                    in the Order.<br></br>
                                    (c) Delayed Delivery: The time and date of delivery/performance as stipulated in the Order shall be
                                    deemed to be the essence of the Agreement. In case of delay in performance of its obligations by the
                                    Seller, or any extension granted by the Buyer, the Buyer shall at his option either (i) accept delayed
                                    deliveries at price reduced by a sum/ percentage (%) mentioned in the Purchase Order for every week
                                    of delay or part thereof; and/or (ii) cancel the Order in part or in full and purchase such cancelled
                                    quantities from open market at the prevailing market price at the risk & cost of the Seller without
                                    prejudice to his rights under 4(c)(i) above in respect to the goods delivered; and/or (iii) refuse to accept
                                    the Goods delivered beyond the delivery date and claim/set-off the difference between the prevailing
                                    market price and contracted price of such quantity delivered belatedly by the Seller.<br></br>
                                    (d) Delay due to force majeure:<br></br>
                                    In the event of cause of force majeure occurring within the agreed delivery terms, the delivery date may
                                    be extended by the Buyer at its sole and absolute discretion on receipt of application from the Seller
                                    without imposition of liquidated damages. Only those cause(s) which have duration of more than seven
                                    (7) consecutive calendar days will be considered the cause of force majeure. The Seller must inform the
                                    Buyer, the beginning and the end of the cause of delay immediately, by an email to<br></br>
                                    purchase@shreeragengg.com with attached letter duly Certified by the Statutory Authorities, in addition if
                                    required by the Buyers the original letter shall be sent by Registered Post or courier This shall in no
                                    case be later than ten (10) days from the beginning and end of each cause of force majeure as defined
                                    above.<br></br>
                                    5. Compliance to Specification:<br></br>
                                    The goods shall correspond with the description of the original specification or of the samples thereof
                                    in full details and must be delivered and dispatched within the stipulated time, as the case may be
                                    otherwise the same shall be liable to be rejected and the Seller shall be deemed to have failed to deliver
                                    the goods in breach of the PO. The Buyer shall in that event at its sole and absolute discretion, will be
                                    entitled to either purchase such goods from other sources on Seller's account, in which case, the Seller
                                    shall be liable to pay to the Buyer any difference between the price at which such goods have been
                                    purchased and the price calculated at the rate set-out in this Order or to hold the Seller liable to pay the
                                    Buyer damages for non-delivery of goods for such breach.<br></br>
                                    
                                    6. Compliance to Regulation (For freight by sea/air)
                                    The Seller shall Guarantee that no hazardous material identified under MEPC269(68) and EUSRR have
                                    been used in the supplies. Seller shall complete and provide Appendix A1 Suppliers Declaration of
                                    conformity (SDOC) and Appendix A2: Material Declaration form (MDF) along with the items and
                                    other technical documentation.<br></br>
                                    7. Packing:<br></br>
                                    Goods supplied against this order must be suitably and properly packed (conforming to special
                                    conditions stipulated by the Buyer, if any, for safe and/or undamaged transport by the designated mode
                                    of transport which is Air, Courier, Sea, Road or Rail.)<br></br>
                                    8. Examination of goods:<br></br>
                                    Irrespective of the fact that the goods are delivered to the Buyer by the Seller at the Seller's place or at
                                    Buyer's said office or are dispatched as per Buyer’s instructions by air, courier, sea, rail or by road, the
                                    goods shall always be supplied, subject to detailed inspection, at the Buyer works or such other
                                    destinations as specified in the Order for ascertaining whether the goods are in conformity with the
                                    Agreement or not and until then in no event the Buyer shall be deemed to have accepted such goods
                                    and upon any rejection of goods in question the Seller shall be deemed to have failed to deliver the
                                    concerned goods in accordance with the Agreement.<br></br>
                                    9. Rejection/ Removal of rejected goods and replacement:<br></br>
                                    Buyer shall have the right to reject the goods whether in full or parts which are not delivered in
                                    accordance with the terms of the PO. Within fifteen days from the receipt of the intimation from the
                                    Buyer of his rejection to accept the goods the Seller shall remove, at his own cost, the rejected goods
                                    from the Buyer's works or wherever such goods are lying. The Buyer shall not be in any way responsible
                                    for or be held liable for any loss or deterioration of the rejected goods shall be at the Seller's risk entirely.
                                    The Seller shall pay to the Buyer reasonable storage charges for storing such rejected goods for a period
                                    exceeding 15 days as aforesaid. Upon rejection, if the Seller fails to replace the goods with the goods
                                    acceptable to the Buyer within the contractual period then the Buyer may, solely at his discretion,
                                    exercise all or any of the following options in respect of the rejected/undelivered quantity: -
                                    a. Dispose-off the rejected goods and claim/set-off the difference between the prevailing market price
                                    and contracted price of such undelivered/rejected quantity to the Seller’s account; and/or
                                    b. Purchase such undelivered/rejected quantity from open market at the prevailing market price at the
                                    risk and cost of the Seller.<br></br>
                                    10. Ownership<br></br>
                                    Save as otherwise provided in this order, no right, title or interest shall be passed on to the Supplier by
                                    virtue of these presents, in the products/raw materials machines/tools/drawings etc., furnished by the
                                    Purchaser to the Supplier, for rendering the processing services. The Supplier shall, at no time, contest
                                    or challenge our said and exclusive rights, title and interest in the said products/raw materials/
                                    machines/tools/drawings etc.<br></br>
                                    11. Encumbrance<br></br>
                                    The Supplier shall not sell, assign, sub-let, pledge, hypothecate or otherwise encumber or suffer a lien
                                    upon or against the said product/raw materials/ machines tools/ drawings etc. and the Supplier shall
                                    undertake to abide by the same.<br></br>
                                    12. Insurance:
                                    a. The vendor shall arrange for a comprehensive insurance coverage for the personnel & material
                                    at their own cost. The Company shall not be liable for any damages to the personnel & material.
                                    13. Invoices:<br></br>
                                    All bills/ invoices for supplies/ services made bearing sales-tax registration number and / or Goods and
                                    Services Tax Identification Number (“GSTIN”) of the Vendor should be sent to purchase@shreeragengg.com
                                    with supporting documents; packing list/delivery note duly acknowledged by SREAPL Sign and
                                    Stamp or that of the agent/ forwarder appointed by SREAPL and if applicable email approval from
                                    purchase@shreeragengg.com for any extra charges incurred beyond the Order value. All documents shall
                                    bear the Purchase order number.<br></br>
                                    Where applicable the Vendor shall ensure that E-way bill is generated along with all documents, as
                                    specified under Rule 138 of the Central Goods and Services Tax Rules, 2017 (“CGST Rules, 2017).
                                    The Vendor hereby undertakes that it would be the party responsible for the generation of E-Way bill
                                    as required under the CGST Rules, 2017, and in no situation would the responsibility of issuance of an
                                    E-way Bill be transferred to SREAPL.<br></br>
                                    Outstation/ out of pocket expenses of service personnel of the vendor/ supplier in relation to travel &
                                    expenses relating to transportation of equipment(s), material(s), installation(s) amongst others will be
                                    reimbursed at actuals on submission of adequate supports. Expenses like Airfare, Hotel Stay, parking
                                    charges, Taxi charges etc will be paid at actuals. Vendor /supplier will take utmost efforts to get pre-
                                    approval for these costs or their close estimates as practically possible. In case the Vendor/ Supplier
                                    fails to provide supporting documents towards the said expense(s) then the same shall be at discretion
                                    of the Vessel superintendent of the company and which shall be final and binding on the parties.
                                    </p>
                            </div>
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