@extends('customer_dashboard.layouts.app')

@section('title')
    <title>@lang('frontend.Create_Booking') | {{ Hyvikk::get('app_name') }}</title>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/create_booking.css') }}">


<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item text-sm text-dark active"><a
            href="{{ url('/create_booking') }}"aria-current="page">@lang('frontend.Create_Booking')</a></li>
@endsection
@section('contents')

    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7">
            <div class="card shadow-sm">
                <div class="card-body px-3 px-sm-4 px-md-4 px-lg-4 px-xl-4 py-4">
                    @if (session('success'))
                        <div class="custom-alert-msg">
                            <div class="alert alert-success custom-alert " role="alert">
                                <strong style="color:white;">{{ session('success') }}</strong>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger col-sm-10 offset-sm-1" style="color:white;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="regForm" action="{{ route('booking.save') }}" method="post">
                        @csrf

                        <input type="hidden" name="booking_type" class="booking_type" value="oneway">

                        <div class="tab">
                            <div class="tab_header">
                                <p class="tab_count">1 / 3</p>
                                <p class="tab_title">@lang('frontend.Where_do_you_want_to_Go')</p>
                            </div>
                            <div class="row px-1 px-sm-1 px-md-4 px-lg-4 px-xl-4">
                                <div class="col-12  mt-3">
                                    <label for="" class="form-label mb-0">@lang('frontend.Pickup_Address')</label>
                                    <input type="text" class="form-control" id="pickup_address"name="pickup_address"
                                        aria-describedby="emailHelp" placeholder="Type & Select Pickup Address"
                                        value="{{ old('pickup_address') }}">
                                    <span class="error_pickup_address"></span>
                                </div>
                                <div class="col-12 mb-3 mt-3">
                                    <label for="" class="form-label mb-0">@lang('frontend.Drop_off_Address')</label>
                                    <input type="text" class="form-control" name="dropoff_address" id="dropoff_address"
                                        aria-describedby="emailHelp" placeholder="Type & Select Dropoff Address"
                                        value="{{ old('dropoff_address') }}">
                                    <span class="error_dropoff_address"></span>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                  <label for="example-time-input" class="form-label mb-0">@lang('frontend.Pickup_Date')</label>

                                    <div class="">
                                       <input type="text" class="form-control pickup_date" name="pickup_date" id="datepicker2" />
                                        <span class="error_pickup_date"></span>
                                    </div>
                                </div>
                                <div
                                    class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0">
                                    <div class="">
                                        <label for="example-time-input" class="form-label mb-0">@lang('frontend.Pickup_Time')</label>
                                        <div class="align-items-center" style="">
                                            <div class="input-group" style="border: 1px solid #d2d6da;">
                                                <input class="form-control pickup_time pt" type="text"
                                                      name="pickup_time">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <img
                                                            src="{{ asset('assets/customer_dashboard/assets/img/svg/time.svg') }}">
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="error_pickup_time"></span>
                                        </div>
                                    </div>

                                </div>


                                @if(Hyvikk::get('return_booking') == 1)

                                <div class="col-lg-12 mt-3 text-center">
                                    <button type="button" class="btn active-btn" id="oneWayBtn">
                                      @lang('frontend.One_Way')
                                    </button>
                                    <button type="button" class="btn inactive-btn" id="returnWayBtn">
                                      @lang('frontend.Return_Way')
                                    </button>


                                    <div class="row form-group date-time d-none show-return-section">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                          <label for="example-time-input" class="form-label mb-0 text-start w-100">

                                            @lang('frontend.rpd')

                                           
                                          </label>

                                            <div class="">

                                              <input type="text" class="form-control return_pickup_date " name="return_pickup_date" id="datepicker4" />
                                              <span class="error_return_pickup_date"></span>                                                


                                            
                                            </div>
                                        </div>

                                        <div
                                            class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0">
                                            <div class="">
                                                <label for="example-time-input" class="form-label mb-0 text-start w-100">
                                                  
                                                  @lang('frontend.rpt')
                                                </label>
                                                <div class="align-items-center" style="">
                                                    <div class="input-group" style="border: 1px solid #d2d6da;">
                                                        <input class="form-control return_pickup_time" type="text"
                                                            id="example-time-input"  name="return_pickup_time">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                <img
                                                                    src="{{ asset('assets/customer_dashboard/assets/img/svg/time.svg') }}">
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <span class="error_return_pickup_time"></span>
                                                </div>
                                            </div>

                                        </div>


                                     


                                    </div>
                                </div>

                                @endif

                                <div class="col-12  mt-3 d-flex align-items-center">
                                    <label for="" class="form-label mb-0">@lang('frontend.No_of_Persons_Travelling')</label>
                                    <input type="text" class="form-control number-input no_of_person"
                                        name="no_of_person" aria-describedby="emailHelp" style="" placeholder="E.g 3"
                                        value="{{ old('no_of_person') }}">
                                    <span class="error_no_of_person"></span>
                                </div>
                                <div class="col-12 mb-3 mt-3">
                                    <label for="" class="form-label mb-0">@lang('frontend.Extra_Notes_Optional')</label>
                                    <input type="text" name="note" value="{{ old('note') }}"
                                        class="form-control responsive-input extra_notes"
                                        placeholder="Mention Any other Extra Info. you want to convey to driver">

                                </div>

                              



                            </div>

                        </div>
                        <div class="tab">
                            <div class="tab_header">
                                <p class="tab_count">2 / 3</p>
                                <p class="tab_title">@lang('frontend.Select_Vehicle')</p>
                            </div>
                            <div class="row px-1 px-sm-1 px-md-4 px-lg-4 px-xl-4">

                                <div class="col-12 mt-3">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">@lang('frontend.Select_Vehicle_from_the_List')</label>
                                        <select class="form-select select-vehicle show-vehicle"
                                            aria-label="Default select example" name="vehicle_id">
                                            <option value='' selected disabled>Select Vehicle</option>


                                        </select>
                                        <span class="error_vehicle"></span>
                                    </div>
                                </div>

                                <div class="col-12  mt-1 mt-sm-2 mt-md-3 mt-lg-3 mt-xl-3 mb-4 show-vehicle-info">

                                </div>

                            </div>

                        </div>
                        <div class="tab">
                            <div class="tab_header">
                                <p class="tab_count">3 / 3</p>
                                <p class="tab_title">@lang('frontend.Booking_Confirmation')</p>
                            </div>
                            <div class="container px-0">
                                <div class="row px-1 px-sm-1 px-md-4 px-lg-4 px-xl-4">
                                    <div class="col-12">

                                        <div class="booking-info">

                                        </div>


                                        <div class="show-vehicle-info1">

                                        </div>
                                        <div class="show-fare-details">

                                        </div>



                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="overflow:auto;">
                            <div
                                class="px-1 px-sm-1 px-md-4 px-lg-4 px-xl-4 d-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start">
                                <button type="button" class="btn btn-square-dark mt-3 me-2 me-sm-4 me-md-5 me-lg-5 "
                                    id="prevBtn" onclick="nextPrev(-1)">@lang('frontend.Go_Back')</button>
                                <button type="button" class="btn btn-square-blue mt-3 " id="nextBtn"
                                    onclick="nextPrev(1)">@lang('frontend.Continue')</button>



                            </div>
                        </div>
                        <div style="text-align:center;margin-top:40px;display:none">
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
                            <span class="step"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5  mt-4 mt-sm-4 mt-md-4 mt-lg-0 mt-xl-0 res-indicator">
            <div class="card shadow-sm ">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="carousel-container">
                                
                             @php
                             $company_services = \App\Model\CompanyServicesModel::get();
                             @endphp
                             
                             @if(isset($company_services) && count($company_services) > 0)
                             <div id="companyServicesCarousel" class="carousel slide" data-bs-ride="carousel">
                                 
                                 <!-- Indicators -->
                                 <div class="carousel-indicators">
                                     @foreach ($company_services as $index => $service)
                                     <button type="button" data-bs-target="#companyServicesCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                                     @endforeach
                                 </div>
                             
                                 <!-- Carousel Items -->
                                 <div class="carousel-inner">
                                     @foreach ($company_services as $index => $service)
                                     <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                         <img src="{{ $service->image ? url('uploads/' . $service->image) : asset('assets/images/Mask.png') }}" class="d-block w-100" alt="Service Image">

                                         <div class="carousel-overlay"></div>
                                         <div class="carousel-caption">
                                           <div class="caption-top">
                                             <p class="carousel-title">{{$service->title}}</p>
                                             <p class="carousel-subtitle">{{ $service->description }}</p>
                                           </div>
                                           {{-- <div class="caption-bottom">
                                             <p>{{ $service->description }}</p>
                                           </div> --}}
                                         </div>


                                     </div>
                                     @endforeach
                                </div>
                             
                                 <!-- Controls -->
                                 <button class="carousel-control-prev" type="button" data-bs-target="#companyServicesCarousel" data-bs-slide="prev">
                                     <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                     <span class="visually-hidden">@lang('frontend.Previous')</span>
                                 </button>
                                 <button class="carousel-control-next" type="button" data-bs-target="#companyServicesCarousel" data-bs-slide="next">
                                     <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                     <span class="visually-hidden">@lang('frontend.Next')</span>
                                 </button>
                             </div>
                             @endif
                             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('script')

    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

      <script>

