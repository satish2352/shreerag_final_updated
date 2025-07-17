@extends('admin.layouts.master')

@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="sparkline13-list">

                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center mb-3">
                <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-start align-items-center">
                    <h5 class="page-title">
                       Material List Report
                    </h5>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 show-btn-position">
                     <a href="{{ route('list-vendor-through-taken-material') }}" class=" ml-3"> <button type="submit" class="btn btn-primary filterbg">Back</button>
                   </a>
                </div>
            </div>
    {{-- Filters --}}
    <form method="GET" class="mb-3" action="{{ url()->current() }}">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
            </div>
            <div class="col-md-3">
                <select name="month" class="form-control">
                    <option value="">Select Month</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="year" class="form-control">
                    <option value="">Select Year</option>
                    @for ($y = date('Y'); $y >= 2022; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
          <div class="row mt-3">
    <div class="col-md-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary filterbg">Filter</button>
                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
            </div>

            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
             <div class="col-md-2">
             <div class="dropdown">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown">
                                            <i class="fa fa-download"></i> Export
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#" id="exportExcel">Export to Excel</a></li>
                                            <li><a class="dropdown-item" href="#" id="exportPdf">Export to PDF</a></li>
                                        </ul>
                                    </div>
             </div>
        </div>
    </form>

    {{-- Export Buttons --}}
    {{-- <div class="mb-2">
        <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['export_type' => 'excel'])) }}" class="btn btn-success btn-sm">Export Excel</a>
        <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->all(), ['export_type' => 'pdf'])) }}" class="btn btn-danger btn-sm">Export PDF</a>
    </div> --}}

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Sr. No.</th>
                      <th>Date</th>
                    <th>PO ID</th>
                    <th>Part Number</th>
                    <th>Item Name</th>
                    <th>PO Quantity</th>
                    <th>Actual Quantity (Sum)</th>
                    <th>Accepted Quantity</th>
                    <th>Rejected Quantity</th>
                    <th>Remaining Quantity</th>
                    <th>Unit</th>
                    <th>Rate</th>
                    <th>Discount</th>
                  
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $material)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                         <td>{{ \Carbon\Carbon::parse($material->updated_at)->format('d-m-Y') }}</td>
                        <td>{{ $material->purchase_order_id }}</td>
                        <td>{{ $material->part_number }}</td>
                        <td>{{ $material->part_description }}</td>
                        <td>{{ number_format((float) $material->max_quantity, 2) }}</td>
                        <td>{{ number_format((float) $material->sum_actual_quantity, 2) }}</td>
                        <td>{{ number_format((float) $material->tracking_accepted_quantity, 2) }}</td>
                        <td>{{ number_format((float) $material->tracking_rejected_quantity, 2) }}</td>
                        <td>{{ number_format((float) $material->remaining_quantity, 2) }}</td>
                        <td>{{ $material->unit_name }}</td>
                        <td>{{ number_format((float) $material->po_rate, 2) }}</td>
                        <td>{{ number_format((float) $material->po_discount, 2) }}%</td>
                      </tr>
                @empty
                    <tr>
                        <td colspan="14" class="text-center">No material data available for this vendor.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
