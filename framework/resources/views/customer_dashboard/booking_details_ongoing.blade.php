@extends('customer_dashboard.layouts.app')



@section('title')



    <title>@lang('frontend.Booking_Details_Ongoing') | {{ Hyvikk::get('app_name') }}</title>



@endsection



@section('css')

<link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/booking_ongoing.css') }}">

@endsection







@section('contents')



@section('breadcrumb')



    <li class="breadcrumb-item text-sm active "><a href="{{ url('/booking') }}" aria-current="page">@lang('frontend.Booking')</a></li>



    <li class="breadcrumb-item text-sm text-dark active"><a href="{{ url('/booking_details') }}" aria-current="page">



        @lang('frontend.Booking_Details')</a></li>



@endsection







<div class="custom-alert-msg" style="color:white;"></div>



<div class="row">



    <div class="col-12 ">



        <div class="row">



            <div class="col-6">



                <div class="booking_detail_id">



                    <p class="booking_id">#{{ $data->id ?? '-' }}</p>



                </div>



            </div>



            <div class="col-6">



                <div class="booking_status">



                    <p class="status_title">@lang('frontend.Status')  </p>











                    @if ($data->ride_status == 'Ongoing')



                        <div class="status_detail1">



                            <p>In-Transit</p>



                        </div>



                    @elseif($data->ride_status == 'Completed')



                        <div class="status_detail2">



                            <p>{{ $data->ride_status ?? '-' }}</p>



                        </div>



                    @elseif($data->ride_status == 'Cancelled')



                        <div class="status_detail3">



                            <p>{{ $data->ride_status ?? '-' }}</p>



                        </div>



                    @else



                        <div class="status_detail">



                            <p>Pending</p>



                        </div>



                    @endif







                </div>



            </div>



            <div class="col-12 mt-2">



                <div class="booking_process">



                    <div class="progress">



                        <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"



                            aria-valuemax="100"



                            style="



                  @if ($data->ride_status == 'Ongoing') width: 70%;



                  @elseif($data->ride_status == 'Completed')



                      width: 100%;



                  @elseif($data->ride_status == 'Cancelled')



                      width: 20%; 



                  @else



                     width: 40%; @endif



                  ">



                        </div>



                    </div>



                </div>



            </div>



        </div>



    </div>



</div>



<div class="row">



    <div class="col-12 mt-4">



        <div class="card booking_detail_map shadow-sm">



            <div class="card-body" style="position: relative;">







                <div class="map1" style="width:100%;height:478px;border-radius: 10px;"></div>



                <div class="ongoing map-function">



                    <div class="map_detail">



                        <div class="row">



                            <div class="col-sm-12 col-md-5 col-lg-5 col-xl-5">



                                <div class="map d-flex " style="align-items:center">



                                    <div class="distance_map_img">



                                        <img src="{{ asset('assets/customer_dashboard/assets/img/svg/map_location.svg') }}">



                                    </div>



                                    <div class="distance_map_content">



                                        <p class="distance_map_title">@lang('frontend.Current_Location') </p>



                                        <p class="distance_map_count current-location">-</p>



                                    </div>



                                </div>



                             



                            </div>



                            <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3 mt-3 mt-sm-3 mt-md-0 mt-lg-0 mt-xl-0">



                                <div class="map d-flex" style="align-items:center">



                                    <div class="distance_map_img">



                                        <img src="{{ asset('assets/customer_dashboard/assets/img/svg/map_speed.svg') }}">



                                    </div>



                                    <div class="distance_map_content">



                                        <p class="distance_map_title">@lang('frontend.Speed')</p>



                                        <p class="distance_map_count curr-speed">-</p>



                                    </div>



                                </div>



                              



                            </div>



                            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4 mt-3 mt-sm-3 mt-md-0 mt-lg-0 mt-xl-0">



                                <div class="map d-flex" style="align-items:center">



                                    <div class="distance_map_img">



                                        <img src="{{ asset('assets/customer_dashboard/assets/img/svg/map_distance.svg') }}">



                                    </div>



                                    <div class="distance_map_content">



                                        <p class="distance_map_title">@lang('frontend.Distance')</p>



                                        <p class="distance_map_count">



                                            {{ $data->getMeta('total_kms') ? $data->getMeta('total_kms') . ' Kms' : 'NA' }}



                                        </p>



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



