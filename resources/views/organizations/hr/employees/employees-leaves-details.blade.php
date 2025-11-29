
@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list" style="padding-bottom: 100px">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>   Employee Leaves Details </h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="row d-flex">
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <strong>Name:</strong> {{ $user_detail[0]->f_name }} {{ $user_detail[0]->m_name }} {{ $user_detail[0]->l_name }}<br>
                                    <strong>Role:</strong> {{ $user_detail[0]->role_name }}
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <a href="{{ url()->previous() }}" class="btn btn-bg-colour mt-3 mb-2">Back</a>
                                </div>
                            </div>
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div class="table-responsive">
                                    @if(!empty($user_detail) && isset($user_detail[0]))
                                    <!-- Employee Info -->
                                    <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                        data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="false"
                                        data-key-events="true" data-show-toggle="true" data-resizable="true"
                                        data-cookie="true" data-cookie-id-table="saveId" data-show-export="true"
                                        data-click-to-select="true" data-toolbar="#toolbar">

                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                 <th>Year</th>
                                                <th>Leave Type</th>
                                <th>Total Leaves</th>
                                <th>Total Leaves Taken</th>
                                <th>Balance Leaves</th>
                                {{-- <th>Month</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user_detail as $index => $leave)
                                           <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $leave->leave_year ?? '-' }}</td>
                                                <td>{{ $leave->leave_type_name ?? '-' }}</td>

                                                <!-- Total Leaves = current_year_leave + carry_forward -->
                                                <td>{{ $leave->total_available_leaves ?? '0' }}</td>

                                                <!-- Leaves Taken -->
                                                <td>{{ $leave->total_leaves_taken ?? '0' }}</td>

                                                <!-- Final Remaining Leaves -->
                                                <td><b>{{ $leave->remaining_leaves ?? '0' }}</b></td>

                                                {{-- <td>{{ $leave->month_name ?? '-' }}</td> --}}
                                            </tr>
                                            @endforeach

                                        </tbody>

                                    </table>
                                    @else
                                    <p class="text-danger">No leave details found.</p>
                                @endif
                                
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
        $(document).ready(function() {
            $(".delete-btn").click(function(e) {
                e.preventDefault();
                var deleteUrl = "{{ route('delete-employee', ':id') }}";
                var userId = $(this).data('id');
                deleteUrl = deleteUrl.replace(':id', userId);

                Swal.fire({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to delete route
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
    <script>
        $('.show-btn').click(function(e) {
            alert('hii');
            $("#show_id").val($(this).attr("data-id"));
            $("#showform").submit();
        })
    </script>
     <script>
        $('.leavesdetails-btn').click(function(e) {
            alert('hii');
            $("#leavesdetails_id").val($(this).attr("data-id"));
            $("#leavesdetailsform").submit();
        })
    </script>
    
    <form method="POST" action="{{ url('/show-employee') }}" id="showform">
        @csrf
        <input type="hidden" name="show_id" id="show_id" value="">
    </form>
    <form method="POST" action="{{ url('/users-leaves-details') }}" id="leavesdetailsform">
        @csrf
        <input type="hidden" name="leavesdetails_id" id="leavesdetails_id" value="">
    </form>
@endpush
@endsection
