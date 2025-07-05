

@if(isset($data) && count($data) > 0)



@foreach($data as $d)

<div class="col-lg-6 mt-3 mt-sm-3 mt-md-3 mt-lg-0 mt-xl-0 p-2" data-action="classfound1" data-id="{{ $d->id }}">

    <div class="card booking-light-timeline shadow-sm">

        <div class="card-header pb-0 p-3">

            <div class="row">

                <div class="col-6 d-flex align-items-center gap-2">

                  

                    <h6 class="mb-0 booking_id"> @lang('frontend.Booking')  : <span>#{{($d->id??'-')}}</span></h6>

                </div>

                <div class="col-6 my-auto d-flex justify-content-end" style="align-items: center;">

                    @if($d && $d->booking_type=="return_way")
                    <img src="{{url('/assets/customer_dashboard/assets/img/return_way.svg')}}" width="30" height="30" class="return-way">
                @else
                <img src="{{url('/assets/customer_dashboard/assets/img/one_way.svg')}}" width="30" height="30" class="one-way">
                @endif

                
                    @if($d->ride_status == "Ongoing")

                    <div class="status_detail1 custom-msg"><p>In-Transit</p></div>

                    @elseif($d->ride_status == "Completed")

                    <div class="status_detail2 custom-msg"><p>{{($d->ride_status??'-')}}</p></div>

                    @elseif($d->ride_status == "Cancelled")

                    <div class="status_detail3 custom-msg"><p>{{($d->ride_status??'-')}}</p></div>

                    @else

                    <div class="status_detail4 custom-msg"><p>Pending</p></div>

                    @endif





                    

                    <div class="dot-dropdown">


                        
                        <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">

                            <img src="{{ asset('assets/customer_dashboard/assets/img/svg/dropdown_dots.svg') }}" class="drop-img">

                        </a>

                        <ul class="dropdown-menu">

                          

                              <li><a class="dropdown-item" href="{{ route('dashboard.booking_details', ['id' => $d->id]) }}">@lang('frontend.View_Details')</a></li>



                              @if($d->ride_status == "Ongoing")

                             <li><a class="dropdown-item" href="{{ route('dashboard.booking_details_ongoing', ['id' => $d->id]) }}">@lang('frontend.Track_Ride')</a></li>

                             @endif

                        </ul>

                    </div>



                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="booking_address p-3">

                <div class="progress">



                    @if($d->ride_status == "Ongoing")

                    <div class="progress-bar progressbar_ongoing" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" ></div>

                    @elseif($d->ride_status == "Completed")

                     <div class="progress-bar progressbar_complete" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" ></div>

                    @elseif($d->ride_status == "Cancelled")

                    <div class="progress-bar progressbar_cancel" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>

                    @else

                    <div class="progress-bar progressbar_pending" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" ></div>

                    @endif

                </div>

                <div class="row ">

                    <div class="col-12">

                        <div class="booking-white-timeline timeline timeline-one-side">

                            <div class="timeline-block">

                                <span class="timeline-step">

                                   

                                </span>

                                <div class="timeline-content mt-3">

                                    

                                    <div class="row">

                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                            
                                          
                                            <p class=" mt-1 mb-0">@if(isset($d->pickup)){{ date("d M 'y h.i A", strtotime($d->pickup)) }} @else --- @endif</span></p>

                                        </div>

                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8" style="padding-left: 25px;">

                                            <p class="timeline_add">{{($d->pickup_addr??'-')}}</p>

                                            <p class="booking_pickup">@lang('frontend.Pickup_Address')</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="booking-white-timeline timeline timeline-one-side mt-2">

                            <div class="timeline-block ">

                                <span class="timeline-step">

                                  

                                </span>

                                <div class="timeline-content">

                                 

                                    <div class="row">

                                        <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">

                                            <p class=" mt-1 mb-0">@if(isset($d->dropoff)){{ date("d M 'y h.i A", strtotime($d->dropoff)) }} @else --- @endif</span></p>

                                        </div>

                                        <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xl-8" style="padding-left: 25px;">

                                            <p class="timeline_add">{{($d->dest_addr??'-')}}</p>

                                            <p class="booking_pickup">@lang('frontend.Drop_off_Address')</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="booking_add_driver">

                <div class="driver_detail_address">

                    <div class="row">

                        <div class="col-6">

                           



                            <div class="driver_add_name_img">

                                <div class="driver_add_img">

                                   

                                @if(isset($d->driver_id))

                                    @php 

                                        $driver = \App\Model\User::find($d->driver_id);

                                    @endphp

                                    @if($driver && $driver->getMeta('driver_image'))

                                        <img src="{{ asset('uploads/'.$driver->getMeta('driver_image')) }}">

                                    @else

                                        <img src="{{ asset('uploads/no-user.jpg') }}">

                                    @endif

                                @else

                                    <img src="{{ asset('uploads/no-user.jpg') }}">

                                @endif

                                



                                </div>

                                <div class="driver_add_name ms-0 ms-sm-4 ms-md-4 ms-lg-4 ms-xl-4">





                                    @if(isset($d->driver_id))

                                    @php 

                                        $driver = \App\Model\User::find($d->driver_id);

                                    @endphp

                                    @if($driver && $driver->name)

                                    <p class="add_driver_name">{{$driver->name}}</p>

                                    @else

                                    <p class="add_driver_name">---</p>

                                    @endif

                                    @else

                                    <p class="add_driver_name">---</p>

                                    @endif



                                    

                                    <p class="add_driver_title">@lang('frontend.Driver')</p>

                                </div>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="vehicle_add_name_img">

                                <div class="vehicle_add_img">



                                @if(isset($d->vehicle_id))

                                    @php 

                                        $vehicle=\App\Model\VehicleModel::find($d->vehicle_id);

                                    @endphp

                                    @if($vehicle && $vehicle->vehicle_image)

                                        <img src="{{ asset('uploads/'.$vehicle->vehicle_image) }}" style="border-radius: 5px;">

                                    @else

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/vehicle.svg') }}" style="border-radius: 5px;">

                                    @endif

                                @else

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/vehicle.svg') }}" style="border-radius: 5px;">

                                @endif





                                    

                                </div>

                                <div class="driver_add_name ms-0 ms-sm-4 ms-md-4 ms-lg-4 ms-xl-4">

                                @if(isset($d->vehicle_id))

                                    @php 

                                        $vehicle=\App\Model\VehicleModel::find($d->vehicle_id);

                                        $v_type=null;

                                        if(isset($vehicle->type_id))

                                        {

                                            $v_type=\App\Model\VehicleTypeModel::find($vehicle->type_id);

                                        }   

                                       

                                    @endphp

                                    @if($v_type && $v_type->vehicletype)

                                    <p class="add_driver_name">{{$v_type->vehicletype ?? '-'}}</p>

                                    @else

                                    <p class="add_driver_name">---</p>

                                    @endif

                                @else

                                <p class="add_driver_name">---</p>

                                @endif



                                   

                                    <p class="add_driver_title">@lang('frontend.Vehicle_Type')</p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="add_bottom_tagline">

                    <div class="row">

                        <div class="col-12">

                            <div class="add_bottom_tag">

                                <div class="row">

                                    <div class="col-6">

                                        <div class="add_book_on">

                                            <span>@lang('frontend.Booked_On')</span>

                                        </div>

                                    </div>

                                    <div class="col-6">

                                        <div class="add_book_on_date">

                                            <span>{{ date("d M 'y h.i A", strtotime($d->created_at)) }}</span>

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



@endforeach

    {{ $data->onEachSide(1)->links('pagination::bootstrap-4') }}

@else



<div class="text-center">

    <h4>No Data Found.</h4>

</div>



   

@endif