<div class="row">



    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mt-3">



        <div class="card booking_Detail_card  shadow-sm">



            <div class="card-header">



                <p class="booking_details_title">@lang('frontend.Booking_Information')</p>



            </div>



            <div class="card-body  px-3 py-2">



                <div class="booking_tab_content">



                    <div class="row">



                        <div class="col-12">



                            <div class="booking_info_timeline timeline timeline-one-side mt-3">



                                <div class="timeline-block ">



                                    <span class="timeline-step">





                                    </span>



                                    <div class="timeline-content">





                                        <div class="row">



                                            <div class="col-3">



                                                <p class="text-xs mt-1 mb-0">@lang('frontend.Pickup')</p>



                                            </div>



                                            <div class="col-9">



                                                <p class="timeline_add">{{ $data->pickup_addr ?? '-' }}</p>



                                            </div>



                                        </div>



                                    </div>



                                </div>



                            </div>



                            <div class="booking_info_timeline timeline timeline-one-side">



                                <div class="timeline-block">



                                    <span class="timeline-step">





                                    </span>



                                    <div class="timeline-content">





                                        <div class="row">



                                            <div class="col-3">



                                                <p class="text-xs mt-1 mb-0">@lang('frontend.Drop_off')</p>



                                            </div>



                                            <div class="col-9">



                                                <p class="timeline_add ">{{ $data->dest_addr ?? '-' }}</p>



                                            </div>



                                        </div>



                                    </div>



                                </div>



                            </div>



                        </div>



                        <div class="col-12">



                            <div class="count_distance_time">



                                <div class="container m-0 p-0">



                                    <div class="row">



                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">



                                            <div class="count_distance">



                                                <p class="count_distance-title">@lang('frontend.Distance')</p>



                                                <p class="count_numbers">



                                                    {{ $data->getMeta('total_kms') ? $data->getMeta('total_kms') . ' Kms' : 'NA' }}



                                                </p>



                                            </div>



                                        </div>



                                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">



                                            <div class="count_distance">



                                                <p class="count_distance-title">@lang('frontend.Travel_Time')</p>



                                                @if ($data->getMeta('total_time'))



                                                    @php







                                                        $timeString = $data->getMeta('total_time');







                                                        [$hours, $minutes, $seconds] = explode(':', $timeString);







                                                        $totalMinutes = $hours * 60 + $minutes + round($seconds / 60);







                                                    @endphp



                                                    <p class="count_numbers">{{ $totalMinutes }} Mins</p>



                                                @else



                                                    <p class="count_numbers">---</p>



                                                @endif



                                            </div>



                                        </div>



                                    </div>



                                </div>



                            </div>



                        </div>



                        <div class="col-12 pt-3">



                            <div class="row">



                                <div class="col-6">



                                    <div class="booked-on">



                                        <span class="mb-0">@lang('frontend.Booked_On')</span>



                                    </div>



                                </div>



                                <div class="col-6">



                                    <div class="booked-on-date">



                                        <span



                                            class="mb-0">{{ date("d M 'y h.i A", strtotime($data->created_at)) }}</span>



                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



                </div>







            </div>



        </div>



    </div>



    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mt-3">



        <div class="card booking_Detail_card  shadow-sm">



            <div class="card-header">



                <p class="booking_details_title">@lang('frontend.Fare_Details')</p>



            </div>



            <div class="card-body  px-3 py-0">



                <div class="booking_tab_content">



                    <div class="row">



                        <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3  fare">



                            <div class="row d-flex flex-column">



                                <div class="col-12 ">



                                    <div class="booking_fare_details">



                                        <div class="booking_fare_title">



                                            <p class="fare_title">@lang('frontend.Mileage')(Km)</p>



                                            <p class="fare_detail">



                                                {{ isset($vehicle->mileage) ? $vehicle->mileage . 'km' : '-' }}</p>



                                        </div>



                                    </div>



                                </div>



                                <div class="col-12">



                                    <div class="booking_fare_details">



                                        <div class="booking_fare_title">



                                            <p class="fare_title">@lang('frontend.Total_Tax') (%)</p>



                                            <p class="fare_detail">{{ Hyvikk::get('currency') }}



                                                {{ $data->total_tax_charge_rs ?? 0 }}</p>



                                        </div>



                                    </div>



                                </div>



                            </div>







                        </div>



                        <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3  fare">



                            <div class="row d-flex flex-column">



                                <div class="col-12 ">



                                    <div class="booking_fare_details">



                                        <div class="booking_fare_title">



                                            <p class="fare_title">@lang('frontend.Amount')</p>



                                            <p class="fare_detail">{{ Hyvikk::get('currency') }}



                                                {{ $data->total_fare ?? 0 }}</p>



                                        </div>



                                    </div>



                                </div>



                                <div class="col-12">



                                    <div class="booking_fare_details">



                                        <div class="booking_fare_title">



                                            <p class="fare_title">@lang('frontend.Fuel_Charges')</p>



                                            <p class="fare_detail">{{ Hyvikk::get('currency') }}



                                                {{ $data->fuel_charges ?? 0 }}</p>



                                        </div>



                                    </div>



                                </div>



                            </div>







                        </div>



                        <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3 fare res-fare" style="">



                            <div class="container px-0">



                                <div class="row d-flex flex-column">



                                    <div class="col-12 ">



                                        <div class="booking_fare_details ps-0 ps-sm-3 ps-md-3 ps-lg-3 ps-xl-3 ">



                                            <div class="booking_fare_title">



                                                <p class="fare_title">@lang('frontend.Sub_Total')</p>



                                                <p class="fare_detail">{{ Hyvikk::get('currency') }}



                                                    {{ $data->total_fare ?? 0 }}</p>



                                            </div>



                                        </div>



                                    </div>



                                    <div class="col-12">



                                        <div class="booking_fare_details ps-0 ps-sm-3 ps-md-3 ps-lg-3 ps-xl-3 ">



                                            <div class="booking_fare_title">



                                                <p class="fare_title">@lang('frontend.Total_Amount')</p>



                                                <p class="fare_detail">{{ Hyvikk::get('currency') }}



                                                    {{ $data->tax_total ?? 0 }}</p>



                                            </div>



                                        </div>



                                    </div>



                                </div>



                            </div>



                        </div>



                        <div class="col-3 fare d-none d-sm-none d-md-flex d-lg-flex d-xl-flex  ">



                            <div class="booking_fare_img">



                                <img src="{{ asset('assets/customer_dashboard/assets/img/svg/fare_booking.svg') }}">



                            </div>



                        </div>



                    </div>



                </div>



            </div>



        </div>



    </div>



    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 driver_res mt-3">



        <div class="card booking_Detail_card  shadow-sm">



            <div class="card-header">



                <p class="booking_details_title">@lang('frontend.Assigned_Vehicle')</p>



            </div>



            <div class="card-body  px-3 pt-0 pb-3">



                <div class="booking_tab_content">



                    <div class="row">



                        <div class="col-12 col-sm-5 col-md-5 col-lg-5 col-xl-5  assign_vehicle">



                            <div class="row d-flex flex-column">



                                <div class="col-12 ">



                                    <div class="booking_assign_vehicle">



                                        <div class="assign_vehicle_img">



                                            @if (isset($vehicle->vehicle_image))



                                                <img src="{{ asset('uploads/' . $vehicle->vehicle_image) }}"



                                                    style="



                                width: 158px;



                             height: 86px;



                            border-radius: 5px;



                             ">



                                            @else



                                                <img src="{{ asset('assets/customer_dashboard/assets/img/svg/assign_vehicle.svg') }}"



                                                    style="border-radius: 5px;">



                                            @endif







                                        </div>



                                    </div>



                                </div>



                                <div class="col-12">



                                    <div class="booking_assign_vehicle">



                                        <div class="booking_assign">



                                            <p class="assign_vehicel_title">@lang('frontend.Vehicle_Number')</p>



                                            <p class="assign_vehicel_no">{{ $vehicle->license_plate ?? '-' }}</p>



                                        </div>



                                    </div>



                                </div>



                            </div>



                        </div>



                        <div class="col-12 col-sm-7 col-md-7 col-lg-7 col-xl-7  ps-sm-4 ps-md-4 ps-lg-4 ps-xl-4">



                            <div class="row d-flex"



                                style="padding-bottom: 11px;



                        padding-top: 50px;



                    ">



                                <div class="col-6">



                                    <div style="display:flex;">



                                        <div class="assign_punch-title">



                                            <p class="assign_punch">{{ $vehicle->model_name ?? '-' }}</p>



                                        </div>



                                    </div>



                                </div>



                                <div class="col-6">



                                    <div class=" justify-content-end justify-content-sm-start justify-content-md-start justify-content-lg-start justify-content-xl-start"



                                        style="display:flex;">



                                        <div class="assign_punch-img">



                                            <p



                                                style="height: 22px;



                            width: 67px;



                            background: {{ $vehicle->color_name ?? 'grey' }}">



                                            </p>







                                          



                                        </div>



                                    </div>



                                </div>



                            </div>



                            <div class="row">



                                <div class="col-4">



                                    <div class="ongoing-detail booking_vehicle_marker">

            

                                      <div class="vehicle-maker">

            

                                        <p class="vehicle-info-title">@lang('frontend.Maker')</p>

            

                                        <p class="vehicle-info-detail">{{($vehicle->make_name??'-')}}</p>

            

                                      </div>

            

                                    </div>

            

                                  </div>

            

                                  <div class="col-4">

            

                                    <div class="ongoing-detail booking_vehicle_marker  justify-content-end justify-content-sm-start justify-content-md-start justify-content-lg-start justify-content-xl-start">

            

                                      <div class="vehicle-maker">

            

                                        <p class="vehicle-info-title">@lang('frontend.Vehicle_Type')</p>

            

                                        <p class="vehicle-info-detail">{{($v_type->vehicletype??'-')}}</p>

            

                                      </div>

            

                                    </div>

            

                                  </div>

            

                                  <div class="col-4">

            

                                    <div class="ongoing-detail">

            

                                      <div class="vehicle-maker">

            

                                        <p class="vehicle-info-title">@lang('frontend.Luggage_Capt')</p>

            

                                        <p class="vehicle-info-detail text-center">No.{{($vehicle->luggage??'-')}}</p>

            

                                      </div>

            

                                    </div>

            

                                  </div>

            

            

                                  <div class="col-4 mt-3">

            

                                    <div class="ongoing-detail booking_vehicle_marker">

            

                                      <div class="vehicle-maker">

            

                                        <p class="vehicle-info-title">@lang('frontend.Engine')</p>

            

                                        <p class="vehicle-info-detail">{{($vehicle->engine_type??'-')}}</p>

            

                                      </div>

            

                                    </div>

            

                                  </div>

            

                                  <div class="col-4 mt-3">

            

                                    <div class="ongoing-detail booking_vehicle_marker  justify-content-end justify-content-sm-start justify-content-md-start justify-content-lg-start justify-content-xl-start">

            

                                      <div class="vehicle-maker">

            

                                        <p class="vehicle-info-title">@lang('frontend.Mileage')</p>

            

                                        <p class="vehicle-info-detail">{{isset($vehicle->mileage)?$vehicle->mileage.'km':'-'}}</p>

            

                                      </div>

            

                                    </div>

            

                                  </div>

            

                                  <div class="col-4 mt-3">

            

                                    <div class="ongoing-detail">

            

                                      <div class="vehicle-maker">

            

                    

                                        <p class="vehicle-info-title">@lang('frontend.Passanger_Capt')</p>

            

                                        <p class="vehicle-info-detail text-center">No.{{($v_type->seats??'-')}}</p>

            

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



    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 driver_res mt-3">



        <div class="card booking_Detail_card  shadow-sm">



            <div class="card-header">



                <p class="booking_details_title">@lang('frontend.Driver_Information')</p>



            </div>



            <div class="card-body  px-3 py-0">



                <div class="booking_tab_content">



                    <div class="row">



                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">



                            <div class="booking_driver_info">



                                <div class="booking_driver">



                                    <div class="booking_driver_img">



                                        @if ($driver && $driver->getMeta('driver_image'))



                                            <img src="{{ asset('uploads/' . $driver->getMeta('driver_image')) }}">



                                        @else



                                            <img src="{{ asset('uploads/no-user.jpg') }}">



                                        @endif



                                    </div>



                                    <div class="rating">



                                        <div class="driver-rating">



                                            @php



                                                $roundedRating = round($rating ?? 0);



                                            @endphp







                                            @for ($i = 1; $i <= 5; $i++)



                                                @if ($i <= $roundedRating)



                                                    <span class="fa fa-star checked"></span>



                                                @else



                                                    <span class="fa fa-star"



                                                        style="color: rgba(181, 181, 181, 1)"></span>



                                                @endif



                                            @endfor







                                        </div>



                                        <div class="rating_no">



                                            <p>{{ $r ?? '-' }}</p>



                                        </div>



                                    </div>



                                </div>



                            </div>



                        </div>



                        <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">



                            <div class="driver_profile">



                                <div class="row">



                                    <div class="col-12" style="padding-left:1px;">



                                        <div class="driver_profile_name">



                                            <p class="driver_name">{{ $driver->name ?? '-' }}</p>



                                            <p class="driver_title">@lang('frontend.Driver')</p>



                                        </div>



                                    </div>



                                    <div class="col-12 mt-3">



                                        <div class="row">



                                            <div class="col-3 px-0">



                                                <div class="driver-res">



                                                    <div class="driver_detail">



                                                        <p class="d_details">@lang('frontend.gender')</p>











                                                        @if ($driver && $driver->getMeta('gender') == '1')



                                                            <p class="d_title" style="color:rgba(33, 82, 255, 1)">@lang('frontend.male')



                                                            </p>



                                                        @elseif($driver && $driver->getMeta('gender') == '0')



                                                            <p class="d_title" style="color:rgba(33, 82, 255, 1)">



                                                                @lang('frontend.female')</p>



                                                        @else



                                                            <p class="d_title">--</p>



                                                        @endif







                                                    </div>



                                                </div>



                                            </div>



                                            <div class="col-4 px-0">



                                                <div class="driver-res">



                                                    <div class="driver_detail">



                                                        <p class="d_details">@lang('frontend.License') #</p>



                                                        <p class="d_title">{{ $driver->license_number ?? '-' }}</p>



                                                    </div>



                                                </div>



                                            </div>



                                            <div class="col-5 px-0">



                                                <div class="driver-res">



                                                    <div class="driver_detail">



                                                        <p class="d_details">@lang('frontend.Employee') #</p>



                                                        <p class="d_title">{{ $driver->id ?? '-' }}</p>



                                                    </div>



                                                </div>



                                            </div>



                                        </div>



                                    </div>



                                    <div class="col-12 mt-4 mb-4 mb-sm-0 mb-md-0 mb-lg-0 mb-xl-0"



                                        style="padding-left:1px;">



                                        <div class="driver_contact_info">







                                            <a href="tel:{{ $driver->phone ?? '-' }}">



                                                <button class="btn btn-icon btn-3 call_btn me-4" type="button"



                                                    style="text-transform: unset;">



                                                    <span class="btn-inner--icon"><img



                                                            src="{{ asset('assets/customer_dashboard/assets/img/svg/phone.svg') }}"></span>



                                                    <span class="btn-inner--text ps-2">@lang('frontend.Call')</span>



                                                </button>



                                            </a>



                                            <a href="https://wa.me/{{ $driver->phone ?? '-' }}">



                                                <button class="btn btn-icon btn-3 whatsapp_btn" type="button"



                                                    style="text-transform: unset;">



                                                    <span class="btn-inner--icon"><img



                                                            src="{{ asset('assets\customer_dashboard\assets\img\svg\icon _WhatsApp_.svg') }}"></span>



                                                    <span class="btn-inner--text ps-2">@lang('frontend.Whatsapp')</span>



                                                </button>



                                            </a>



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



