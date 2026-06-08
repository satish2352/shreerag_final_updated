@extends('admin.layouts.master')
@section('content')
<div class="data-table-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="sparkline13-list">
                    <div class="sparkline13-hd">
                        <div class="main-sparkline13-hd">
                            <h1>Search By <span class="table-project-n">PO Number</span></h1>
                        </div>
                    </div>

                    @if (Session::get('status') == 'success')
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            <strong>Success!</strong> {{ Session::get('msg') }}
                        </div>
                    @endif
                    @if (Session::get('status') == 'error')
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            <strong>Error!</strong> {!! session('msg') !!}
                        </div>
                    @endif

                    <div class="sparkline13-graph">
                        {{-- Search Form --}}
                        <form method="GET" action="{{ route('search-by-po-no') }}" id="poSearchForm">
                            <div class="d-flex justify-content-end mb-3">
                                <div class="col-md-4">
                                    <input type="text" name="purchase_orders_id"
                                        value="{{ $searchPoNo ?? '' }}"
                                        class="form-control"
                                        placeholder="Search by PO Number">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary filterbg" type="submit">Search</button>
                                    <a href="{{ route('search-by-po-no') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>

                        {{-- Results Table --}}
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr.No.</th>
                                            <th>PO Number</th>
                                            <th>Product Name</th>
                                            <th>Description</th>
                                            <th>Remark</th>
                                            <th>Purchase Order</th>
                                            <th>Count</th>
                                            <th>Generate Gate Pass</th>
                                            <th>Close PO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($data_output as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $data->purchase_orders_id }}</strong></td>
                                                <td>{{ ucwords($data->product_name) }}</td>
                                                <td>{{ ucwords($data->description) }}</td>
                                                <td>{{ ucwords($data->remarks) }}</td>
                                                <td>
                                                    <a href="{{ route('list-po-details', [base64_encode($data->gatepass_id), base64_encode($data->purchase_orders_id)]) }}">
                                                        <button class="btn btn-sm btn-bg-colour">Check PO Details</button>
                                                    </a>
                                                </td>
                                                <td>{{ $data->gatepass_count }}</td>
                                                <td>
                                                    <a href="{{ route('add-gatepass-with-po', base64_encode($data->purchase_orders_id)) }}">
                                                        <button class="btn btn-sm btn-bg-colour">Generate Gate Pass</button>
                                                    </a>
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('close-po') }}"
                                                          onsubmit="return confirm('Mark PO {{ $data->purchase_orders_id }} as CLOSED? It will no longer appear on this page and no more gate passes can be generated from here.');">
                                                        @csrf
                                                        <input type="hidden" name="purchase_orders_id" value="{{ $data->purchase_orders_id }}">
                                                        <button type="submit" class="btn btn-sm btn-danger">Close PO</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No Record Found</td>
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
    </div>
</div>
@endsection
