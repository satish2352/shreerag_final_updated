@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Accepted BOM Business Wise List</h1>

                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true"
                                        data-show-refresh="false" data-key-events="true" data-show-toggle="true"
                                        data-resizable="true" data-cookie="true" data-cookie-id-table="saveId"
                                        data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                        <thead>
                                            <tr>
                                                <th data-field="id">ID</th>
                                                <th data-field="date" data-editable="false">Sent Date</th>
                                                <th data-field="product_name" data-editable="false">Product Name</th>
                                                <th data-field="quantity" data-editable="false">Quantity</th>
                                                <th data-field="description" data-editable="false">Description</th>
                                                <th data-field="design_image" data-editable="false">Design</th>
                                                <th data-field="bom_image" data-editable="false">Estimated BOM</th>
                                                <th data-field="total_estimation_amount" data-editable="false">Total
                                                    Estimation Amount</th>
                                                <th data-field="remark_by_estimation" data-editable="false">Estimation
                                                    Remark</th>
                                                @if (session('role_id') == 15)
                                                    <th data-field="action" data-editable="false">Action</th>
                                                @else
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_output as $data)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td> {{ $data->updated_at ? $data->updated_at->format('d-m-Y') : 'N/A' }}
                                                    </td>
                                                    <td>{{ ucwords($data->product_name) }}</td>
                                                    <td>{{ ucwords($data->quantity) }}</td>
                                                    <td>{{ ucwords($data->description) }}</td>
                                                    <td> <a class="img-size" target="_blank"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['design_image'] }}"
                                                            alt="Design"> Click to view</a>
                                                    </td>
                                                    <td> <a class="img-size"
                                                            href="{{ Config::get('FileConstant.DESIGNS_VIEW') }}{{ $data['bom_image'] }}"
                                                            alt="bill of material">Click to download</a>
                                                    </td>
                                                    <td>{{ ucwords($data->total_estimation_amount) }}</td>
                                                    <td>{{ ucwords($data->remark_by_estimation) }}</td>
                                                    @if (session('role_id') == 15)
                                                        <td>
                                                            <form action="{{ route('send-to-production', $data->id) }}"
                                                                method="POST" class="send-to-production-form">
                                                                @csrf
                                                                <button class="btn btn-sm btn-bg-colour" type="submit">Send
                                                                    to Production</button>
                                                            </form>
                                                        </td>
                                                    @else
                                                    @endif
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
 @push('scripts')  
    <script>
        $(document).on('submit', '.send-to-production-form', function(e) {
            e.preventDefault(); // prevent default form submission
            let form = this;

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to send Accepted BOM to production?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, send it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // submit the form if confirmed
                }
            });
        });
    </script>
    @endpush
@endsection
