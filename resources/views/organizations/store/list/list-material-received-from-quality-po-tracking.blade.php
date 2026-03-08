@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Material Received Purchase Order Wise Tracking</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <form method="GET" action="{{ url()->current() }}">
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="col-md-4">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="form-control" placeholder="Search Project / Product / PO">
                                            </div>
                                            <div class="col-md-2 ">
                                                <button class="btn btn-primary filterbg">Search</button>
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-striped">
                                        {{-- <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar"> --}}
                                        <thead>
                                            <tr>
                                                <th data-field="id">ID</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="grn_date" data-editable="false">Description</th>
                                                <th data-field="purchase_id" data-editable="false">Remark</th>
                                                <th data-field="bom_image" data-editable="false">BOM</th>
                                                <th data-field="action" data-editable="false">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data_output as $data)
                                                <tr>

                                                    <td>{{ ($data_output->currentPage() - 1) * $data_output->perPage() + $loop->iteration }}
                                                    </td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td>{{ ucwords($data->remarks) }}</td>
                                                    <td> <a class="img-size"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                            alt="bill of material">Click to download</a>
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('list-material-received-from-quality-bussinesswise-tracking', $data->id) }}"><button
                                                                data-toggle="tooltip" title="Edit"
                                                                class="btn btn-sm btn-bg-colour"> Check Details</button></a>
                                                        &nbsp; &nbsp; &nbsp;
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        No Record Found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-end mt-3">
                                        {{ $data_output->onEachSide(1)->links() }}
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
