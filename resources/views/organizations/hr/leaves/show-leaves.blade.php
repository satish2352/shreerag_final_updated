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
                    <a href="{{ route('list-leaves-acceptedby-hr') }}" class="btn btn-sm btn-bg-colour ml-3">Back</a>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">

                <div style="background-color: #fff; padding:25px; margin-bottom: 100px;">

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
                                        <div style="font-size: 20px; font-weight: bold; text-transform: uppercase; line-height: 1.2;">
                                            {{ $getOrganizationData->company_name }}
                                        </div>
                                        <div style="margin-top: 5px; font-size: 12px;">
                                            {{ $getOrganizationData->address }}, CIN: {{ $getOrganizationData->cin_number }}<br>
                                            Phone No.: {{ $getOrganizationData->mobile_number }}, Email Id: {{ $getOrganizationData->email }}<br>
                                            GST No: {{ $getOrganizationData->gst_no }}
                                        </div>
                                    </td>

                                    <!-- Right Side: Empty -->
                                    <td style="width: 10%;"></td>
                                </tr>
                            </table>
                        </div>

                        <div style="display: flex;justify-content: center;">
                            <h5>Leave Application Form</h5>
                        </div>

                        <div class="w-75">
                            <div class="row print-row" style="padding-left:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4 left-side"><p><strong>Full Name</strong> :</p></div>
                                <div class="col-lg-8 col-md-8 col-sm-8 right-side">
                                    <p>{{ $user_detail['leave_details']->other_employee_name }}</p>
                                </div>
                            </div>
                            <div class="row print-row" style="padding-left:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4 left-side"><p><strong>Email Id</strong> :</p></div>
                                <div class="col-lg-8 col-md-8 col-sm-8 right-side">
                                    <p>{{ strip_tags($user_detail['leave_details']->u_email) }}</p>
                                </div>
                            </div>
                            <div class="row print-row" style="padding-left:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4 left-side"><p><strong>From Date</strong> :</p></div>
                                <div class="col-lg-8 col-md-8 col-sm-8 right-side">
                                    <p>{{ strip_tags($user_detail['leave_details']->leave_start_date) }}</p>
                                </div>
                            </div>
                            <div class="row print-row" style="padding-left:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4 left-side"><p><strong>To Date</strong> :</p></div>
                                <div class="col-lg-8 col-md-8 col-sm-8 right-side">
                                    <p>{{ strip_tags($user_detail['leave_details']->leave_end_date) }}</p>
                                </div>
                            </div>
                            <div class="row print-row" style="padding-left:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4 left-side"><p><strong>Leave Day</strong> :</p></div>
                                <div class="col-lg-8 col-md-8 col-sm-8 right-side">
                                    @if ($user_detail['leave_details']->leave_day == 'half_day')
                                        <p>Half Day</p>
                                    @elseif($user_detail['leave_details']->leave_day == 'full_day')
                                        <p>Full Day</p>
                                    @else
                                        <p>Unknown Status</p>
                                    @endif
                                </div>
                            </div>
                            <div class="row print-row" style="padding-left:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4 left-side"><p><strong>Leave Count</strong> :</p></div>
                                <div class="col-lg-8 col-md-8 col-sm-8 right-side">
                                    <p>{{ strip_tags($user_detail['leave_details']->leave_count) }}</p>
                                </div>
                            </div>
                            <div class="row print-row" style="padding-left:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4 left-side"><p><strong>Leave Type Name</strong> :</p></div>
                                <div class="col-lg-8 col-md-8 col-sm-8 right-side">
                                    <p>{{ strip_tags($user_detail['leave_details']->leave_type_name) }}</p>
                                </div>
                            </div>
                            <div class="row print-row" style="padding-left:10px;">
                                <div class="col-lg-4 col-md-4 col-sm-4 left-side"><p><strong>Reason</strong> :</p></div>
                                <div class="col-lg-8 col-md-8 col-sm-8 right-side">
                                    <p>{{ strip_tags($user_detail['leave_details']->reason) }}</p>
                                </div>
                            </div>

                            <!-- Leave Summary in Table Format -->
                            <div class="fix-bottom">
                                <h5 class="mt-3 mb-2 pl-3" style="padding-left: 10px;"><strong>Leave Summary</strong></h5>
                                <table class="table table-bordered table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Total Leave Count</th>
                                            <th>Total Leaves Taken</th>
                                            <th>Remaining Leaves</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user_detail['leave_summary'] as $leave)
                                            <tr>
                                                <td>{{ $leave->leave_type_name }}</td>
                                                <td>{{ $leave->leave_count }}</td>
                                                <td>{{ $leave->total_leaves_taken }}</td>
                                                <td>{{ $leave->remaining_leaves }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <button onclick="printInvoice()" title="Employee Details"
                            class="btn btn-primary print-btn m-4 print-button" style="margin-top: 20px;">
                        Print
                    </button>
                </div>
            </div>
        </div>

        <script>
            function printInvoice() {
                var contentToPrint = document.getElementById("printableArea").innerHTML;
                var printWindow = window.open('', '', 'height=600,width=800');

                printWindow.document.write('<html><head><title>Print</title>');
                printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">');
                printWindow.document.write('<style>');
                printWindow.document.write(`
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        padding: 20px;
                        font-size: 14px;
                        border: 2px solid black;
                    }
                    table {
                        border-collapse: collapse;
                        width: 100%;
                        margin-top: 15px;
                    }
                    table, th, td {
                        border: 1px solid black;
                    }
                    th, td {
                        padding: 6px;
                        text-align: center;
                    }
                    th {
                        background-color: #f2f2f2;
                    }
                    .row {
                        display: flex;
                        margin-bottom: 10px;
                    }
                    img {
                        max-width: 100px;
                        height: auto;
                    }
                    h3, h4, h5 {
                        font-family: Arial, sans-serif;
                    }
                `);
                printWindow.document.write('</style></head><body>');
                printWindow.document.write(contentToPrint);
                printWindow.document.write('</body></html>');

                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }
        </script>
    @endsection
