@extends('admin.layouts.master')
@section('content')

    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">

        <div class="emp-form-header">
            <h4><i class="fa fa-edit mr-2"></i> Edit Finance Document</h4>
            <a href="{{ route('list-doc-upload-fianace') }}" class="emp-form-close" title="Close">&times;</a>
        </div>

        <div class="emp-form-body">

            @if (session('msg'))
                <div class="alert alert-{{ session('status') }}">{{ session('msg') }}</div>
            @endif
            @if (Session::has('status'))
                <div class="alert alert-{{ Session::get('status') }} alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{ ucfirst(Session::get('status')) }}!</strong> {{ Session::get('msg') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('update-doc-upload-fianace') }}" method="POST" id="editDesignsForm" enctype="multipart/form-data">
                @csrf

                <a class="btn btn-sm btn-primary ml-3 mb-3">
                    <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                </a>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="dynamicTable">
                        <tr>
                            <th>Product Name</th>
                            <th>Product Qty</th>
                            <th>Product Price</th>
                            <th>Product Unit</th>
                            <th>Action</th>
                        </tr>
                        {{-- @foreach ($editData as $key => $editDataNew) --}}
                        <tr>
                            <input type="hidden" name="" id="" class="form-control" value="" placeholder="">
                            <td><input type="text" name="" value="" placeholder="Enter Product Name" class="form-control" /></td>
                            <td><input type="text" name="" value="" placeholder="Enter Product Quantity" class="form-control" /></td>
                            <td><input type="text" name="" value="" placeholder="Enter Product Price" class="form-control" /></td>
                            <td><input type="text" name="" value="" placeholder="Enter Product Unit" class="form-control" /></td>
                            <td>
                                <a data-id="" class="delete-btn btn btn-danger m-1" title="Delete"><i class="fas fa-archive"></i></a>
                                {{-- <button type="button" class="btn btn-danger remove-tr" data-row-id="{{ $editDataNew->designs_details_id }}">Remove</button> --}}
                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    </table>
                </div>

                {{-- @foreach ($editData as $key => $editDataNew) @if($key == 0) --}}
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="text" class="form-control" id="design_page" name="design_page" placeholder=" " value="">
                            <label for="design_page">Product Page</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="text" class="form-control" id="project_name" name="project_name" placeholder=" " value="">
                            <label for="project_name">Project Name</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="text" class="form-control" id="time_allocation" name="time_allocation" placeholder=" " value="">
                            <label for="time_allocation">Time Allocated For Product</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                        <div class="fl-group">
                            <input type="file" class="form-control" accept="image/*" id="image" name="image">
                            <label for="image">Image</label>
                            <div id="oldImageDisplay">
                                {{-- @if (isset($editDataNew->image)) --}}
                                <b>Image Preview: </b>
                                <img src="" alt="Old Image" style="max-width: 100px;">
                                {{-- @endif --}}
                            </div>
                            <div id="selectedImageDisplay" style="display: none;">
                                <b>Image Preview: </b>
                                <img src="" alt="Selected Image" style="max-width: 100px;">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endif @endforeach --}}

                <div class="col-md-12 text-center mt-4">
                    <button class="btn btn-sm btn-bg-colour" type="submit">Update Data</button>
                    <a href="{{ route('list-doc-upload-fianace') }}" class="btn btn-sm btn-primary ml-2">Cancel</a>
                </div>

            </form>
        </div>

    </div>
    </div></div></div></div>

    <form method="POST" action="{{ route('delete-addmore') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            var i = {!! count($editData) !!};

            $("#add").click(function() {
                ++i;
                $("#dynamicTable").append(
                    '<tr><td><input type="text" name="design_name_' + i + '" placeholder="Enter Product Name" class="form-control" /></td><td><input type="text" name="product_quantity_' + i + '" placeholder="Enter Product Quantity" class="form-control" /></td><td><input type="text" name="product_size_' + i + '" placeholder="Enter Product Price" class="form-control" /></td><td><input type="text" name="product_unit_' + i + '][product_unit_]" placeholder="Enter Product Unit" class="form-control" /></td><td><a class="delete-btn btn btn-danger m-1 remove-tr" title="Delete Tender"><i class="fas fa-archive"></i></a></td></tr>'
                );
            });

            $(document).on("click", ".remove-tr", function() {
                $(this).parents("tr").remove();
            });
        });
    </script>
    <script>
        $('.delete-btn').click(function(e) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#delete_id").val($(this).attr("data-id"));
                    $("#deleteform").submit();
                }
            });
        });
    </script>
@endsection
