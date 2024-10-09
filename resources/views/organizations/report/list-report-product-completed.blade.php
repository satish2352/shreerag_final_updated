<!-- Static Table Start -->
@extends('admin.layouts.master')
@section('content')
    <style>
        .fixed-table-loading {
            display: none;
        }

        #table thead th {
            white-space: nowrap;
        }

        #table thead th {
            width: 300px !important;
            padding-right: 49px !important;
            padding-left: 20px !important;
        }

        .custom-datatable-overright table tbody tr td {
            padding-left: 19px !important;
            padding-right: 5px !important;
            font-size: 14px;
            text-align: left;
        }
        a{
            color: black;
        }
    </style>

    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Product Completed List</h1>
                            </div>
                        </div>

                        @if (Session::get('status') == 'success')
                            <div class="alert alert-success alert-success-style1">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                <p><strong>Success!</strong> {{ Session::get('msg') }}</p>
                            </div>
                        @endif
                        @if (Session::get('status') == 'error')
                            <div class="alert alert-danger alert-mg-b alert-success-style4">
                                <button type="button" class="close sucess-op" data-dismiss="alert" aria-label="Close">
                                    <span class="icon-sc-cl" aria-hidden="true">&times;</span>
                                </button>
                                <i class="fa fa-times adminpro-danger-error admin-check-pro" aria-hidden="true"></i>
                                <p><strong>Danger!</strong> {{ Session::get('msg') }}</p>
                            </div>
                        @endif
                          <form method="GET" action="{{ route('list-product-completed-report') }}">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="from_date">From Date</label>
                                                <input type="date" name="from_date" class="form-control" value="{{ request()->from_date }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="to_date">To Date</label>
                                                <input type="date" name="to_date" class="form-control" value="{{ request()->to_date }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label for="year">Year</label>
                                                <select name="year" class="form-control">
                                                    <option value="">Select Year</option>
                                                    @for ($i = now()->year; $i >= 2010; $i--)
                                                        <option value="{{ $i }}" {{ request()->year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="col-md-2">
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
                                            <div class="col-md-2">
                                                <label for="year"></label>
                                                <p style="font-size: 18px;"><strong>Total Records: {{ $total_count }}</strong></p>
                                            </div>
                                        </div>
                                            <div class="row d-flex justify-content-center">
                                                {{-- <div class="d-flex justify-content-center"> --}}
                                                <div class="col-md-12 mt-4" style="display: flex; justify-content: center; margin-top:20px;">
                                                    <button type="submit" class="btn btn-primary">Filter</button>
                                                    <a href="{{ route('list-product-completed-report') }}" class="btn btn-secondary">Reset</a>
                                                {{-- </div> --}}
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </form>
                                    <div class="sparkline13-list" style="margin-top: 0px !important;">
                                        <div class="datatable-dashv1-list custom-datatable-overright">
                                            <div id="toolbar">
                                                <select class="form-control">
                                                    <option value="">Export Basic</option>
                                                    <option value="all">Export All</option>
                                                    <option value="selected">Export Selected</option>
                                                </select>
                                            </div>
            
                                    <div class="table-responsive" style="background-color: #fff;">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="title" data-editable="false">customer Name</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="truck_no" data-editable="false">Truck Number</th>
                                                <th data-field="outdoor_no" data-editable="false">Outdoor Number</th>
                                                <th data-field="gate_entry" data-editable="false">Gate Entry</th>
                                                <th data-field="remark" data-editable="false">Dispatch Remark</th>
                                                <th data-field="updated_at" data-editable="false">Dispatch Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td>{{ ucwords($data->title) }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->truck_no) }}</td>
                                                    <td>{{ ucwords($data->outdoor_no) }}</td>
                                                    <td>{{ ucwords($data->gate_entry) }}</td>
                                                    <td>{{ ucwords($data->remark) }}</td>  
                                                    <td>{{ ucwords($data->updated_at) }}</td>
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
            </div>
        </div>
    </div>

 
    
@endsection