<div class="container-fluid">



    <div class="row">



        <div class="col-12 mt-5 mb-4">



            <div class="booking_btn_group ongoing">



                <div class="container-fluid">



                    <div class="row">



                        <div



                            class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 d-flex justify-content-center justify-content-sm-center justify-content-md-end justify-content-lg-end justify-content-xl-end">



                            <a href="mailto:help@hyvikk.com"



                                class="btn btn-icon btn-3 contact_support_btn d-flex align-items-center"



                                type="button">



                                <span class="btn-inner--text">@lang('frontend.Contact_Support')</span>



                                <span class="btn-inner--icon"><img



                                        src="{{ asset('assets/customer_dashboard/assets/img/svg/ongoing_contact.svg') }}"></span>



                            </a>



                        </div>







                        @if ($data && $data->getMeta('ride_status') == 'Ongoing')



                            <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 d-flex justify-content-center">







                                <div class="btn-group">



                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal100"



                                        class="btn btn-danger btn btn-danger dropdown-togglebtn btn-icon btn-3  breakdown_btn d-flex align-items-center mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0"



                                        >



                                        <span class="btn-inner--text">@lang('frontend.Veh_Breakdown')</span>



                                        <span class="btn-inner--icon"><img



                                                src="{{ asset('assets/customer_dashboard/assets/img/svg/ongoing_breakdown.svg') }}"></span>



                                    </button>







                                </div>







                            </div>



                            <div



                                class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 d-flex justify-content-center justify-content-sm-center justify-content-md-start justify-content-lg-start justify-content-xl-start">











                                <div class="btn-group">



                                    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal101"



                                        class="btn btn-danger btn btn-danger btn btn-icon btn-3 driver_alert_btn d-flex align-items-center mt-3 mt-sm-3 mt-md-0 mt-lg-0 mt-xl-0"



                                        style="border-radius: 5px;">



                                        <span class="btn-inner--text">@lang('frontend.Driver_Alert')</span>



                                        <span class="btn-inner--icon"><img



                                                src="{{ asset('assets/customer_dashboard/assets/img/svg/ongoing_driver_alert.svg') }}"></span>



                                    </button>











                                    <ul class="dropdown-menu overflow-auto"



                                        style="max-height:500%;overflow-x: hidden !important;">







                                        @if (isset($driver_alert) && count($driver_alert) > 0)



                                            @foreach ($driver_alert as $da)



                                                <li><a class="dropdown-item" href="#"



                                                        data-action="action-alert" data-message="{{ $da->name }}"



                                                        data-bookingid="{{ $data->id }}"



                                                        data-rcode="200">{{ $da->name }}</a></li>



                                            @endforeach



                                        @endif



                                    </ul>



                                </div>































                            </div>







                        @endif



                    </div>



                </div>







            </div>



        </div>











    </div>



