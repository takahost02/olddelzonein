<div class="col-12">

    <div class="card light-timeline p-3 shadow-sm">

        <div class="card-header p-0 ">

            <div class="row">

                <div class="col-12 col-sm-5 col-md-5 col-lg-5 col-xl-5">

                    <h6 class="mb-0 booking_id">@lang('frontend.Booking_ID') <span class="booking_id_span"># {{$data->id}}</span></h6>

                </div>

                <div class="col-12 col-sm-7 col-md-7 col-lg-7 col-xl-7 hover-eff my-auto text-start text-sm-end text-md-end text-lg-end text-xl-end">

                    <button class="btn btn-icon btn-3 breakdown_btn" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal102">

                        <span class="btn-inner--icon"><img src="{{ asset('assets/customer_dashboard/assets/img/svg/breakdown.svg') }}"></span>

                        <span class="btn-inner--text ">@lang('frontend.Breakdown')</span>

                    </button>

                    <a href="mailto:help@hyvikk.com" class="btn btn-icon btn-3 contact_btn" type="button">

                        <span class="btn-inner--icon"><img src="{{ asset('assets/customer_dashboard/assets/img/svg/contact.svg') }}"></span>

                        <span class="btn-inner--text">@lang('frontend.Support')</span>

                    </a>

                    <button class="btn btn-icon btn-3 driver_alert_btn" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal103">

                        <span class="btn-inner--icon"><img src="{{ asset('assets/customer_dashboard/assets/img/svg/driver_alert.svg') }}"></span>

                        <span class="btn-inner--text">@lang('frontend.Alert')</span>

                    </button>

                </div>

            </div>

        </div>

        <div class="card-body p-0 ">

          

            <p id="pickup-address" class="d-none">{{($data->pickup_addr??'-')}}</p>

            <p id="dropoff-address" class="d-none">{{($data->dest_addr??'-')}}</p>

            <p id="latitude" class="d-none">{{($data->latitude??'')}}</p>

            <p id="longitude" class="d-none">{{($data->longitude??'')}}</p>

            <p id="driver-id" class="d-none">{{($data->driver_id??'-')}}</p>

            <div class="map" style="width:100%;height:300px;border-radius: 10px;"></div>

        </div>

    </div>

</div>

<div class="col-12 mt-3">

    <div class="location_sec">

        <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">

            <div class="card py-3 px-4 shadow-sm">

                <div class="card-body p-0">

                    <div class="container px-0">

                        <div class="row">

                            <div class="col-8">

                                <div class="location">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/location.svg') }}">

                                    <div class="current_location">

                                        <span class="current_loc_title">@lang('frontend.Current_Location')</span>

                                        <span class="current_add current-location">-</span>

                                    </div>

                                </div>

                            </div>

                            <div class="col-4">

                                <div class="speed">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/speed.svg') }}">

                                    <div class="current_location">

                                        <span class="current_loc_title">@lang('frontend.Speed')</span>

                                        <span class="current_add mt-1 current_speed">-</span>

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

