@extends('admin.layouts.master')
@section('content')
    <div class="data-table-area mg-tb-15">
    <div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
    <div class="emp-form-card">
        <div class="emp-form-header">
            <h4><i class="fa fa-edit mr-2"></i> Edit Design Upload</h4>
            <a href="{{ route('list-products') }}" class="emp-form-close">&times;</a>
        </div>
        <div class="emp-form-body">
            @if (session('msg'))
                <div class="alert alert-{{ session('status') }}">
                    {{ session('msg') }}
                </div>
            @endif
            @if (Session::has('status'))
                <div class="alert alert-{{ Session::get('status') }} alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{ ucfirst(Session::get('status')) }}!</strong>
                    {{ Session::get('msg') }}
                </div>
            @endif

            <form action="{{ route('update-products') }}" method="POST" id="editDesignsForm" enctype="multipart/form-data">
                @csrf

                <a class="btn btn-sm btn-primary ml-3"> <button
                        type="button" name="add" id="add" class="btn btn-success">Add
                        More</button></a>

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
                            <input type="hidden" name="" id=""
                                class="form-control" value="" placeholder="">
                            <td>
                                <input type="text" name="" value=""
                                    placeholder="Enter Product Name" class="form-control" />
                            </td>
                            <td><input type="text" name="" value=""
                                    placeholder="Enter Product Quantity" class="form-control" />
                            </td>
                            <td><input type="text" name="" value=""
                                    placeholder="Enter Product Price" class="form-control" />
                            </td>
                            <td><input type="text" name="" value=""
                                    placeholder="Enter Product Unit" class="form-control" />
                            </td>
                            {{-- <td><button type="button" class="btn btn-danger remove-tr">Remove</button></td> --}}
                            <td>
                                <a data-id="" class="delete-btn btn btn-danger m-1"
                                    title="Delete Tender"><i class="fas fa-archive"></i></a>
                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    </table>
                    {{-- @foreach ($editData as $key => $editDataNew)
                    @if ($key == 0) --}}
                    <div class="row mt-3">
                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="file" name="english_image"
                                    class="form-control" id="english_image" accept="image/*">
                                <label for="english_image">Image</label>
                                @if ($errors->has('english_image'))
                                    <span class="fl-error">{{ $errors->first('english_image') }}</span>
                                @endif
                            </div>
                            <img id="english"
                                src="{{ Config::get('DocumentConstant.SLIDER_VIEW') }}{{ $slider->english_image }}"
                                class="img-fluid img-thumbnail" width="150">
                            <img id="english_imgPreview" src="#" alt="pic"
                                class="img-fluid img-thumbnail" width="150"
                                style="display:none">
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 fl-col">
                            <div class="fl-group">
                                <input type="file" name="marathi_image"
                                    id="marathi_image" accept="image/*"
                                    class="form-control">
                                <label for="marathi_image">&#2459;&#2366;&#2479;&#2494;&#2458;&#2367;&#2468;&#2381;&#2352;</label>
                                @if ($errors->has('marathi_image'))
                                    <span class="fl-error">{{ $errors->first('marathi_image') }}</span>
                                @endif
                            </div>
                            <img id="marathi"
                                src="{{ Config::get('DocumentConstant.SLIDER_VIEW') }}{{ $slider->marathi_image }}"
                                class="img-fluid img-thumbnail" width="150">
                            <img id="marathi_imgPreview" src="#" alt="pic"
                                class="img-fluid img-thumbnail" width="150"
                                style="display:none">
                        </div>
                    </div>
                    {{-- @endif --}}
                    {{-- @endforeach --}}
                </div>

                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ route('list-products') }}" class="btn btn-white mr-2">Cancel</a>
                        <button class="btn btn-sm btn-primary login-submit-cs" type="submit">Update Data</button>
                    </div>
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
                    '<tr><td><input type="text" name="design_name_' + i +
                    '" placeholder="Enter Product Name" class="form-control" /></td><td><input type="text" name="product_quantity_' +
                    i +
                    '" placeholder="Enter Product Quantity" class="form-control" /></td><td><input type="text" name="product_size_' +
                    i +
                    '" placeholder="Enter Product Price" class="form-control" /></td><td><input type="text" name="product_unit_' +
                    i +
                    '][product_unit_]" placeholder="Enter Product Unit" class="form-control" /></td><td><a class="delete-btn btn btn-danger m-1 remove-tr" title="Delete Tender"><i class="fas fa-archive"></i></a></td></tr>'
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
