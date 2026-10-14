@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
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
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                {{-- Page header --}}
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 style="margin:0; font-weight:600; color:#1a3a6b;">Business Sent For Design</h5>
                                </div>

                                {{-- Search bar --}}
                                <form method="GET" action="{{ url()->current() }}">
                                    <input type="hidden" id="business_id" name="business_id">
                                    <div class="d-flex justify-content-end align-items-center mb-3" style="gap:8px;">
                                        <div style="flex:0 0 340px; max-width:100%;">
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                class="form-control"
                                                placeholder="Search Project Name / Product Name / PO No.">
                                        </div>
                                        <button type="submit" class="btn btn-primary filterbg">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <a href="{{ url()->current() }}" class="btn btn-secondary">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </a>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar"> --}}
                                        <thead>
                                            <tr>
                                                <th data-field="id">Sr.No.</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="project_name" data-editable="false">Project name</th>
                                                <th data-field="customer_po_number" data-editable="false">PO Number</th>
                                                <th data-field="title" data-editable="false">Customer Name</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="total_amount" data-editable="false">Total Amount</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="rate" data-editable="false">Rate</th>
                                                <th data-field="discription" data-editable="false">Description</th>
                                                <th data-field="remarks" data-editable="false">Remark</th>
                                                <th data-field="business_pdf" data-editable="false">File</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>
                                                    <td> {{ $data_output->firstItem() + $loop->index }}
                                                    </td>
                                                    <td> {{ $data->created_at ? $data->created_at->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ ucwords($data->project_name) }}</td>
                                                    <td>{{ ucwords($data->customer_po_number) }}</td>
                                                    <td>{{ ucwords($data->title) }}</td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->total_amount) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->rate) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                    <td>
                                                        @if (!empty($data->business_pdf))
                                                            <a href="{{ Config::get('FileConstant.BUSINESS_PDF_VIEW') }}{{ $data->business_pdf }}"
                                                                target="_blank" class="btn btn-sm btn-info"
                                                                style="color: #fff">
                                                                <i class="fa fa-file-pdf-o"></i> View PDF
                                                            </a>
                                                        @else
                                                            <span class="text-muted">No File</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="12" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p>
                                                Showing {{ $data_output->firstItem() }} to {{ $data_output->lastItem() }}
                                                of {{ $data_output->total() }} rows
                                            </p>
                                        </div>

                                        <div class="col-md-6 d-flex justify-content-end mt-3">
                                            {{ $data_output->appends(request()->query())->onEachSide(1)->links() }}
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
