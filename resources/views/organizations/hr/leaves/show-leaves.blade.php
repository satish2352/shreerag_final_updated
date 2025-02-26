@extends('admin.layouts.master')
<style>
    .borderpage {
        border: 1px solid black;
        padding: 10px;
    }
</style>
@section('content')
    <div class="show-page-position">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 " style="padding-top: 88px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-start align-items-center">
                    <h3 class="page-title">
                        Employee Details
                    </h3>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 show-btn-position">
                    <a href="{{ route('list-leaves-acceptedby-hr') }}" class="btn btn-sm btn-primary ml-3">Back</a>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">

                <div class="" style="background-color: #fff; padding:25px;">


                    <div class="col-12 " id="printableArea" style="border: 1px solid black;">
                        @include('admin.layouts.alert')



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
                                                     font-family: 'Font Awesome 5 Free'!important;
                                                ">
                                            {{ $getOrganizationData->company_name }}
                                        </div>
                                        <div
                                            style="margin-top: 5px; font-size: 12px;  font-family: 'Font Awesome 5 Free'!important;">
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
                        <div style="display: flex;justify-content: center;">
                            <h3 style="font-family: 'Font Awesome 5 Free'!important; ">Leave Application Form</h3>

                        </div>
                        <div class="row " style="padding-left:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">Full Name :</h4>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">
                                    {{ $user_detail->other_employee_name }} </h4>
                            </div>
                        </div>
                        <div class="row " style="padding-left:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;"><strong>Email Id</strong> :
                                    </h4>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">
                                    {{ strip_tags($user_detail->u_email) }}</h4>
                            </div>
                        </div>
                        <div class="row " style="padding-left:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4"
                                style="font-family: 'Font Awesome 5 Free'!important;">
                                <h4><strong>From Date</strong> :</h4>
                              
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">
                                    {{ strip_tags($user_detail->leave_start_date) }}</h4>
                            </div>
                        </div>
                        <div class="row " style="padding-left:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4"
                            style="font-family: 'Font Awesome 5 Free'!important;">
                                <h4><strong>To Date</strong> :</h4>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">
                                    {{ strip_tags($user_detail->leave_end_date) }}</h4>
                            </div>
                        </div>
                        <div class="row " style="padding-left:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4"
                                style="font-family: 'Font Awesome 5 Free'!important;">
                                <h4><strong>Leave Day</strong> :</h4>
                               
                            </div>

                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">
                                    <h4>
                                        @if ($user_detail->leave_day == 'half_day')
                                            Half Day
                                        @elseif($user_detail->leave_day == 'full_day')
                                            Full Day
                                        @else
                                            Unknown Status
                                        @endif
    
                                    </h4></h4>
                            </div>
                        </div>


                        <div class="row " style="padding-left:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4"
                            style="font-family: 'Font Awesome 5 Free'!important;">
                                <h4><strong>Leave Count</strong> :</h4>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">
                                    {{ strip_tags($user_detail->leave_count) }}</h4>
                            </div>
                        </div>
                        <div class="row " style="padding-left:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4"
                                style="font-family: 'Font Awesome 5 Free'!important;">
                                <h4><strong>Leave Type Name</strong> :</h4>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">
                                    {{ strip_tags($user_detail->leave_type_name) }}</h4>
                            </div>
                        </div>
                        <div class="row " style="padding-left:10px;">
                            <div class="col-lg-4 col-md-4 col-sm-4"
                            style="font-family: 'Font Awesome 5 Free'!important;">
                                <h4><strong>Reason</strong> :</h4>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <h4 style="font-family: 'Font Awesome 5 Free'!important;">
                                    {{ strip_tags($user_detail->reason) }}</h4>
                            </div>
                        </div>


                    </div>
                    <a><button data-toggle="tooltip" onclick="printInvoice()" title="Employee Details"
                            class="btn btn-primary print-btn m-4 print-button" style="margin-top: 20px;">Print</button></a>
                </div>
            </div>
        </div>
        <script>
            // function printInvoice() {
            //     // Get the content you want to print
            //     var contentToPrint = document.getElementById("printableArea").innerHTML;

            //     // Open a new window
            //     var printWindow = window.open('', '', 'height=600,width=800');

            //     // Write the content to the new window with proper styles
            //     printWindow.document.write('<html><head><title>Print</title>');
            //     printWindow.document.write('<style>');
            //     printWindow.document.write(
            //         'body { font-family: Arial, sans-serif; margin: 0; padding: 10px; font-size:30px; font-family: fangsong; border: 1px solid black;}'
            //     ); // Add padding to body
            //     printWindow.document.write(
            //         '#printableArea { width: 100%; overflow: hidden;  }'); // Ensure full width of content
            //     printWindow.document.write('.borderpage { border: 1px solid black; }'); // Corrected CSS for company-name-size

            //     printWindow.document.write('</style>');
            //     printWindow.document.write('</head><body>');
            //     printWindow.document.write(contentToPrint);
            //     printWindow.document.write('</body></html>');

            //     // Close the document to render
            //     printWindow.document.close();
            //     printWindow.focus();

            //     // Trigger the print dialog
            //     printWindow.print();

            //     // Close the print window after printing
            //     printWindow.close();
            // }

            function printInvoice() {
    var contentToPrint = document.getElementById("printableArea").innerHTML;

    var printWindow = window.open('', '', 'height=600,width=800');

    printWindow.document.write('<html><head><title>Print</title>');
    
    // Include Bootstrap CSS for proper styling
    printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">');

    printWindow.document.write('<style>');
    printWindow.document.write(`
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            font-size: 14px;
             border: 2px solid black;
        padding: 20px;
        margin: 10px;
        }
        #printableArea {
            width: 100%;
            overflow: hidden;
            padding: 20px;
            border: 1px solid black;
        }
        h3, h4, h4 {
            font-family: Arial, sans-serif;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
        }
        .col-lg-4 {
            font-weight: bold;
        }
        img {
            max-width: 100px;
            height: auto;
        }
    `);
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    
    printWindow.document.write(contentToPrint);

    printWindow.document.write('</body></html>');

    printWindow.document.close();
    printWindow.focus();

    printWindow.print();
    printWindow.close();
}

        </script>
        <!-- content-wrapper ends -->
    @endsection
