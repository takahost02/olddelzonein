@if (count($data) > 0)
    <div class="show-scrollbar" style="overflow-y: scroll;
height: 745px;
">




        @foreach ($data as $d)
            <div class="col-12 mt-3 ">
                <div class="card light-timeline p-3 shadow-sm selectable" data-action="classfound" data-id="{{ $d->id }}">
                   
                        <div class="card-header p-0">

                          
                            <div class="row">
                                <div class="col-10 col-sm-10 col-md-11 col-lg-11 col-xl-11">

                                   
                                    <h6 class="mb-0 booking_id">Booking : <span>#{{ $d->id }}</span></h6>
                                </div>
                                <div class="col-2 col-sm-2 col-md-1 col-lg-1 col-xl-1 my-auto text-end">

                                    <div class="dot-dropdown">
                                        <a class="dropdown-toggle img-set" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img
                                                src="{{ asset('assets/customer_dashboard/assets/img/svg/dropdown_dots.svg') }}">
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{ route('dashboard.booking_details', ['id' => $d->id]) }}"
                                                    class="dropdown-item">View Details</a>
                                           
                                            </li>
                                            <li>
                                                    <a
                                                        href="{{ route('dashboard.booking_details_ongoing', ['id' => $d->id]) }}" class="dropdown-item">Track
                                                        Ride</a></button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 pt-4">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                    aria-valuemax="100"
                                    style="width: 40%;">
                                </div>

                            </div>
                            <div class="row ">
                                <div class="col-12">
                                    <div class="white-timeline timeline timeline-one-side">
                                        <div class="timeline-block">
                                            <span class="timeline-step" title="Journey Date">
                                            </span>
                                            <div class="timeline-content mt-3">
                                                <div class="row">
                                                    <div class="col-4 col-sm-3 col-md-3 col-lg-3 col-xl-3 info-container">
                                                        <p class="text-xs mt-1 mb-0 info-container info-btn ">
                                                        @if ($d->getMeta('journey_date'))
                                                            {{ date("d M 'y", strtotime($d->getMeta('journey_date'))) }}
                                                        @else
                                                            ---
                                                        @endif
                                                        </p>
                                                        <span class="custom-info">Journey Date </span>

                                                       
                                                        
                                                    
                                                    </div>
                                                    <div class="col-8 col-sm-9 col-md-9 col-lg-9 col-xl-9 info-container1"  >
                                                        <p class="timeline_add info-btn1">
                                                            @if ($d->getMeta('journey_time'))
                                                                {{ date('h.i A', strtotime($d->getMeta('journey_time'))) }}
                                                            @else
                                                                ---
                                                            @endif

                                                        </p>

                                                        <span class="custom-info1">Journey Time</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="white-timeline timeline timeline-one-side">
                                        <div class="timeline-block ">
                                            <span class="timeline-step" title="Journey Started On">

                                            </span>
                                            <div class="timeline-content">

                                                <div class="row">
                                                    <div class="col-4 col-sm-3 col-md-3 col-lg-3 col-xl-3 info-container">
                                                        <p class="text-xs mt-1 mb-0 info-btn" >
                                                            @if ($d->getMeta('ridestart_timestamp'))
                                                                <?php [$datePart, $timePart] = explode(' ', $d->getMeta('ridestart_timestamp')); ?>
                                                                {{ date("d M 'y", strtotime($datePart)) }}
                                                            @else
                                                                ---
                                                            @endif

                                                        </p>
                                                        <span class="custom-info">Journey Started On</span>

                                                    </div>
                                                    <div class="col-8 col-sm-9 col-md-9 col-lg-9 col-xl-9 info-container1">
                                                        <p class="timeline_add info-btn1">

                                                            @if ($d->getMeta('ridestart_timestamp'))
                                                                <?php [$datePart, $timePart] = explode(' ', $d->getMeta('ridestart_timestamp')); ?>
                                                                {{ date('h.i A', strtotime($timePart)) }}
                                                            @else
                                                                ---
                                                            @endif

                                                        </p>

                                                        <span class="custom-info1">Journey Started Time</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="white-timeline timeline timeline-one-side">
                                        <div class="timeline-block">
                                            <span class="timeline-step" title="Journey Completed On">
                                                <!-- <i class="ni ni-bell-55 text-success text-gradient"></i> -->
                                            </span>
                                            <div class="timeline-content">
                                                <!-- <h6 class="text-dark text-sm font-weight-bold mb-0">$2400, Design changes</h6> -->
                                                <div class="row">
                                                    <div class="col-4 col-sm-3 col-md-3 col-lg-3 col-xl-3 info-container">
                                                        <p class="text-xs mt-1 mb-0 info-btn" >

                                                            @if ($d->getMeta('rideend_timestamp'))
                                                                <?php [$datePart, $timePart] = explode(' ', $d->getMeta('rideend_timestamp')); ?>
                                                                {{ date("d M 'y", strtotime($datePart)) }}
                                                            @else
                                                                ---
                                                            @endif
                                                        </p>

                                                        <span class="custom-info">Journey Completed On</span>
                                                    </div>
                       

                                                    <div class="col-8 col-sm-9 col-md-9 col-lg-9 col-xl-9 info-container1">
                                                        <p class="timeline_add info-btn1">

                                                            @if ($d->getMeta('rideend_timestamp'))
                                                                <?php [$datePart, $timePart] = explode(' ', $d->getMeta('rideend_timestamp')); ?>
                                                                {{ date('h.i A', strtotime($timePart)) }}
                                                            @else
                                                                ---
                                                            @endif

                                                        </p>

                                                    <span class="custom-info1">Journey Completed Time</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="booked-on">
                                                <span class="mb-0">@lang('frontend.Booked_On')</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="booked-on-date">
                                                <span
                                                    class="mb-0">{{ date("d M 'y h.i A", strtotime($d->created_at)) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="col-12 mt-3">
        <h4 class="text-center">No Ongoing Ride Found</h4>
    </div>
@endif