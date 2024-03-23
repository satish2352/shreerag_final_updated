@extends('admin.layouts.master')
@section('content')
<style>

.fixed-table-loading {
    display: none;
}
#table thead th {
    white-space: nowrap;
}
#table thead th{
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
 

</style>
<head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<div class="admintab-area mg-tb-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="tab-content-details shadow-reset">
                    
                    @foreach($detailsData as $data)
                    <h2>{{$data->company_name}}</h2>
                  @endforeach
                </div>
            </div>
        </div>
        
    </div>
</div>

<div class="single-product-tab-area mg-tb-15">
            <!-- Single pro tab review Start-->
            <div class="single-pro-review-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="review-tab-pro-inner">
                                <ul id="myTab3" class="tab-review-design">
                                    <li class="active"><a href="#description"><i class="fa fa-pencil" aria-hidden="true"></i> Profile</a></li>
                                    <li class=""><a href="#reviews"><i class="fa fa-file-image-o" aria-hidden="true"></i> Pictures</a></li>
                                    <li class=""><a href="#INFORMATION"><i class="fa fa-commenting" aria-hidden="true"></i> Employeees</a></li>
                                </ul>
                                <div id="myTabContent" class="tab-content custom-product-edit">
                                    <div class="product-tab-list tab-pane fade active in" id="description">
                                      @foreach($detailsData as $data)
                                       <div class="row">
                                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                          <div class="review-content-section">
                                              <div class="form-group">
                                                  <label for="company_name">Company Name:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-building" aria-hidden="true"></i></span>
                                                      <input type="text" id="company_name" value="{{$data->company_name}}" disabled class="form-control" placeholder="Company Name">
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="company_email">Company Email:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                                      <input type="text" id="company_email" class="form-control" placeholder="Company Email" value="{{$data->email}}" disabled>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="founding_date">Founding Date:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                                      <input type="text" id="founding_date" class="form-control" placeholder="Founding Date" value="{{$data->founding_date}}" disabled>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="website">Website Link:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-info" aria-hidden="true"></i></span>
                                                      <input type="text" id="website" class="form-control" placeholder="Website" value="{{$data->website}}" disabled>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="instagram_link">Instagram Link:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-instagram" aria-hidden="true"></i></span>
                                                      <input type="text" id="instagram_link" class="form-control" placeholder="Instagram Link" value="{{$data->instagram_link}}" disabled>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                          <div class="review-content-section">
                                              <div class="form-group">
                                                  <label for="mobile_number">Mobile Number:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                                                      <input type="text" id="mobile_number" class="form-control" placeholder="Mobile Number" value="{{$data->mobile_number}}" disabled>
                                                  </div>
                                              </div>
                                              
                                              <div class="form-group">
                                                  <label for="employee_count">Employee Count:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-users" aria-hidden="true"></i></span>
                                                      <input type="text" id="employee_count" class="form-control" placeholder="Employee Count" value="{{$data->employee_count}}" disabled>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="address">Address:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                                      <input type="text" id="address" class="form-control" placeholder="Address" value="{{$data->address}}" disabled>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="facebook_link">Facebook Link:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-facebook" aria-hidden="true"></i></span>
                                                      <input type="text" id="facebook_link" class="form-control" placeholder="Facebook Link" value="{{$data->facebook_link}}" disabled>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="twitter_link">Twitter Link:</label>
                                                  <div class="input-group mg-b-pro-edt">
                                                      <span class="input-group-addon"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                                                      <input type="text" id="twitter_link" class="form-control" placeholder="Twitter Link" value="{{$data->twitter_link}}" disabled>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>@endforeach
                                  </div>
                                </div>
                                    <div class="product-tab-list tab-pane fade" id="reviews">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="review-content-section">
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="pro-edt-img">
                                                                <img src="{{ Config::get('DocumentConstant.ORGANIZATION_VIEW') . $data->image }}" height="400px" width="400px" alt="">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                          <div class="row">
                                                            @foreach($detailsData as $data)
                                                              <div class="col-lg-12">
                                                                  <div class="product-edt-pix-wrap float-left">
                                                                      <div class="input-group">
                                                                          <span class="input-group-addon">Company Logo:- </span>
                                                                          <input type="text" class="form-control" placeholder="{{$data->company_name}}" value="{{$data->company_name}}" disabled>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                          </div>@endforeach
                                                      </div>

                                                    </div>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-tab-list tab-pane fade" id="INFORMATION">
                                            <div class="sparkline13-graph">
                                                <div class="datatable-dashv1-list custom-datatable-overright">
                                                    <div id="toolbar">
                                                       <select class="form-control" id="roleSelect">
                                                            <option disabled>All</option>
                                                        @foreach($dept as $data)
                                                                <option value="{{$data->id }}">
                                                                    <a href="{{ route('filter-employees', base64_encode($data->id)) }}">{{ $data->department_name }}</a>
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>  
                                                    <div class="table-responsive"> 
                                                        <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                                            data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                                            data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                                            data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                                            data-toolbar="#toolbar">
                                                            <thead>
                                                                <tr >
                                                                    <th data-field="">#</th>
                                                                    <th data-field="company_name" data-editable="false">Employee Name</th>
                                                                    <th data-field="email" data-editable="false">Email</th>
                                                                    <!-- <th data-field="password" data-editable="false">Password</th> -->
                                                                    <th data-field="mobile_number" data-editable="false">Mobile Number</th>
                                                                    <th data-field="address" data-editable="false">Address</th>
                                                                    <th data-field="image" data-editable="false">Image</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  id="fetchedDataContainer">
                                                                  @foreach($employees as $data)
                                                                  <tr>
                                                                      
                                                                      <td>{{ $loop->iteration }}</td>
                                                                    
                                                                      <td>{{ucwords($data->employee_name)}}</a></td>
                                                                      <td>{{$data->email}}</td>
                                                                      <td>{{ucwords($data->mobile_number)}}</td>
                                                                      <td>{{ucwords($data->address)}}</td>
                                                                      <td><img style="max-width:250px; max-height:150px;" src="{{ Config::get('DocumentConstant.EMPLOYEES_VIEW') . $data->emp_image }}" alt="{{ strip_tags($data['company_name']) }} Image" /></td>
                                                                      
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $('#roleSelect').change(function () {
            var selectedRoleId = $(this).val();

            $.ajax({
                url: "{{ url('filter-employees') }}/" + selectedRoleId,
                method: 'GET',
                data: { id: selectedRoleId },
                success: function (data) {
                    $("#fetchedDataContainer").empty();
                    try {
                        console.log("=======", data);
                        if (Array.isArray(data)) {
                            data.forEach(function (item, index) {
                                var imageUrl = "{{ Config::get('DocumentConstant.EMPLOYEES_VIEW') }}" + item.emp_image;
                                var newRow = '<tr>' +
                                    '<td>' + (index + 1) + '</td>' +
                                    '<td>' + ucwords(item.employee_name) + '</td>' +
                                    '<td>' + item.email + '</td>' +
                                    '<td>' + ucwords(item.mobile_number) + '</td>' +
                                    '<td>' + ucwords(item.address) + '</td>' +
                                    '<td><img style="max-width:250px; max-height:150px;" src="' + imageUrl + '" alt="' + item.company_name + ' Image" /></td>' +
                                    '</tr>';
                                $('#fetchedDataContainer').append(newRow);
                                });
                        } else {
                            console.error('Invalid data format. Expected an array.');
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                    }
                },
                error: function (error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    });

    function ucwords(str) {
        return str.replace(/\b\w/g, function (char) {
            return char.toUpperCase();
        });
    }

</script>

@endsection