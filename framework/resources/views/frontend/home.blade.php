@extends('frontend.layouts.app')

@section('title') 
    <title> @lang('frontend.home') | {{ Hyvikk::get('app_name') }} </title>
@endsection


@section('css')

<link rel="stylesheet" href="{{ asset('assets/frontend/home.css') }}">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@endsection
@if(request()->is('home'))
@section('css')
<style>
    .main-section-background {
        display: unset;

    }
</style>
@endsection
@endif

@section('content')

{{-- <section class="hero-section--home">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="hero-content--home w-100 text-center mt-4">
                        <h5 class="light primary">0{{ Hyvikk::frontend('contact_phone') }}</h5>
<h1 class="mb-3" id="image_text">@lang('frontend.reliable_way')</h1>
<a href="#book_now"><button class="btn mx-auto form-submit-button">@lang('frontend.book_now')</button></a>
</div>
</div>
</div>
</div>
</section> --}}
{{-- ------------  Ends hero section --------------- --}}
<section class="hero-section background-container">
    <div class="hero-section-round">
        <img src="{{ asset('assets/images/round.png') }}" class="hero-section-round-1">
        <img src="{{ asset('assets/images/Rectangle.png') }}" class="hero-section-shadow">
    </div>
    <div class="hero-slider">
        <div class="owl-carousel owl-theme">
            <div>
                <div class="main-slider">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 order-md-first  order-sm-last">
                            <div class="main-slider-content">
                                <h1>@lang('frontend.reliable_way')</h1>
                                <h4><a href="#">0{{ Hyvikk::frontend('contact_phone') }}</a></h4>
                                <a href="#book_Sec" class="btn book-now-main-slider"> @lang('frontend.book_now')</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 order-md-last order-sm-first">
                            <div class="main-slider-img">
                                <img src="{{ asset('assets/images/tesla_back.png') }}"  >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="main-slider">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 order-md-first order-sm-last">
                            <div class="main-slider-content">
                                <h1>@lang('frontend.reliable_way')</h1>
                                <h4><a href="#">0{{ Hyvikk::frontend('contact_phone') }}</a></h4>
                                <a href="#book_Sec" class="btn book-now-main-slider"> @lang('frontend.book_now')</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 order-md-last order-sm-first pe-0">
                            <div class="main-slider-img">
                                <img src="{{ asset('assets/images/tesla_tru.png') }}" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="main-slider">
                    <div class="row">
                        <div class="col-md-6 col-lg-6 col-sm-12 order-md-first order-sm-last">
                            <div class="main-slider-content">
                                <h1>@lang('frontend.reliable_way')</h1>
                                <h4><a href="#">0{{ Hyvikk::frontend('contact_phone') }}</a></h4>
                                <a href="#book_Sec" class="btn book-now-main-slider"> @lang('frontend.book_now')</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 order-md-last order-sm-first">
                            <div class="main-slider-img">
                                <img src="{{ asset('assets/images/tesla_truck.png') }}" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ------------ Booking section  -------------- --}}
