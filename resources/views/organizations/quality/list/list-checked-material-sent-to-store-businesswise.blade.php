
@extends('admin.layouts.master')
@section('content')
    <style>
        .sparkline13-list-product {
        background-color: #fff;
        padding: 22px;
        margin-top: 72px;
        margin-bottom: 80px;
    }
    </style>
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list-product">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Material Need To Sent To<span class="table-project-n"> Production</span> Department Business Wise</h1>
                            </div>
                        </div>
                        <form method="GET" action="{{ route('list-material-sent-to-quality-businesswise', ['id' => $id]) }}">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="from_date">From Date</label>
                                    <input type="date" name="from_date" class="form-control" value="{{ request()->from_date }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="to_date">To Date</label>
                                    <input type="date" name="to_date" class="form-control" value="{{ request()->to_date }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="year">Year</label>
                                    <select name="year" class="form-control">
                                        <option value="">Select Year</option>
                                        @foreach (yearOptions() as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="month">Month</label>
                                    <select name="month" class="form-control">
                                        <option value="">Select Month</option>
                                        @foreach (range(1, 12) as $month)
                                            <option value="{{ $month }}" {{ request()->month == $month ? 'selected' : '' }}>
                                                {{ date("F", mktime(0, 0, 0, $month, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12 mt-4" style="display: flex; justify-content: center; margin-top:20px;">
                                    <button type="submit" class="btn btn-sm btn-bg-colour">Filter</button>
                                    <a href="{{ route('list-material-sent-to-quality-businesswise', ['id' => $id]) }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                
                                                <th data-field="id">Sr.No.</th> 
                                                <th data-field="purchase_orders_id" data-editable="false">Purchase Order ID</th>
                                                <th data-field="grn" data-editable="false">GRN</th>
                                                <th data-field="client_name" data-editable="false">Client Name</th>
                                                <th data-field="vendor_company_name" data-editable="false">Client Company Name</th>
                                                <th data-field="email" data-editable="false">Email</th>
                                                <th data-field="contact_no" data-editable="false">Phone Number</th>
                                                <th data-field="vendor_address" data-editable="false">Address</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    
                                                    <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->purchase_orders_id }}</td>
                                            <td>
                                                <div style="display: flex; align-items: center;">
                                                    <a href="{{ route('list-grn-details-po-tracking', [base64_encode($data->purchase_orders_id), base64_encode($data->business_details_id), base64_encode($data->grn_id)]) }}">
                                                        <button data-toggle="tooltip" title="GRN Details" class="pd-setting-ed">GRN Details</button>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>{{ $data->vendor_name }}</td>
                                           <td>{{ $data->vendor_company_name }}</td>
                                           <td>{{ $data->vendor_email }}</td> 
                                           <td>{{ $data->contact_no }}</td> 
                                           <td>{{ $data->vendor_address }}</td> 
                                           
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
@endsection
