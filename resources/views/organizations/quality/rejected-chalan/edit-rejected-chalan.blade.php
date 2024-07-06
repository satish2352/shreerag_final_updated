@extends('admin.layouts.master')
@section('content')
    <style>
        label {
            margin-top: 20px;
        }

        label.error {
            color: red;
            /* Change 'red' to your desired text color */
            font-size: 12px;
            /* Adjust font size if needed */
            /* Add any other styling as per your design */
        }
    </style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="sparkline12-list">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <center>
                            <h1>Edit GRN Data</h1>
                        </center>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="basic-login-form-ad">
                        <div class="row">
                            @if (session('msg'))
                                <div class="alert alert-{{ session('status') }}">
                                    {{ session('msg') }}
                                </div>
                            @endif

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                @if (Session::has('status'))
                                    <div class="col-md-12">
                                        <div class="alert alert-{{ Session::get('status') }} alert-dismissible"
                                            role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <strong>{{ ucfirst(Session::get('status')) }}!</strong>
                                            {{ Session::get('msg') }}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="all-form-element-inner">
                                        <form action="{{ route('update-products', 
                                       ) }}"
                                            method="POST" id="editDesignsForm" enctype="multipart/form-data">
                                            @csrf
                                           
                                            <a
                                             {{-- href="{{ route('add-more-data') }}" --}}
                                            class="btn btn-sm btn-primary ml-3"> <button type="button" name="add" id="add" class="btn btn-success">Add More</button></a>

                                            <div class="container-fluid">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

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
                                                            <input type="hidden" name=""
                                                                id="" class="form-control"
                                                                value=""
                                                                placeholder="">
                                                            <td>
                                                                <input type="text"
                                                                    name=""
                                                                    value=""
                                                                    placeholder="Enter Product Name" class="form-control" />
                                                            </td>
                                                            <td><input type="text"
                                                                    name=""
                                                                    value=""
                                                                    placeholder="Enter Product Quantity"
                                                                    class="form-control" /></td>
                                                            <td><input type="text"
                                                                    name=""
                                                                    value=""
                                                                    placeholder="Enter Product Price"
                                                                    class="form-control" /></td>
                                                            <td><input type="text"
                                                                    name=""
                                                                    value=""
                                                                    placeholder="Enter Product Unit" class="form-control" />
                                                            </td>
                                                            {{-- <td><button type="button" class="btn btn-danger remove-tr">Remove</button></td> --}}
                                                            <td>
                                                                
                                                                <a data-id=""
                                                                    class="delete-btn btn btn-danger m-1"
                                                                    title="Delete Tender"><i
                                                                        class="fas fa-archive"></i></a>
                                                         


                                                                {{-- <button type="button" class="btn btn-danger remove-tr " data-row-id="{{ $editDataNew->designs_details_id }}">Remove</button> --}}
                                                            </td>
                                                        </tr>
                                                    {{-- @endforeach --}}
                                                </table>
                                                {{-- @foreach ($editData as $key=> $editDataNew)
                                                @if($key == 0) --}}
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <label for="design_page">Product Page:</label>
                                                                <input type="text" class="form-control" id="design_page" name="design_page" value="" placeholder="Enter Design Page">
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <label for="project_name">Project Name:</label>
                                                                <input type="text" class="form-control" id="project_name" name="project_name" value="" placeholder="Enter Project Name">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <label for="time_allocation">Time Allocated For Product:</label>
                                                                <input type="text" class="form-control" id="time_allocation" name="time_allocation" value="" placeholder="Enter Time Allocated For Product">
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                                <label for="image">Image:</label>
                                                                <input type="file" class="form-control" accept="image/*" id="image" name="image">
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
                                                {{-- @endif --}}
                                            {{-- @endforeach --}}
                                                <div class="login-btn-inner">
                                                    <div class="row">
                                                        <div class="col-lg-5"></div>
                                                        <div class="col-lg-7">
                                                            <div class="login-horizental cancel-wp pull-left">
                                                                <a href="{{ route('list-products') }}"
                                                                    class="btn btn-white"
                                                                    style="margin-bottom:50px">Cancel</a>
                                                                <button class="btn btn-sm btn-primary login-submit-cs"
                                                                    type="submit" style="margin-bottom:50px">Update Data</button>
                                                               
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <form method="POST" action="{{ route('delete-addmore') }}" id="deleteform">
        @csrf
        <input type="hidden" name="delete_id" id="delete_id" value="">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
 
<script>
    // $(document).ready(function() {
    //     var i = {!! count($editData) !!}; // Initialize i with the number of existing rows

    //     $("#add").click(function() {
    //         ++i;

    //         $("#dynamicTable").append(
    //             '<tr><td><input type="text" name="design_name_' + i + '" placeholder="Enter Product Name" class="form-control" /></td><td><input type="text" name="product_quantity_' + i + '" placeholder="Enter Product Quantity" class="form-control" /></td><td><input type="text" name="product_size_' + i + '" placeholder="Enter Product Price" class="form-control" /></td><td><input type="text" name="product_unit_' + i + '" placeholder="Enter Product Unit" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
    //         );
    //     });

    //     $(document).on("click", ".remove-tr", function() {
    //         $(this).parents("tr").remove();
    //     });

    //     // Hide the "Add More" button initially if needed
    //     // $("#add").hide();
    // });

    $(document).ready(function() {
    var i = {!! count($editData) !!}; // Initialize i with the number of existing rows

    $("#add").click(function() {
        ++i;

        $("#dynamicTable").append(
            '<tr><td><input type="text" name="design_name_' + i + '" placeholder="Enter Product Name" class="form-control" /></td><td><input type="text" name="product_quantity_' + i + '" placeholder="Enter Product Quantity" class="form-control" /></td><td><input type="text" name="product_size_' + i + '" placeholder="Enter Product Price" class="form-control" /></td><td><input type="text" name="product_unit_' + i + '][product_unit_]" placeholder="Enter Product Unit" class="form-control" /></td><td><a class="delete-btn btn btn-danger m-1 remove-tr" title="Delete Tender"><i class="fas fa-archive"></i></a></td></tr>'
        );
    });

    $(document).on("click", ".remove-tr", function() {
        $(this).parents("tr").remove();
    });

    // Hide the "Add More" button initially if needed
    // $("#add").hide();
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
        })

    });
</script>





{{-- <script>
 $(document).on("click", ".remove-tr", function() {
    var rowId = $(this).data('row-id');
    var row = $(this).closest('tr');
    alert(rowId);
    $.ajax({
    url: '/remove-design-details/' + rowId,
    type: 'DELETE',
    data: {
        _token: '{{ csrf_token() }}'
    },
    success: function(response) {
        row.remove();
        alert(response.msg);
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
        alert('Error occurred. Please check console for details.');
    }
});
});

</script> --}}
@endsection