<div class="col-12 ">

    <div class="mt-3">

        <div class="card border_nav shadow-sm">

            <div class="card-header" style="border-bottom: 2px solid #BFCBF8;">

                <ul class="nav nav-tabs card-header-tabs justify-content-around">

                    <li class="nav-item">

                        <a href="#booking_info" class="nav-link active" data-bs-toggle="tab">@lang('frontend.Booking_Information')</a>

                    </li>

                    <li class="nav-item">

                        <a href="#vehicle" class="nav-link" data-bs-toggle="tab">@lang('frontend.Vehicle')</a>

                    </li>

                    <li class="nav-item">

                        <a href="#driver_info" class="nav-link" data-bs-toggle="tab">@lang('frontend.Driver_Information')</a>

                    </li>

                    <li class="nav-item">

                        <a href="#fare_details" class="nav-link" data-bs-toggle="tab">@lang('frontend.Fare_Details')</a>

                    </li>

                </ul>

            </div>

            <div class="card-body p-0">

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="booking_info">

                        <div class="booking_tab_content">

                            <div class="row">

                                <div class="col-12">

                                    <div class="booking_info_timeline timeline timeline-one-side mt-3">

                                        <div class="timeline-block ">

                                            <span class="timeline-step">

                                                <!-- <i class="ni ni-bell-55 text-success text-gradient"></i> -->

                                            </span>

                                            <div class="timeline-content">

                                                <!-- <h6 class="text-dark text-sm font-weight-bold mb-0">$2400, Design changes</h6> -->

                                                <div class="row">

                                                    <div class="col-3">

                                                        <p class="text-xs mt-1 mb-0">@lang('frontend.Pickup')</p>

                                                    </div>

                                                    <div class="col-9">

                                                        <p class="timeline_add">{{($data->pickup_addr??'-')}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="booking_info_timeline timeline timeline-one-side">

                                        <div class="timeline-block ">

                                            <span class="timeline-step">

                                                <!-- <i class="ni ni-bell-55 text-success text-gradient"></i> -->

                                            </span>

                                            <div class="timeline-content">

                                                <!-- <h6 class="text-dark text-sm font-weight-bold mb-0">$2400, Design changes</h6> -->

                                                <div class="row">

                                                    <div class="col-3">

                                                        <p class="text-xs mt-1 mb-0">@lang('frontend.Drop_off')</p>

                                                    </div>

                                                    <div class="col-9">

                                                        <p class="timeline_add ">{{($data->dest_addr??'-')}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="count_distance_time">

                                        <div class="container p-0 mx-0">

                                            <div class="row">

                                                <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">

                                                    <div class="count_distance">

                                                        <p class="count_distance-title">@lang('frontend.Distance')</p>

                                                        <p class="count_numbers">{{($data->getMeta('total_kms')??'-')}} Kms</p>

                                                    </div>

                                                </div>

                                                <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">

                                                    <div class="count_distance">

                                                        <p class="count_distance-title">@lang('frontend.Travel_Time')</p>



                                                        @if($data && $data->getMeta('total_time'))

                                                            @php

                                                                

                                                                $timeString = $data->getMeta('total_time');



                                                                list($hours, $minutes, $seconds) = explode(':', $timeString);



                                                                $totalMinutes = ($hours * 60) + $minutes + round($seconds / 60);



                                                            @endphp

                                                            <p class="count_numbers">{{$totalMinutes}} Mins</p>

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

                                                <span class="mb-0">{{ date("d M 'y h.m A", strtotime($data->created_at)) }}</span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="vehicle">

                        <div class="vehicle-tab"> 

                            <div class="row">

                                <div class="col-12 col-sm-6 col-md-6 col-xl-6 col-lg-6">

                                    <div class="vehicle-img-name">

                                        <div class="vehicle-img">



                                            @if(isset($vehicle->vehicle_image))

                                            <img src="{{ asset('uploads/'.$vehicle->vehicle_image) }}" style="

                                            height: 140px;

                                            width: 213px;

                                             ">

                                            @else

                                            <img src="{{ asset('assets/customer_dashboard/assets/img/svg/vehicle.svg') }}" >

                                            @endif



                                        </div>

                                        <div class="vehicle-name">

                                            <p class="vehicle-title">@lang('frontend.Vehicle_Number')</p>

                                            <p class="vehicle-number">{{($vehicle->license_plate??'-')}}</p>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-12 col-sm-6 col-md-6 col-xl-6 col-lg-6">

                                    <div class="vehicle-details">

                                        <div class="row">

                                            <div class="col-6">

                                                <div class="punch-title">

                                                    <p class="pt-1 mb-0">{{($vehicle->model_name??'-')}}</p>

                                                </div>

                                            </div>

                                            <div class="col-6">

                                                <div class=" d-flex justify-content-center " style="width: auto;height: 100%;align-items: center;">

                                                    <div class="punch-color"></div>

                                               

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row mt-3">

                                            <div class="col-6">

                                                <div class="vehicle-maker">

                                                    <p class="vehicle-info-title">@lang('frontend.Maker')</p>

                                                    <p class="vehicle-info-detail">{{($vehicle->make_name??'-')}}</p>

                                                </div>

                                            </div>

                                            <div class="col-6">

                                                <div class="vehicle-maker spaces">

                                                    <p class="vehicle-info-title">@lang('frontend.Vehicle_Type')</p>

                                                    <p class="vehicle-info-detail">{{($v_type->vehicletype??'-')}}</p>

                                                </div>

                                            </div>

                                            <div class="col-6 mt-3">

                                                <div class="vehicle-maker">

                                                    <p class="vehicle-info-title">@lang('frontend.Engine')</p>

                                                    <p class="vehicle-info-detail">{{($vehicle->engine_type??'-')}}</p>

                                                </div>

                                            </div>

                                            <div class="col-6 mt-3">

                                                <div class="vehicle-maker spaces">

                                                    <p class="vehicle-info-title">@lang('frontend.Mileage')</p>

                                                    <p class="vehicle-info-detail">{{isset($vehicle->mileage)?$vehicle->mileage.'km':'-'}}</p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="driver_info">

                        <div class="driver_info_tab">

                            <div class="row d-md-flex d-lg-flex d-xl-flex">

                                <div class="col-12 d-sm-flex d-md-flex d-lg-flex d-xl-flex justify-content-between">

                                    <div class="driver_info col-12 col-sm-6 col-md-6 col-xl-6 col-lg-6">

                                        <div class="driver_img">

                                            @if($driver && $driver->getMeta('driver_image'))

                                            <img src="{{ asset('uploads/'.$driver->getMeta('driver_image')) }}"  >

                                        @else

                                            <img src="{{ asset('uploads/no-user.jpg') }}">

                                        @endif

                                        

                                        </div>

                                        <div class="driver_name_info">

                                            

                                            <p class="driver_name">{{($driver->name??'-')}}</p>

                                            <p class="driver">@lang('frontend.Driver')</p>

                                        </div>

                                    </div>

                                    <div class="driver_call col-12 col-sm-6 col-md-6 col-xl-6 col-lg-6">

                                        <a href="tel:{{($driver->phone??'-')}}">

                                                <button class="btn btn-icon btn-3 call_btn" type="button" style="text-transform: unset;">

                                                    <span class="btn-inner--icon"><img src="{{ asset('assets/customer_dashboard/assets/img/svg/phone.svg') }}"></span>

                                                    <span class="btn-inner--text ps-2">@lang('frontend.Call')</span>

                                                </button>

                                         </a>

                                         <a href="https://wa.me/{{($driver->phone??'-')}}">

                                            <button class="btn btn-icon btn-3 whatsapp_btn" type="button" style="text-transform: unset;">

                                                <span class="btn-inner--icon"><img src="{{ asset('assets/customer_dashboard/assets/img/svg/icon _WhatsApp_.svg') }}"></span>

                                                <span class="btn-inner--text ps-2">@lang('frontend.Whatsapp')</span>

                                            </button>

                                        </a>

                                    </div>

                                </div>

                                <div class="col-12">

                                    <div class="driver_details mt-4">

                                        <div class="row">

                                            <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">

                                                <div class="employee">

                                                    <div class="employee_detail">

                                                        <p class="employee_title">@lang('frontend.Employee') #</p>

                                                        <p class="employee_id">{{($driver->id??'-')}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">

                                                <div class="employee">

                                                    <div class="employee_detail">

                                                        <p class="employee_title">Gender</p>

                                                        @if($driver && $driver->getMeta('gender') == "1")

                                                          <p class="employee_id">@lang('frontend.male')</p>

                                                        @elseif($driver && $driver->getMeta('gender') == "0")

                                                         <p class="employee_id">@lang('frontend.female')</p>

                                                         @else

                                                         <p class="employee_id">--</p>

                                                        @endif

                                                        

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">

                                                <div class="employee">

                                                    <div class="employee_detail">

                                                        <p class="employee_title">@lang('frontend.License') #</p>

                                                        <p class="employee_id">{{($driver->license_plate??'-')}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-6 col-sm-3 col-md-3 col-lg-3 col-xl-3">

                                                <div class="employee">

                                                    <div class="employee_detail">

                                                        <p class="employee_title">@lang('frontend.Ratings')</p>

                                                        <p class="employee_id">

                                                            

                                                            @php

                                                                $roundedRating = round($rating ?? 0); // Round the rating to the nearest integer

                                                            @endphp

                                                            @for ($i = 1; $i <= 5; $i++)

                                                            @if ($i <= ($roundedRating))

                                                                    <span class="fa fa-star checked"></span>

                                                                @else

                                                                    <span class="fa fa-star" style="color: rgba(181, 181, 181, 1)"></span>

                                                                @endif

                                                            @endfor

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

                    <div class="tab-pane fade" id="fare_details">

                        <div class="fare_details">

                            <div class="row">

                                <div class="col-12 col-sm-9 col-md-9 col-lg-9 col-xl-9">

                                    <div class="fare_detail_sec">

                                        <div class="row">

                                            <div class="col-4">

                                                <div class="fare_all_detail px-0 px-sm-3 px-md-3 px-lg-0 px-xl-3">

                                                    <div class="fare_detail_des">

                                                        <p class="fare_detail_title">@lang('frontend.Mileage')(Km)</p>

                                                        <p class="fare_detail_info">{{($vehicle->mileage??'-')}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-4">

                                                <div class="fare_all_detail">

                                                    <div class="fare_detail_des">

                                                        <p class="fare_detail_title">@lang('frontend.Amount')</p>

                                                        <p class="fare_detail_info">{{Hyvikk::get('currency')}} {{($data->total_fare??0)}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-4">

                                                <div class="fare_all_detail">

                                                    <div class="fare_detail_des">

                                                        <p class="fare_detail_title">@lang('frontend.Sub_Total')</p>

                                                        <p class="fare_detail_info">{{Hyvikk::get('currency')}} {{($data->total_fare??0)}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="row mt-4">

                                            <div class="col-4">

                                                <div class="fare_all_detail px-0 px-sm-3 px-md-3 px-lg-0 px-xl-3">

                                                    <div class="fare_detail_des">

                                                        <p class="fare_detail_title">@lang('frontend.Total_Tax') (%)</p>

                                                        <p class="fare_detail_info">{{Hyvikk::get('currency')}} {{($data->total_tax_charge_rs??0)}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-4">

                                                <div class="fare_all_detail">

                                                    <div class="fare_detail_des">

                                                        <p class="fare_detail_title">@lang('frontend.Fuel_Charges')</p>

                                                        <p class="fare_detail_info">{{Hyvikk::get('currency')}} {{($data->fuel_charges??0)}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-4">

                                                <div class="fare_all_detail">

                                                    <div class="fare_detail_des">

                                                        <p class="fare_detail_title">@lang('frontend.Total_Amount')</p>

                                                        <p class="fare_detail_info">{{Hyvikk::get('currency')}} {{($data->tax_total??0)}}</p>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="col-3  d-none d-sm-flex d-md-flex d-lg-flex d-xl-flex justify-content-center">

                                    <div class="fare_detail_img">

                                        <img src="{{ asset('assets/customer_dashboard/assets/img/svg/fare_detail.svg') }}">

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









<div class="modal fade" id="exampleModal102" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

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





<div class="modal fade" id="exampleModal103" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

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