</div>



















<div class="modal fade" id="exampleModal100" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">



    <div class="modal-dialog">



        <div class="modal-content">



            <div class="modal-header">



                <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('frontend.Vehicle_Breakdown')</h1>



                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>



            </div>



            <div class="modal-body">



                <select class="form-control sele-vehicle-breakdown">



                    @if (isset($ve_breakdown) && count($ve_breakdown) > 0)



                        <option selected disabled>Select Vehicle Breakdown</option>



                        @foreach ($ve_breakdown as $vb)



                            <option data-message="{{ $vb->name }}" data-bookingid="{{ $data->id }}">



                                {{ $vb->name }}</option>



                        @endforeach



                    @endif



                </select>



                <span class="error-vehicle-breakdown"></span>



            </div>



            <div class="modal-footer">



                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"



                    style="background: grey;">@lang('frontend.Close')</button>



                <button type="button" class="btn btn-primary save-data" data-rcode="100"



                    style="background: rgba(52, 71, 103, 1);">@lang('frontend.Save')</button>



            </div>



        </div>



    </div>



</div>











<div class="modal fade" id="exampleModal101" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">



  <div class="modal-dialog">



      <div class="modal-content">



          <div class="modal-header">



              <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('frontend.Driver_Alert')</h1>



              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>



          </div>



          <div class="modal-body">



              <select class="form-control sele-driver-alert">



                @if (isset($driver_alert) && count($driver_alert) > 0)



                                            



                      <option selected disabled>Select Vehicle Breakdown</option>



                      @foreach ($driver_alert as $da)



                          <option data-message="{{ $da->name }}" data-bookingid="{{ $data->id }}">



                              {{ $da->name }}</option>



                      @endforeach



                  @endif



              </select>



              <span class="error-driver-alert"></span>



          </div>



          <div class="modal-footer">



              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"



                  style="background: grey;">@lang('frontend.Close')</button>



              <button type="button" class="btn btn-primary save-data" 



                  style="background: rgba(52, 71, 103, 1);" data-rcode="200">@lang('frontend.Save')</button>



          </div>



      </div>



  </div>



