@extends('admin.layouts.master')
@section('content')
    <div class="show-page-position">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-6 d-flex justify-content-start align-items-center">
                    <h3 class="page-title">
                        Vision Mission Detail
                    </h3>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 show-btn-position">
                    <a href="{{ route('list-vision-mission') }}" class="btn btn-sm btn-primary ml-3">Back</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center">
                <div class="sparkline12-list">

                    <div class="row">
                        <div class="col-12">
                            <div class="row ">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <label>Vision Description :</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <label>{{ strip_tags($showData->vision_description) }}</label>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <label>Mission Description :</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <label>{{ strip_tags($showData->mission_description) }}</label>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <label>Vision Image :</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <img src="{{ Config::get('DocumentConstant.VISION_MISSION_VIEW') }}{{ $showData->vision_image }}"
                                        style="width:150px; height:150px; background-color: aliceblue;"
                                        alt="Vision Image" />
                                </div>
                            </div><br>
                            <div class="row ">
                                <div class="col-lg-3 col-md-3 col-sm-3">
                                    <label>Mission Image :</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <img src="{{ Config::get('DocumentConstant.VISION_MISSION_VIEW') }}{{ $showData->mission_image }}"
                                        style="width:150px; height:150px; background-color: aliceblue;"
                                        alt="Mission Image" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- content-wrapper ends -->
    @endsection