<section class="book-a-cab-detail" id="book_Sec">
    <div class="container">
        <div class="row">

            <div class="col-md-12 ">
                <div class="feet-img">
                    <img src="{{ asset('assets/images/FLEET.png') }}">
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="container">
                    {{-- @if (session('success'))
                    <div class="alert alert-primary col-sm-10 offset-sm-1" style="position:absolute;right:10px;top:33px;width:auto;z-index:9">
                        {{ session('success') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger col-sm-10 offset-sm-1">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif --}}
                <div class="message-booking custom-alert"></div>

                <div class="book-cab-form m-0 ">
                    <h1>@lang('frontend.book_a_cab')</h1>

                    <form class="row mt-2" method="POST" id="booking_form">

                        {!! csrf_field() !!}


                       
<input type="hidden" name="ride_type" class="ride_type" value="oneway"> 
<input type="hidden" name="booking_schedule_type" class="booking_schedule_type" value="1"> 


                        <div class="col-md-12 col-sm-12 col-lg-12 form-group book-for-label">
                            <div class="row" style="display:flex;align-items:center;">
                                <div class="col-4 col-sm-4 col-md-4 col-lg-4 ">
                                    <label for="inputEmail" class="form-label">@lang('frontend.book_for')</label>
                                </div>
                                <div class="col-8 col-sm-8 col-md-8 col-lg-8 col-xxl-8">
                                    <div class="btn-now" role="group">


                                        <div type="button" class="btn now  book-now" data-type="0" onclick="">
                                            @lang('frontend.book_for_now')

                                        </div>
                                        <div type="button" class="btn now active ms-3 book-later" data-type="1" data-radiotext="later">@lang('frontend.book_for_later')
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="load_radio_button">

                        </div>

                        <div class="col-md-12 input-effect pickup-dropoff-address">
                            <input class="effect-22 form-control" type="text" placeholder="" name="source_address" id="source_address">
                            <label class="form-label" style="position:absolute;">@lang('frontend.pickup_address')</label>
                            <span class="focus-bg error-source_address"></span>
                        </div>
                        
                        <div class="col-md-12 input-effect pickup-dropoff-address">
                            <input class="effect-22 form-control" type="text" placeholder="" name="dest_address" id="dest_address">
                            <label class="form-label" style="position:absolute;">@lang('frontend.dropoff_address')</label>
                            <span class="focus-bg error-dest_address"></span>
                        </div>


                        <div class="col-md-12 form-group date-time">
                            <div class="row">
                                <div class="col-5 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                                    <label for="journey_date" id="current_data_time" class="form-label">
                                        @lang('frontend.Pickup_date_and_Time')
                                    </label>
                                </div>
                                <div class="col-7 col-sm-6 col-md-8 col-lg-8 col-xl-8 col-xxl-8 d-flex">
                                    <div class="datetime">
                                        <input type="text" class="form-control date" name="datepicker2" id="datepicker2" />
                                        <input type="text" class="form-control time has-content pickup_time" name="journey_time" id="journey_time" />
                                        
                                    </div>
                                </div>
                        
                                <span class="error-pickup_date_time"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-12 mt-3 form-group">
                            <div class="row">
                                <div class="col-12">
                                    <label for="booking_schedule" class="form-label">
                                        @lang('frontend.For_One_Month')
                                    </label>
                                    <select class="form-select" name="booking_schedule" id="booking_schedule" required>
                                        <option value="" disabled selected>Select</option>
                                        <option value="0" {{ old('booking_schedule') == '0' ? 'selected' : '' }}>
                                            @lang('frontend.For_One_Month')
                                        </option>
                                        <option value="1" {{ old('booking_schedule') == '1' ? 'selected' : '' }}>
                                            @lang('frontend.For_One_Day')
                                        </option>
                                    </select>

                                </div>
                            </div>
                        </div>


                      
                        @if(Hyvikk::get('return_booking') == 1)

                        <div>
                            <div class="col-lg-12 mt-3 text-center">
                                <button type="button" class="btn active-btn" id="oneWayBtn">
                                    @lang('frontend.One_Way')
                                </button>
                                <button type="button" class="btn inactive-btn" id="returnWayBtn">
                                    @lang('frontend.Return_Way')
                                </button>
    
    
                                <div class="row form-group date-time1 d-none show-return-section">
                                    <div class="col-6  col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                                        <label for="inputState" id="current_data_time" class="form-label" style="font-size: 16px;font-weight: 700;padding-right: 21px;">
                                            
                                            @lang('frontend.return_pickup_date')
                                            
                                            
                                        </label>
    
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-8  col-lg-8 col-xl-8 col-xxl-8 d-flex">
                                        <div class="datetime">
                                            <input type="text" class="form-control date1 return_pickup_date" id="datepicker4" name="return_pickup_date" />
                                            <input type="text" class="form-control time1 return_pickup_time"   name="return_pickup_time">
                                        </div>
    
                                    </div>
                                    
                                    <!-- Dropoff Address Inputs Container -->
                                    <div id="dropoffAddressContainer">
                                        <div class="col-md-12 input-effect pickup-dropoff-address">
                                            <input class="effect-22 form-control" type="text" placeholder="" name="dropoff_address[]" id="dropoff_address">
                                            <label class="form-label" style="position:absolute;">@lang('frontend.dropoff_address')</label>
                                            <span class="focus-bg error-dropoff_address"></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Add More Button with Full Width and Same Alignment -->
                                    <div class="col-md-12 mt-2">
                                        <button type="button" class="btn btn-sm w-100" id="addDropoffAddressBtn"
                                            style="background-color: #000; color: #fff; border: none;">
                                            + Add Dropoff Address
                                        </button>
                                    </div>



                                    
                                    <span class="error-return_pickup_date_time">
    
                                    </span>
    
                                </div>
                            </div>
                        </div>
                      


                        @endif
                       


                        <div class="col-md-12 vehicle-type">
                            <div class="row">
                                <div class="col-5  col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4 vehicle-type-label">
                                    <label for="inputEmail4" class="form-label">@lang('frontend.Vehicle_Type')</label>
                                </div>
                                <div class="col-7 col-sm-6 col-md-8  col-lg-8 col-xl-8 col-xxl-8">
                                    <select class="form-select float-end custom-select v_type" name="vehicle_type" id="vehicle_type" aria-label="vehicle type" required>
                                        <option value="" disabled selected>@lang('frontend.vehicle_type')</option>
                                        @foreach ($vehicle_type as $type)
                                        <option value="{{ $type->id }}" {{ $type->id == old('vehicle_type') ? 'selected' : '' }}>
                                            {{ $type->vehicletype }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="focus-bg error-vehicle_type"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-12 material-type mt-3 ">
                            <div class="row">
                                <div class="col-5 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4 material-type-label">
                                    <label for="material_typeid" class="form-label">@lang('frontend.Material_Type')</label>
                                </div>
                                <div class="col-7 col-sm-6 col-md-8 col-lg-8 col-xl-8 col-xxl-8">
                                    <select class="form-select float-end custom-select m_type" name="material_typeid" id="material_typeid" aria-label="material type" required>
                                        <option value="" disabled selected>@lang('frontend.material_type')</option>
                                        @foreach ($material_type as $type)
                                            <option value="{{ $type->id }}" {{ $type->id == old('material_typeid') ? 'selected' : '' }}>
                                                {{ $type->materialtype }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="focus-bg error-material_typeid"></span>
                            </div>
                        </div>



@if(Auth::check())
    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
@endif






                        <div class="col-md-12 form-group num-person">
                            <div class="row">
                                <div class="col-5 col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4 num-person-label">
                                    <label for="no_of_persons" class="form-label">@lang('frontend.no_of_person')</label>
                                </div>
                                <div class="col-7 col-sm-6 col-md-8 col-lg-8 col-xl-8 col-xxl-8 justify-content-lg-start num-person-input">
                                    <div class="number-input-container">
                                        <input
                                            type="text"
                                            class="form-control has-content"
                                            name="no_of_persons"
                                            id="no_of_persons"
                                            value="1"
                                            min="1"
                                            step="1"
                                            required
                                        >
                                        <div class="icon">
                                            <div class="increment">
                                                <i class="fas fa-sort-up increment-icon"></i>
                                            </div>
                                            <div class="decrement">
                                                <i class="fas fa-sort-down decrement-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="focus-bg error-no_of_persons"></span>
                            </div>
                        </div>



                        <div class="col-md-12 form-group cab-form-text-area">
                            <textarea class="form-control" id="note_textarea" rows="4" cols="30" placeholder="@lang('frontend.other_things')" style="resize: none;opacity: 0.5;" required>{{ old('note') }}</textarea>
                        </div>

                        @php($methods = json_decode(Hyvikk::payment('method')))
                        @if (Hyvikk::frontend('admin_approval') == 0 && Hyvikk::api('api_key') != null)
                        <div class="col-lg-12">
                            <div class="checkboxes flex-row-center">
                                <label class="state custom-state">@lang('frontend.select_payment_method'): &nbsp;</label>
                                @foreach ($methods as $method)
                                <div class="pretty p-default p-round">
                                    <input type="radio" name="method" id="method" value="{{ $method }}" @if ($method=='cash' ) checked @endif>
                                    <div class="state custom-state">
                                        <label>{{ $method }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                        @endif

                        
                    

                        <div class="col-md-12 book-cab-submit">
                            <button class="tab-button mx-auto mt-3 btn booking-save" type="submit" id="booking">

                                <div class="spinner-border text-light hide-13 d-none"
                                    role="status">
                                    <span class="sr-only"></span>
                                </div>
                                <div class="hide-14">
                                    @lang('frontend.book_now')
                                </div>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 d-none d-sm-none d-md-none d-lg-block d-xl-block">

            <div class="car-img">
                <img src="{{ asset('assets/images/tesla.png') }}" class="round-shape">
                <img src="{{ asset('assets/images/tesla_come.png') }}" class="tesla-car-backimg">
            </div>
        </div>
    </div>
    {{-- </div>  --}}
</section>

{{-- ----------------- Vechicles Section ---------------------}}
<section class="our-vehicles  mt-2 mt-sm-2 mt-md-3 mt-lg-5 mt-xl-5">
    <div class="container ">
        <div class="row">
            <div class="col-12 mt-5 mt-sm-2 mt-md-3 mt-lg-5 mt-xl-5">
                <div class="our-vehicle-heading">
                    <h1>@lang('frontend.our_vehicle')</h1>
                </div>
            </div>
            <div class="col-12 col-md-6 col-sm-12 col-lg-8">
                <div class="slideshow">
                    <div class="owl-carousel">

                        @if(isset($vehicle) && count($vehicle) >0)

                        @foreach($vehicle as $v)
                        <div class="slides">
                            <img src="{{asset('uploads/'.$v->vehicle_image)}} " style="width:100%;"
                                data-icon="{{($v && $v->getMeta('icon')) ? asset('uploads/'.$v->icon)  :asset('assets/images/car_blue.png')}}" data-make="{{$v->make_name}}" data-model="{{$v->model_name}}"
                                data-vid="{{$v->id}}" data-passanger="{{$v->types->seats}}" data-mileage="{{$v->mileage}}"
                                data-actions="track-img">
                        </div>
                        @endforeach
                        @else
                        <div class="slides"><img src="{{ asset('uploads/car1.png') }}" style="width:100%;"></div>
                        @endif


                    </div>
                    <!-- <div class="owl-nav">
                        <button type="button" role="presentation" class="owl-prev">
                            <span aria-label="Previous">‹</span>
                        </button>
                        <button type="button" role="presentation" class="owl-next">
                            <span aria-label="Next">›</span>
                        </button>
                    </div> -->
                    <div class="car-slider-dots">
                        <div class="dots-line"></div>
                    </div>

                    <div id="counter"></div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-sm-12 col-lg-4">
                <div class="car-features">
                    <span class="show-make">-</span>
                    <h2 class="show-model">-</h2>
                    <div class="line"></div>
                    <div class="car-features-col">
                        <div class="row">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6 col-xxl-6 px-2 px-sm-4 px-md-2 px-lg-2 px-xl-4 image-hover">
                                <div class="car-features-detail">
                                    <div class="tesla-dark-logo">
                                        <img src="{{asset('assets/images/car_blue.png')}}" class="show-icon-img" width="83px" height="107px">
                                        <p class="d-none show-icon-img1">

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6 col-xxl-6 px-2 px-sm-4 px-md-2 px-lg-2 px-xl-4 ">
                                <div class="car-features-detail">
                                    <div class="passanger">
                                        <p style="font-size:32px;font-weight:500;padding-top:35px" class="show-passanger">0</p>
                                        <p style="padding-top:27px;">@lang('frontend.Passanger')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6 col-xxl-6 px-2 px-sm-4 px-md-2 px-lg-2 px-xl-4 ">
                                <div class="car-features-detail">
                                    <div class="rating">
                                        <p style="font-size:32px;font-weight:500;padding-top:30px" class="set-ratings">
                                        </p>
                                        <p style="padding-top:33px;">@lang('frontend.Ratings')</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-lg-6 col-xxl-6 px-2 px-sm-4 px-md-2 px-lg-2 px-xl-4 ">
                                <div class="car-features-detail">
                                    <div class="mileage">
                                        <p class="mileage-space show-mileage" style="font-size:32px;font-weight:500;padding-top:30px;">0
                                        </p>
                                        <p>
                                            <span style="font-weight: normal;">km</span>
                                        </p>
                                        <p style="padding-top:19px;">@lang('frontend.Mileage')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- -------------  Services section -------------- --}}
<section class="our-services">

    <div class="container  counter-parent">
        <div class="row">
            <div class="col-md-12 ">
                <div class="our-services-heading">
                    <h1>@lang('frontend.our_service')</h1>
                </div>
            </div>
        </div>
        <div id="counter1"></div>
        <div class="dots-line"></div>
        <div class="owl-carousel owl-carousel-vertical " style="margin-top: 30px;position:relative;">


            @if(isset($company_services) && count($company_services)> 0)

            @foreach ($company_services as $service)
            <div class="slides">
                <div class="row">
                    <div class="col-12 col-lg-4 col-md-6 col-sm-12  order-lg-first  order-md-first order-sm-last">
                        <div class="services-content">
                            <div class="services-1">
                                <div class="row">
                                    <div class="col-md-8 ">
                                        <div class="services-1-heading">
                                            <h3>{{ $service->title }}</h3>
                                            <div class="line"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="services-1-truckimg">
                                            <img src="{{ asset('assets/images/service.png') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 ">
                                        <div class="services-1-content">
                                            <p>{{ $service->description }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-8 order-lg-last  order-lg-last order-sm-first">
                        <div class="services-1-img">

                            @if ($service->image != null)
                            <img src="{{ url('uploads/' . $service->image) }}">
                            @else
                            <img src="{{ asset('assets/images/Mask.png') }}">
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @endif

        </div>
    </div>
    {{-- </div>
    </div>
    </div>  --}}

</section>
{{-- ------------  Testimonial section ------------ --}}

<section class="testimonial">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12 ">
                <div class="testimonial-heading">
                    <h1>@lang('frontend.testimonials')</h1>
                </div>
            </div>
        </div>
        <div>
            <div class="owl-carousel" style="position: relative;">

                @if(isset($testimonial) && count($testimonial) > 0)

                @foreach ($testimonial as $t)
                <div class="slides">
                    <div class="row">
                        <div class="col-lg-5 col-md-6 col-sm-12">
                            <div class="testimonials-imgs" style="position: relative;">
    @if (!empty($t->image))
        <img src="{{ url('uploads/' . $t->image) }}" alt="Testimonial Image" class="testimonial-image">
    @else
        <img src="{{ asset('assets/images/tesla_come.png') }}" alt="Default Testimonial Image" class="testimonial-image">
    @endif
</div>

                        </div>
                        <div class="col-lg-7 col-md-6 col-sm-12">
                            <div class="testimonial-content">
                                <h2>{{ $t->name }}</h2>
                                <div class="line"></div>
                                <p>{{ $t->details }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif



            </div>
            <!-- <div class="testimonial-dots">
                <div class="dots-line"></div>
            </div> -->
        </div>

    </div>
    </div>
</section>



@endsection


@section('script')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).on("click", ".v_type", function() {

        if ($(this).val() != null) {


            if($(".booking_type").val() == "return_way")
            {

                

                var select_status = $(".select-radio").val();
                var v_type = $(this).val();

                var date = $(".date").val();
                var time = $(".time").val();

                var return_pickup_date = $(".return_pickup_date").val();
                var return_pickup_time = $(".return_pickup_time").val();

                var booking_type="return_way";


                show_vehicle(select_status, v_type, date, time,booking_type,return_pickup_date,return_pickup_time);
            }
            else
            {
                var select_status = $(".select-radio").val();
                var v_type = $(this).val();
                var date = $(".date").val();
                var time = $(".time").val();

                var booking_type="one_way";


                show_vehicle(select_status, v_type, date, time,booking_type,null,null);
            }
        }
    });

    function show_vehicle(status = null, v_type = null, date = null, time = null,booking_type=null,return_pickup_date=null,return_pickup_time=null) {
        $.ajax({
            url: "{{url('get_free_vehicle')}}",
            type: "get",
            data: {
                'status': status,
                'type_id': v_type,
                'date': date,
                'time': time,
                'booking_type':booking_type,
                'return_pickup_date':return_pickup_date,
                'return_pickup_time':return_pickup_time


            },
            success: function(data) {
                var tblprint = "";
                if (data.status === 100 && data.data.length > 0) {
                    tblprint += "<option value='' disabled selected>Select a vehicle</option>";
                    $.each(data.data, function(i, item) {
                        tblprint += "<option value='" + item.id + "'>" + item.model_name + "</option>";
                    });
                } else {
                    tblprint += "<option value='' disabled selected>No vehicle Found</option>";
                }

                $('.show_vehicle').html(tblprint);

            }
        });
    }


    $(".image-hover").mouseover(function() {

        var img = "{{asset('assets/images/car_blue.png')}}";
        var img1 = "{{asset('assets/images/car_white.png')}}";
        if (img == $(this).find('.show-icon-img1').text()) {
            $(this).find('.show-icon-img1').text(img1);
            $(this).find('img').attr('src', img1);
        }

    }).mouseout(function() {
        var img = "{{asset('assets/images/car_blue.png')}}";
        var img1 = "{{asset('assets/images/car_white.png')}}";
        if (img1 == $(this).find('.show-icon-img1').text()) {
            $(this).find('.show-icon-img1').text(img);
            $(this).find('img').attr('src', img);
        }
    });


    $(".image-container").mouseover(function() {
        $(this).attr('src', $(this).data("hover"));
    }).mouseout(function() {
        $(this).attr('src', $(this).data("src"));
    });


    $(document).ready(function() {
        $(".active [data-actions='track-img']").each(function() {

            $(".show-icon-img1").text($(this).parent().find('img').data('icon'));

            $(".show-icon-img").attr("src", $(this).parent().find('img').data('icon'));

            $(".show-passanger").text($(this).parent().find('img').data('passanger'));

            var mileage = $(this).parent().find('img').data('mileage');
            $(".show-mileage").text(mileage === '' ? 0 : mileage);

            $(".show-make").text($(this).parent().find('img').data('make'));

            $(".show-model").text($(this).parent().find('img').data('model'));

            var vid = $(this).parent().find('img').data('vid');
            get_ratings(vid);
        });
    });



    $(document).on("click", ".owl-next", function() {

        $(".show-passanger").text($(this).parent().parent().find(".active").find('img').data('passanger'));


        var mileage = $(this).parent().parent().find(".active").find('img').data('mileage');

        $(".show-mileage").text(mileage == '' ? 0 : mileage);

        $(".show-make").text($(this).parent().parent().find(".active").find('img').data('make'));

        $(".show-model").text($(this).parent().parent().find(".active").find('img').data('model'));

        $(".show-icon-img").attr("src", $(this).parent().parent().find(".active").find('img').data('icon'));

        $(".show-icon-img1").text($(this).parent().parent().find(".active").find('img').data('icon'));

        var vid = $(this).parent().parent().find(".active").find('img').data('vid');
        get_ratings(vid);
    });

    $(document).on("click", ".owl-prev", function() {

        $(".show-passanger").text($(this).parent().parent().find(".active").find('img').data('passanger'));
        $(".show-mileage").text($(this).parent().parent().find(".active").find('img').data('mileage'));

        $(".show-make").text($(this).parent().parent().find(".active").find('img').data('make'));

        $(".show-model").text($(this).parent().parent().find(".active").find('img').data('model'));

        $(".show-icon-img").attr("src", $(this).parent().parent().find(".active").find('img').data('icon'));

        $(".show-icon-img1").text($(this).parent().parent().find(".active").find('img').data('icon'));

        var vid = $(this).parent().parent().find(".active").find('img').data('vid');
        get_ratings(vid);
    });



    function get_ratings(vid) {

        var url = "{{ url('get_ratings') }}";
        if (typeof jQuery === 'undefined') {
            return;
        }

        $.ajax({
            url: url,
            type: "get",
            data: {
                "vid": vid
            },
            success: function(data) {

                if (data && data.avg !== undefined) {
                    var print = `${data.avg}
                         <span style="font-weight: normal; padding-left: 5px;">/5</span>`;
                    $(".set-ratings").html(print);
                } else {
                    return;
                }
            },
            error: function(xhr, status, error) {
                return;
            }
        });


    }


    $(document).ready(function(){
        document.getElementById("oneWayBtn").addEventListener("click", function() {
        this.classList.add("active-btn");
        this.classList.remove("inactive-btn");
        document.getElementById("returnWayBtn").classList.add("inactive-btn");
        document.getElementById("returnWayBtn").classList.remove("active-btn");

        $(".booking_type").val('oneway');

        $(".show-return-section").addClass('d-none');


        $('#vehicle_type').prop('selectedIndex',0);
        $('.show_vehicle').prop('selectedIndex',0);

       
    });

    document.getElementById("returnWayBtn").addEventListener("click", function() {
        this.classList.add("active-btn");
        this.classList.remove("inactive-btn");
        document.getElementById("oneWayBtn").classList.add("inactive-btn");
        document.getElementById("oneWayBtn").classList.remove("active-btn");

        $(".booking_type").val('return_way');

        $(".show-return-section").removeClass('d-none');

        
        $('#vehicle_type').prop('selectedIndex',0);
        $('.show_vehicle').prop('selectedIndex',0);

  

    });
    });

   
</script>

<script>
    document.getElementById('addDropoffAddressBtn').addEventListener('click', function () {
        const container = document.getElementById('dropoffAddressContainer');

        // Create a new dropoff input group
        const newInput = document.createElement('div');
        newInput.classList.add('col-md-12', 'input-effect', 'pickup-dropoff-address', 'mt-2');
        newInput.innerHTML = `
            <input class="effect-22 form-control" type="text" placeholder="" name="dropoff_address[]" />
            <label class="form-label" style="position:absolute;">@lang('frontend.dropoff_address')</label>
            <span class="focus-bg error-dropoff_address"></span>
        `;

        container.appendChild(newInput);
    });
</script>

<script>
        // This now correctly updates the hidden field
$('.btn-now .btn').on('click', function () {
    const selectedType = $(this).data('type'); // 0 or 1
    $('.booking_schedule_type').val(selectedType); // this now works
    $('.btn-now .btn').removeClass('active');
    $(this).addClass('active');
});

$('#oneWayBtn').on('click', function () {
    $('.ride_type').val('oneway');
});

$('#returnWayBtn').on('click', function () {
    $('.ride_type').val('return_way');
});
</script>


<script>
$(document).ready(function () {
    $('#booking_form').on('submit', function (e) {
        e.preventDefault();

        const bookingType = $('.booking_schedule_type').val(); 
        const rideType = $('.ride_type').val(); 
        const isLater = bookingType === "1";

        const formData = {
            source_address: $('#source_address').val(),
            dest_address: $('#dest_address').val(),
            no_of_persons: $('#no_of_persons').val(),
            note: $('#note_textarea').val(),
            user_id: $('input[name=user_id]').val(),
            vehicle_typeid: $('#vehicle_type').val(), 
            material_typeid: $('#material_typeid').val(),
            ride_type: rideType,
            booking_schedule_type: bookingType,
            _token: '{{ csrf_token() }}',
            journey_date: $('#datepicker2').val(),      
            journey_time: $('#journey_time').val()      
        };


        if (isLater) {
            formData.journey_date = $('#datepicker2').val();
            formData.journey_time = $('#journey_time').val();
        }

        if (rideType === "return_way") {
            formData.return_pickup_date = $('#datepicker4').val();
            formData.return_pickup_time = $('.return_pickup_time').val();

            // Gather all dropoff_address[] values
            formData.dropoff_address = [];
            $('input[name="dropoff_address[]"]').each(function () {
                const val = $(this).val().trim();
                if (val !== '') {
                    formData.dropoff_address.push(val);
                }
            });
        }

        // ✅ Debug output before sending
        console.log("🚀 Sending Form Data:");
        Object.entries(formData).forEach(([k, v]) => {
            console.log(`${k}:`, v);
        });

        const ajaxUrl = isLater ? '{{ url("book-later") }}' : '{{ url("book-now") }}';

        $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log("✅ Success response:", response);
                if (response.success == "1") {
                    $('.message-booking').html(`<div class="alert alert-success">${response.message}</div>`);
                    $('#booking_form')[0].reset();
                } else {
                    $('.message-booking').html(`<div class="alert alert-danger">${response.message}</div>`);
                }
            },
            error: function (xhr, status, error) {
                console.error("❌ AJAX Error:", status, error);
                $('.message-booking').html(`<div class="alert alert-danger">An error occurred. Please try again.</div>`);
            }
        });
    });
});
</script>





@endsection