</div>



















@endsection







@section('script')



<script



    src="https://maps.googleapis.com/maps/api/js?key={{ Hyvikk::api('api_key') }}&libraries=places&callback=initMap"



    async defer></script>



<script>



var map;

var marker; 





initMap();



function initMap() {

    $(document).ready(function() {

        var mapDivs = document.querySelectorAll('.map1');



        

        var startIcon = "{{ asset('assets/images/pickup.png') }}";

        var endIcon = "{{ asset('assets/images/dropup.png') }}"; 

        var currentIcon = "{{ asset('assets/images/current.png') }}"; 



     

        mapDivs.forEach(function(mapDiv) {

        

            map = new google.maps.Map(mapDiv, {

                zoom: 10,

                center: { lat: 0, lng: 0 }

            });



            

            var startAddress = "{{ $data->pickup_addr }}";

            var endAddress = "{{ $data->dest_addr }}";



         

            var geocoder = new google.maps.Geocoder();



          

            geocodeAddress(geocoder, map, startAddress, endAddress, startIcon, endIcon);



            

            marker = null;



            current_location();

           

            setInterval(current_location, 30000);

        });

    });

}





function current_location() {

    $.ajax({

        url: "{{url('current-location')}}",

        type: "GET",

        data: { "driver_id": "{{ $data->driver_id }}" },

        success: function(data) {

            if (data.latitude && data.longitude) {

                var driverLatLng = new google.maps.LatLng(data.latitude, data.longitude);

                if (marker) {

                    marker.setPosition(driverLatLng);

                } else {

                    marker = new google.maps.Marker({

                        map: map,

                        position: driverLatLng,

                        title: "Driver's Current Location",

                        icon: "{{ asset('assets/images/current.png') }}" 

                    });

                }

                map.setCenter(driverLatLng);



                 $(".current-location").text(data.current_location || '-');

                $(".curr-speed").text(data.speed || '-');

            } else {

                console.error('Driver location not available');

                $(".current-location").text('-');

                $(".curr-speed").text('-');

            }

        },

        error: function() {

            console.error('Failed to retrieve driver location');

            $(".current-location").text('-');

            $(".curr-speed").text('-');

        }

    });

}