let pickupTimeInstance, returnTimeInstance;

$(document).ready(function () {
    // Flatpickr for time inputs
    pickupTimeInstance = flatpickr(".pickup_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: false,
        minuteIncrement: 1,
        disableMobile: true,
        onChange: function () {
            enforceReturnTime(true);
        }
    });

    returnTimeInstance = flatpickr(".return_pickup_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: false,
        minuteIncrement: 1,
        disableMobile: true
    });

    flatpickr(".return_dropoff_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: false,
        minuteIncrement: 1,
        disableMobile: true
    });

    // Bootstrap datepickers
    $('#datepicker2, #datepicker4, #datepicker6').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        clearBtn: true,
        autoclose: true,
        startDate: new Date(),
        // endDate: new Date(new Date().getFullYear() + 1, 11, 31)
    });

    // Set initial dates
    $('#datepicker2').datepicker('setDate', new Date());
    $('#datepicker4').datepicker('setDate', new Date());
    $('#datepicker6').datepicker('setDate', new Date());

    // Datepicker change event syncing
    $('#datepicker2').on('changeDate', function () {
        const pickupDate = $('#datepicker2').datepicker('getDate');
        if (pickupDate) {
            // Sync return pickup date
            $('#datepicker4').datepicker('setDate', pickupDate);
            $('#datepicker4').datepicker('setStartDate', pickupDate);

            // Sync return dropoff date
            $('#datepicker6').datepicker('setDate', pickupDate);
            $('#datepicker6').datepicker('setStartDate', pickupDate);
        }
        enforceReturnTime();
    });

    $('#datepicker4').on('changeDate', function () {
        const returnPickupDate = $('#datepicker4').datepicker('getDate');
        if (returnPickupDate) {
            // Sync return dropoff date
            $('#datepicker6').datepicker('setDate', returnPickupDate);
            $('#datepicker6').datepicker('setStartDate', returnPickupDate);
        }
        enforceReturnTime();
    });

    // When return pickup time changes, sync dropoff time
    $('.return_pickup_time').on('change', function () {
        const returnTime = $(this).val();
        if (returnTime) {
            const newDropoffTime = offsetTime(returnTime, 1); // +30 mins offset
            $('.return_dropoff_time').val(newDropoffTime);
        }
    });

    // Utility functions
    function timeToMinutes(time) {
        const [h, m] = time.split(':').map(Number);
        return h * 60 + m;
    }

    function minutesToTime(minutes) {
        const h = Math.floor(minutes / 60);
        const m = minutes % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    }

    function offsetTime(time, offsetMinutes) {
        const [h, m] = time.split(':').map(Number);
        let total = h * 60 + m + offsetMinutes;

        // Wrap around 24h if needed
        if (total >= 24 * 60) total = total % (24 * 60);

        const newH = Math.floor(total / 60);
        const newM = total % 60;
        return `${String(newH).padStart(2, '0')}:${String(newM).padStart(2, '0')}`;
    }

    function syncReturnDate() {
        const pickupDate = $('#datepicker2').datepicker('getDate');
        const returnDate = $('#datepicker4').datepicker('getDate');

        if (returnDate < pickupDate) {
            $('#datepicker4').datepicker('setDate', pickupDate);
        }

        $('#datepicker4').datepicker('setStartDate', pickupDate);
    }

    function enforceReturnTime(forceUpdate = false) {
        const pickupDate = $('#datepicker2').datepicker('getDate');
        const returnDate = $('#datepicker4').datepicker('getDate');
        const pickupTime = $('.pickup_time').val();
        const returnTime = $('.return_pickup_time').val();

        if (!pickupDate || !returnDate || !pickupTime) return;

        const isSameDay = pickupDate.toDateString() === returnDate.toDateString();
        const pickupMins = timeToMinutes(pickupTime);
        const minReturnTime = minutesToTime(pickupMins + 1);

        if (isSameDay) {
            returnTimeInstance.set({ minTime: minReturnTime });

            if (
                forceUpdate ||
                !returnTime ||
                timeToMinutes(returnTime) <= pickupMins
            ) {
                returnTimeInstance.setDate(minReturnTime, true);
            }
        } else {
            returnTimeInstance.set({ minTime: null });
        }
    }
});




      </script>

    

        @if (session('success'))
            <script>
                setTimeout(function() {
                    $(".custom-alert-msg").html('');
                }, 6000);
            </script>
        @endif

        <script>
            $(document).ready(function() {



                $(".select-vehicle").on("change", function() {
                    $.ajax({
                        url: "{{ route('booking.fetch') }}",
                        type: "get",
                        data: {
                            "id": $(this).val(),
                            "pickup_date": $(".pickup_date").val(),
                            "pickup_time": $(".pickup_time").val(),
                            "pickup_address": $("#pickup_address").val(),
                            "dropoff_address": $("#dropoff_address").val(),
                        },
                        success: function(data) {
                            $(".show-vehicle-info").html('');
                            $(".show-vehicle-info1").html('');
                            $(".booking-info").html('');
                            $(".show-fare-details").html('');

                            var vehicle_info = `<div class="card p-0 " style="box-shadow: unset;">
                       <div class="card-header px-0 pt-3 pb-2"
                         style="border-bottom: 2px solid #dcdcdc;">
                         <p class="vehicle_info_title">Vehicle Information</p>
                       </div>
                       <div class="card-body px-0 py-3">
                         <div class="booking_tab_content">
                           <div class="row">
                             <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                               <div class="row">
                                 <div class="col-12 ">
                                   <div class="row d-flex ">
                                     <div class="col-6">
                                       <div class="assign_vehicle_punch p-0">
                                         <div class="assign_punch-title">
                                           <p class="assign_punch">${data.vehicle.model_name}</p>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="col-6">
                                       <div class="p-0">
                                         <div class="assign_punch-img">
                                           <div style="background-color: ${data.vehicle.color_name};
                                           height: 20px;
                                           width: 42%;">
                                             
                                           </div>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                                   <div class="row">
                                     <div class="col-6 mt-3">
                                       <div class="create_booking booking_vehicle_marker ">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Maker</p>
                                           <p class="vehicle-info-detail">${data.vehicle.make_name}</p>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="col-6 mt-3">
                                       <div class="create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Vehicle Type</p>
                                           <p class="vehicle-info-detail">${data.v_type.vehicletype}</p>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="col-6 mt-2">
                                       <div class=" create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Engine</p>
                                           <p class="vehicle-info-detail">${data.vehicle.engine_type}</p>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="col-6 mt-2">
                                       <div class="create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Mileage</p>
                                           <p class="vehicle-info-detail">${data.vehicle.mileage} Km</p>
                                         </div>
                                       </div>
                                     </div>

                                     <div class="col-6 mt-2">
                                       <div class="create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Luggage Capt.</p>
                                           <p class="vehicle-info-detail">No.${data.luggage}</p>
                                         </div>
                                       </div>
                                     </div>


                                     <div class="col-6 mt-2">
                                       <div class="create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Passanger Capt.</p>
                                           <p class="vehicle-info-detail">No.${data.v_type.seats}</p>
                                         </div>
                                       </div>
                                     </div>


                                   </div>
                                 </div>
                               </div>
                             </div>
                             <div
                               class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0 res-vehicle-img">
                               <div class="vehicle_info_img ">
                                 <img src="{{ asset('./uploads/${data.vehicle.vehicle_image}') }}">
                               </div>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>`;

                            $(".show-vehicle-info").html(vehicle_info);


                            var vehicle_info1 = `<div class="card p-0 " style="box-shadow: unset;">
                       <div class="card-header px-0 pt-3 pb-2"
                         style="border-bottom: 2px solid #dcdcdc;">
                         <p class="vehicle_info_title">Selected Vehicle Info.</p>
                       </div>
                       <div class="card-body px-0 py-3">
                         <div class="booking_tab_content">
                           <div class="row">
                             <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                               <div class="row">
                                 <div class="col-12 ">
                                   <div class="row d-flex ">
                                     <div class="col-6">
                                       <div class="assign_vehicle_punch p-0">
                                         <div class="assign_punch-title">
                                           <p class="assign_punch">${data.vehicle.model_name}</p>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="col-6">
                                       <div class="p-0">
                                         <div class="assign_punch-img">
                                           <div style="background-color: ${data.vehicle.color_name};
                                           height: 20px;
                                           width: 42%;">
                                             
                                           </div>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                                   <div class="row">
                                     <div class="col-6 mt-3">
                                       <div class="create_booking booking_vehicle_marker ">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Maker</p>
                                           <p class="vehicle-info-detail">${data.vehicle.make_name}</p>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="col-6 mt-3">
                                       <div class="create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Vehicle Type</p>
                                           <p class="vehicle-info-detail">${data.v_type.vehicletype}</p>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="col-6 mt-2">
                                       <div class=" create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Engine</p>
                                           <p class="vehicle-info-detail">${data.vehicle.engine_type}</p>
                                         </div>
                                       </div>
                                     </div>
                                     <div class="col-6 mt-2">
                                       <div class="create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Mileage</p>
                                           <p class="vehicle-info-detail">${data.vehicle.mileage} Km</p>
                                         </div>
                                       </div>
                                     </div>

                                      <div class="col-6 mt-2">
                                       <div class="create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Luggage Capacity</p>
                                           <p class="vehicle-info-detail">${data.luggage}</p>
                                         </div>
                                       </div>
                                     </div>


                                     <div class="col-6 mt-2">
                                       <div class="create_booking booking_vehicle_marker">
                                         <div class="vehicle_marker">
                                           <p class="vehicle-info-title">Passanger Capacity</p>
                                           <p class="vehicle-info-detail">${data.v_type.seats}</p>
                                         </div>
                                       </div>
                                     </div>

                                     

                                   </div>
                                 </div>
                               </div>
                             </div>
                             <div
                               class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0 res-vehicle-img">
                               <div class="vehicle_info_img ">
                                 <img src="{{ asset('./uploads/${data.vehicle.vehicle_image}') }}">
                               </div>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>`;

                            $(".show-vehicle-info1").html(vehicle_info1);


                            var booking_info = `<div class="card p-0" style="box-shadow: unset;">
                         <div class="card-header px-0 pb-2 pt-3" style="border-bottom: 2px solid #dcdcdc;">
                           <p class="vehicle_info_title">Booking Order Details</p>
                         </div>
                         <div class="card-body px-0 py-3">
                           <div class="booking_tab_content">
                             <div class="container px-0">
                               <div class="row">
                                 <div class="col-12">
                                   <div class="booking_info_timeline  timeline timeline-one-side booking_con">
                                     <div class="timeline-block ">
                                       <span class="timeline-step timeline-steps">
                                         <!-- <i class="ni ni-bell-55 text-success text-gradient"></i> -->
                                       </span>
                                       <div class="timeline-content">
                                         <!-- <h6 class="text-dark text-sm font-weight-bold mb-0">$2400, Design changes</h6> -->
                                         <div class="container px-0">
                                           <div class="row">
                                             <div class="col-3">
                                               <p class=" mb-0 res-font" >Pickup</p>
                                             </div>
                                             <div class="col-9">
                                               <p class="timeline_add " style="font-size: 12px;">${$("#pickup_address").val()}</p>
                                             </div>
                                           </div>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                                   <div class="booking_info_timeline timeline timeline-one-side booking_con">
                                     <div class="timeline-block ">
                                       <span class="timeline-step timeline-steps">
                                       </span>
                                       <div class="timeline-content">
                                         <!-- <h6 class="text-dark text-sm font-weight-bold mb-0">$2400, Design changes</h6> -->
                                         <div class="container px-0">
                                           <div class="row ">
                                             <div class="col-3">
                                               <p class="  mb-0 res-font" >Drop-off</p>
                                             </div>
                                             <div class="col-9">
                                               <p class="timeline_add " style="font-size: 12px;">${$("#dropoff_address").val()}</p>
                                             </div>
                                           </div>
                                         </div>
                                       </div>
                                      
                                     </div>
                                   </div>
                                 </div>
                               </div>
                               <div class="col-12">
                                 <div class="booking_order_details ">
                                   <div class="container px-0">
                                     <div class="row d-flex align-items-center">
                                       <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                         <p class="vehicle_info_title" style="color:#2152FF;font-weight: 600;">Pickup
                                           Date-Time</p>
                                       </div>
                                       <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                         <p class="date_time ">${data.full_date}</p>
                                       </div>
                                       <div class="col-7 col-sm-4 col-md-4 col-lg-4 col-xl-4 mx-auto mx-sm-0 mx-md-0 mx-lg-0 mx-xl-0 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0">
                                         <div class="person_counter text-center  p-2">
                                           <span style="font-size: 18px;font-weight: 700;color: #344767;"> ${$(".no_of_person").val()}</span>
                                           <p style="font-size: 10px;font-weight: 400;color:#34476799">No of Persons
                                           </p>
                                         </div>
                                       </div>
                                     </div>
                                   </div>
                                 </div>
                               </div>
                               <div class="col-12 mt-3">
                                 <div class="booking_order_details ">
                                   <div class="container px-0">
                                     <div class="row">
                                       <div class="col-4 col-sm-3 col-md-3 col-mg-3 col-xl-3">
                                         <p class="vehicle_info_title" style="color:#2152FF;font-weight: 600;">Extra
                                           Notes
                                         </p>
                                       </div>
                                       <div class="col-8 col-sm-9 col-md-8 col-lg-9 col-xl-9">
                                         <p class="mb-0" style="color:#344767;font-weight: 400;font-size: 12px;word-break: break-all;">${($(".extra_notes").val()??'-')}</p>
                                       </div>
                                     </div>
                                   </div>
                                 </div>
                               </div>
                             </div>
                           </div>
                         </div>
                       </div>`;



                            $(".booking-info").html(booking_info);


                            var fare_details = `<div class="card p-0" style="box-shadow: unset;">
                         <div class="card-header px-0 pb-2 pt-3" style="border-bottom: 2px solid #dcdcdc;">
                           <p class="vehicle_info_title">Fare Details</p>
                         </div>
                         <div class="card-body px-0 py-3">
                           <div class="fare_order_detail">
                             <table class="table table-striped" style="border-collapse: collapse;">
                               <tbody>
                                 <tr>
                                   <td class="ps-2 ps-sm-2 ps-md-5 ps-lg-5 ps-xl-5 vehicle-info-title">Base Fare / Amount
                                   </td>
                                   <td style="text-align: center;" class="table-detail vehicle-info-detail">${data.currency} ${data.arr.total_fare}</td>
                                 </tr>
                                
                                 <tr>
                                   <td class="ps-2 ps-sm-2 ps-md-5 ps-lg-5 ps-xl-5 vehicle-info-title">Tax (%) - ${data.arr.total_tax_percent}%</td>
                                   <td style="text-align: center;" class="table-detail vehicle-info-detail">${data.currency} ${data.arr.total_tax_charge_rs}</td>
                                 </tr>
                                 <tr>
                                   <td class="ps-2 ps-sm-2 ps-md-5 ps-lg-5 ps-xl-5 vehicle-info-title">Fuel Charges</td>
                                   <td style="text-align: center;" class="table-detail vehicle-info-detail">-</td>
                                 </tr>
                                 <tr>
                                   <td class="ps-2 ps-sm-2 ps-md-5 ps-lg-5 ps-xl-5 "
                                     style="font-size: 13px;font-weight: 600;color:#344767">Sub Total
                                   </td>
                                   <td style="text-align: center;font-weight: 700;" class="table-detail vehicle-info-detail">${data.currency} ${data.arr.tax_total}
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="ps-2 ps-sm-2 ps-md-5 ps-lg-5 ps-xl-5 "
                                     style="font-size:16px;color:#2152FF;font-weight: 700;">Total Amount
                                   </td>
                                   <td style="text-align: center;font-size:16px;color:#2152FF;font-weight: 700;">${data.currency} ${data.arr.tax_total}
                                   </td>
                                 </tr>
                               </tbody>
                             </table>
                           </div>
                         </div>
                       </div>`;

                            $(".show-fare-details").html(fare_details);

                        }
                    });

                });
            });


            var currentTab = 0;
            showTab(currentTab);

            function showTab(n) {

                var x = document.getElementsByClassName("tab");
                for (var i = 0; i < x.length; i++) {
                    x[i].style.display = "none";
                }
                x[n].style.display = "block";

                if (n == 0) {
                    document.getElementById("prevBtn").style.display = "none";
                } else {
                    document.getElementById("prevBtn").style.display = "inline";
                }
                if (n == (x.length - 1)) {
                    document.getElementById("nextBtn").innerHTML = "Submit";
                } else {
                    document.getElementById("nextBtn").innerHTML = "Next";
                }
                fixStepIndicator(n)
            }

            function nextPrev(n) {


                var x = document.getElementsByClassName("tab");
                if (n == 1 && !validateForm()) return false;
                currentTab += n;
                if (currentTab >= x.length) {

                    currentTab = x.length - 1;
                    $("#regForm").submit();
                    return false;
                }
                showTab(currentTab);

                if (currentTab == 1 && n == 1) {


                    $(".show-vehicle-info").html('');
                    

                    $('.select-vehicle').prop('selectedIndex',0);

                    $.ajax({
                        url: "{{ url('/get-vehicle') }}",
                        type: "get",
                        data: {
                            "pickup_date": $(".pickup_date").val(),
                            "pickup_time": $(".pickup_time").val(),
                            "booking_type":$(".booking_type").val(),
                            'return_pickup_date':$(".return_pickup_date").val(),
                            'return_pickup_time':$(".return_pickup_time ").val(),

                        },
                        success: function(data) {
                            var tblprint = "";
                            if (data.status === 100 && data.data.length > 0) {
                                tblprint +=
                                    "<option value='' disabled selected>Select a vehicle</option>";
                                $.each(data.data, function(i, item) {

                                    tblprint += "<option value='" + item.id +
                                        "'>" + item
                                        .model_name + "</option>";
                                });


                            } else {
                                tblprint +=
                                    "<option value='' disabled selected>No vehicle Found</option>";
                            }


                            $('.show-vehicle').html(tblprint);
                        }
                    });

                }
            }

            function validateForm() {

                var x, y, i, valid = true;
                x = document.getElementsByClassName("tab");
                y = x[currentTab].getElementsByTagName("input");
                za = x[currentTab].getElementsByTagName("select");

                for (i = 0; i < za.length; i++) {
                    if (za[i].name === "vehicle_id") {
                        if (za[i].value === "") {
                            valid = false;
                            $('.error_vehicle').text('vehicle is required').css('color', 'red');
                        } else {
                            $('.error_vehicle').text('');
                        }
                    }
                }

                for (i = 0; i < y.length; i++) {
                    if (y[i].name === "pickup_address") {
                        if (y[i].value === "") {
                            valid = false;
                            $('.error_pickup_address').text('Pickup Address is required').css('color', 'red');
                        } else {
                            $('.error_pickup_address').text('');
                        }
                    }


                    if (y[i].name === "dropoff_address") {
                        if (y[i].value === "") {
                            valid = false;
                            $('.error_dropoff_address').text('Dropoff Address is required').css('color', 'red');
                        } else {
                            $('.error_dropoff_address').text('');
                        }
                    }
                    if (y[i].name === "pickup_date") {
                        if (y[i].value === "") {
                            valid = false;
                            $('.error_pickup_date').text('Pickup Date is required').css('color', 'red');
                        } else {
                            $('.error_pickup_date').text('');
                        }
                    }
                    if (y[i].name === "pickup_time") {
                        if (y[i].value === "") {
                            valid = false;
                            $('.error_pickup_time').text('Pickup Time is required').css('color', 'red');
                        } else {
                            $('.error_pickup_time').text('');
                        }
                    }

                    if (y[i].name === "no_of_person") {
                        if (y[i].value === "") {
                            valid = false;
                            $('.error_no_of_person').text('no of person is required').css('color', 'red');
                        } else {
                            $('.error_no_of_person').text('');
                        }
                    }

                    if($(".booking_type").val() == "return_way")
                    {
                         if (y[i].name === "return_pickup_date") {
                           if (y[i].value === "") {
                               valid = false;
                               $('.error_return_pickup_date').text('return pickup date is required').css('color', 'red');
                           } else {
                               $('.error_return_pickup_date').text('');
                           }
                       }
                       if (y[i].name === "return_pickup_time") {
                           if (y[i].value === "") {
                               valid = false;
                               $('.error_return_pickup_time').text('return pickup time is required').css('color', 'red');
                           } else {
                               $('.error_return_pickup_time').text('');
                           }
                       }

                    


                    }

                    

                }

                if (valid) {
                    document.getElementsByClassName("step")[currentTab].className += " finish";
                }
                return valid;
            }

            function fixStepIndicator(n) {

                var i, x = document.getElementsByClassName("step");
                for (i = 0; i < x.length; i++) {
                    x[i].className = x[i].className.replace(" active", "");
                }

                x[n].className += " active";
            }
        </script>
        <script>
            $(function() {
                var current = location.pathname;
                $('.navbar-nav li a').each(function() {
                    var $this = $(this);

                    if ($this.attr('href').substring(this.href.lastIndexOf('/') + 1) == current.substring(
                            current.lastIndexOf('/') + 1)) {
                        $this.addClass('active');
                    }

                    if (current.substring(current.lastIndexOf('/') + 1) == '') {
                        $('.navbar-nav li a').first().addClass('active');
                    }
                });

                $('.offcanvas-nav_links a').each(function() {
                    var $this = $(this);

                    if ($this.attr('href').substring(this.href.lastIndexOf('/') + 1) == current.substring(
                            current.lastIndexOf('/') + 1)) {
                        $this.addClass('active');
                    }

                    if (current.substring(current.lastIndexOf('/') + 1) == '') {
                        $('.offcanvas-nav_links a').first().addClass('active');
                    }
                });


            });
        </script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ Hyvikk::api('api_key')  }}&libraries=places&callback=initMap"
            async defer></script>

        @if (Hyvikk::api('google_api') == '1')
            <script>
                function initMap() {
                    $('#pickup_address').attr("placeholder", "");
                    $('#dropoff_address').attr("placeholder", "");
                    // var input = document.getElementById('searchMapInput');
                    var pickup_addr = document.getElementById('pickup_address');
                    new google.maps.places.Autocomplete(pickup_addr);

                    var dest_addr = document.getElementById('dropoff_address');
                    new google.maps.places.Autocomplete(dest_addr);


                }
            </script>
        @endif

        <script>
            document.getElementById("oneWayBtn").addEventListener("click", function() {
                this.classList.add("active-btn");
                this.classList.remove("inactive-btn");
                document.getElementById("returnWayBtn").classList.add("inactive-btn");
                document.getElementById("returnWayBtn").classList.remove("active-btn");

                $(".booking_type").val('oneway');

                $(".show-return-section").addClass('d-none');
            });

            document.getElementById("returnWayBtn").addEventListener("click", function() {
                this.classList.add("active-btn");
                this.classList.remove("inactive-btn");
                document.getElementById("oneWayBtn").classList.add("inactive-btn");
                document.getElementById("oneWayBtn").classList.remove("active-btn");

                $(".booking_type").val('return_way');

                $(".show-return-section").removeClass('d-none');

            });
        </script>
    @endsection