function geocodeAddress(geocoder, map, startAddress, endAddress, startIcon, endIcon) {

    geocoder.geocode({ 'address': startAddress }, function(resultsStart, status) {

        if (status === 'OK') {

            var startLocation = resultsStart[0].geometry.location;

            geocoder.geocode({ 'address': endAddress }, function(resultsEnd, status) {

                if (status === 'OK') {

                    var endLocation = resultsEnd[0].geometry.location;



                    

                    new google.maps.Marker({

                        position: startLocation,

                        map: map,

                        title: 'Start',

                        icon: startIcon 

                    });



                

                    new google.maps.Marker({

                        position: endLocation,

                        map: map,

                        title: 'End',

                        icon: endIcon 

                    });



                    

                    displayRoute(map, startLocation, endLocation);

                } else {

                    console.error('Geocode was not successful for end address: ' + status);

                }

            });

        } else {

            console.error('Geocode was not successful for start address: ' + status);

        }

    });

}





function displayRoute(map, startLocation, endLocation) {

    var directionsService = new google.maps.DirectionsService();

    var directionsRenderer = new google.maps.DirectionsRenderer({

        map: map,

        suppressMarkers: true 

    });

    var request = {

        origin: startLocation,

        destination: endLocation,

        travelMode: 'DRIVING'

    };



    directionsService.route(request, function(response, status) {

        if (status === 'OK') {

            directionsRenderer.setDirections(response);

        } else {

            console.error('Directions request failed due to ' + status);

        }

    });

}





</script>



@endsection



