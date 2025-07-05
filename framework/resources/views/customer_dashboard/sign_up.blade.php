@extends('customer_dashboard.layouts.app')
@section('title')
    <title>@lang('frontend.sign_up') | {{ Hyvikk::get('app_name') }}</title>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/sign_up.css') }}">

@endsection
@section('content')
<div class="container position-sticky z-index-sticky top-0 p-0 d-sm-flex d-md-flex d-lg-none d-xl-none">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav id="myNavbar" class="d-flex d-sm-flex d-md-flex d-lg-none d-xl-none login_info navbar navbar-expand-lg  top-0 z-index-3 position-absolute  py-2 start-0 end-0 my-0">
                <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 space" href="{{url('/')}}">
                    <img src="{{ asset('assets/images/'. Hyvikk::get('logo_img') ) }}">
                </a>
                <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon mt-2">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </span>
                </button>
                <div class="collapse navbar-collapse" id="navigation">
                    <div class="container">
                        <div class="row space">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="header-caption">
                                    <p class="mb-0">@lang('frontend.Mobile_App')</p>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="header-link  d-flex align-items-center justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-end justify-content-xl-end">
                                    <p>@lang('frontend.Already_have') <a href="{{ route('log_in') }}">@lang('frontend.login')</a></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
<div class="sign-up-content" style="height: 100vh;">
    <div class="container-fluid px-0 d-flex flex-column min-vh-100">
        <div class="row  px-0  mx-0 d-flex">
            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-5 px-0 order-2 order-sm-2 order-md-2 order-lg-1 order-xl-1  ">
                <div class="blue_bg  d-flex flex-column flex-grow-1 position-relative res-grid" style="flex: 1;">
                    <div class="container">
                        <div class="row  d-flex justify-content-center">
                            <div class="col-12 col-sm-12 col-md-10 col-lg-12 col-xl-12 px-0 d-none d-sm-none d-md-none d-lg-flex d-xl-flex">
                                <div class="container-fluid ">
                                    <div class="row mt-4">
                                        <div class="col-4">
                                            <a class="navbar-brand  ms-lg-0 ms-3 space" href="{{url('/')}}">
                                                <img src="{{ asset('assets/images/'. Hyvikk::get('logo_img') ) }}">
                                            </a>
                                        </div>
                                        <div class="col-8 d-flex align-items-center justify-content-end ">
                                            <div class="top-header-caption">
                                                <div class="header-caption ">
                                                    <p class="mb-0">{{Hyvikk::frontend('sign_up_title')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                            @if(Hyvikk::frontend('sign_up_content'))


                            @foreach(json_decode(Hyvikk::frontend('sign_up_content')) as $s)


                            <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
                                <div class="features mt-5 mx-0">
                                    <div class="container px-0">
                                        <div class="row">
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="features-img">
                                                    <img src="{{url('/uploads'.'/'.$s->file_path)}}">
                                                </div>
                                            </div>
                                            <div class="col-8 d-flex align-items-center">
                                                <div class="feature-content ">
                                                    <div class="feature-title">
                                                        <p class="f-title">{{$s->title}}</p>
                                                        <p class="f-content">{{$s->subtitle}}.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            @endforeach

                            @endif


                            {{-- <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 ">
                                <div class="features mb-4 mx-0">
                                    <div class="container px-0">
                                        <div class="row">
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="features-img">
                                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/search.svg')}}">
                                                </div>
                                            </div>
                                            <div class="col-8 d-flex align-items-center">
                                                <div class="feature-content ">
                                                    <div class="feature-title">
                                                        <p class="f-title">Sidebar Search</p>
                                                        <p class="f-content">Search any Module / Section with Just a Few Key Presses.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 ">
                                <div class="features mb-4">
                                    <div class="container px-0">
                                        <div class="row">
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="features-img">
                                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/upgrade.svg')}}">
                                                </div>
                                            </div>
                                            <div class="col-8 d-flex align-items-center">
                                                <div class="feature-content ">
                                                    <div class="feature-title">
                                                        <p class="f-title">Upgraded Front-end Website</p>
                                                        <p class="f-content">A Revamped Front-end UI Design to give you a Fresh Experience.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 ">
                                <div class="features mb-4">
                                    <div class="container px-0">
                                        <div class="row">
                                            <div class="col-4 d-flex justify-content-center">
                                                <div class="features-img">
                                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/menu.svg')}}">
                                                </div>
                                            </div>
                                            <div class="col-8 d-flex align-items-center">
                                                <div class="feature-content ">
                                                    <div class="feature-title">
                                                        <p class="f-title">The Awesome "Font Awesome" Icons</p>
                                                        <p class="f-content">Because Good Icons Represent Features Better.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 ">
                                <div class="features-tagline mt-3 mt-sm-3 mt-md-3 mt-lg-5 mt-xl-5">
                                    <p>{{Hyvikk::frontend('sign_up_sub_title')}}</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="footer-copyright d-none d-sm-none d-md-none d-lg-flex d-xl-flex justify-content-center mb-4 mt-3" style="position: relative;bottom: 0;">
                                    {{-- <p class="mb-0 copyright-link">
                                        Copyright ©
                                        <script>
                                            document.write(new Date().getFullYear())

                                        </script> 


                                        
                                    </p> --}}
                                    
                                    <div class="text-white">{!! Hyvikk::get('web_footer') !!}</div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class=" col-12 col-sm-12 col-md-12 col-lg-6 col-xl-7 px-0 order-1 order-sm-1 order-md-1 order-lg-2 order-xl-2 mt-7 mt-sm-7 mt-md-7 mt-lg-0 mt-xl-0">
                <div class="white-bg d-flex flex-column flex-grow-1 position-relative res-grid" style="flex: 1;">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 mt-4 ">
                                <div class="header-link  d-none d-sm-none d-md-none d-lg-flex d-xl-flex align-items-center justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-end justify-content-xl-end">
                                    <p>@lang('frontend.Already_have') <a href="{{ route('log_in') }}">@lang('frontend.login')</a></p>
                                </div>
                            </div>
                            <div class="col-12">

                                <div class="register-msg custom-alerts"></div>


                                <div class="row res-form">
                                    <div class="col-12">
                                        <div class="sign_title">
                                            <p class="sign_up">@lang('frontend.sign_up')</p>
                                            <p class="sign_up_welcome">@lang('frontend.Welcome')</p>
                                            <p class="sign_up_welcome_content">@lang('frontend.join_us')</p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="sign-up-form">
                                            <form id="large-screen-form" method="POST">
                                                <div class="row">
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-1 mb-1 mb-sm-0 mt-sm-0 mt-md-0 mb-md-0 mt-lg-0 mb-lg-0 mt-xl-0 mb-xl-0">
                                                        <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.First_Name')</label>
                                                        <input type="text" name="first_name" class="form-control" id="exampleInputEmail1"  placeholder="Enter your First Name" oninput="validateformat(this)">
                                                        <span class="focus-bg error-first_name"></span>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-1 mb-1 mb-sm-0 mt-sm-0 mt-md-0 mb-md-0 mt-lg-0 mb-lg-0 mt-xl-0 mb-xl-0">
                                                        <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Last_Name')</label>
                                                        <input type="text" name="last_name" class="form-control" id="exampleInputEmail1"  placeholder="Enter your Last Name" oninput="validateformat(this)">
                                                        <span class="focus-bg error-last_name"></span>
                                                    </div>
                                                    <div class="col-12 ">
                                                        <div class="radio-btn-groups d-flex align-items-center mt-4 mb-3 ">
                                                            <label class="form-label custom-form-label mb-0">@lang('frontend.Select_Gender')</label>
                                                            <div class="radio-btn d-flex align-items-center ms-4">
                                                                <div class="form-check d-flex align-items-center">
                                                                  
                                                                    <input type="radio" class="black gender-value " name="gender" value="1" id="male" >
                                                                    <label class="custom-control-label custom-form-label mb-0 " for="male">@lang('frontend.male')</label>
                                                                </div>
                                                                <div class="form-check d-flex align-items-center">
                                                                  
                                                                    <input type="radio" class="black gender-value" name="gender" value="0" id="female"  checked>
                                                                    <label class="custom-control-label custom-form-label mb-0 " for="female">@lang('frontend.female')</label>
                                                                </div>
                                                                
                                                            </div>
                                                            <span class="focus-bg error-gender"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-1 mb-1 mb-sm-0 mt-sm-0 mt-md-0 mb-md-0 mt-lg-0 mb-lg-0 mt-xl-0 mb-xl-0">
                                                        <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Email_Id')</label>
                                                        <input type="email" name="email"  class="form-control"  placeholder="Enter your Email Address">
                                                        <span class="focus-bg error-email"></span>

                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-1 mb-1 mb-sm-0 mt-sm-0 mt-md-0 mb-md-0 mt-lg-0 mb-lg-0 mt-xl-0 mb-xl-0">
                                                        <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Phone_Number')</label>
                                                        <input type="text" name="phone" class="form-control"  placeholder="Enter your Phone Number" maxlength="15">
                                                        <span class="focus-bg error-phone"></span>
                                                    </div>
                                                    <div class="col-12 mb-0 mt-0 mb-sm-3 mt-sm-3 mt-md-3 mb-md-3 mt-lg-3 mb-lg-4 mt-xl-3 mb-xl-3">
                                                        <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Address_Optional')</label>
                                                        <input type="text" name="address" class="form-control"  placeholder="Enter your Address">
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-1 mb-1 mb-sm-0 mt-sm-0 mt-md-0 mb-md-0 mt-lg-0 mb-lg-0 mt-xl-0 mb-xl-0">
                                                        <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Password')
                                                            <div class="tooltip-container">
                                                                <span class="tooltip-trigger "><img src="{{ asset('assets/customer_dashboard/assets/img/svg/info-hexagon.svg') }}" class="ms-1"></span>
                                                                <div class="tooltip-content">
                                                                    <div class="">
                                                                        <ul class="pass_guide mb-1 mt-1">
                                                                            <li>
                                                                                <p>Min 1 Special Character (@ # $ % &amp; ! * ?)</p>
                                                                            </li>
                                                                            <li>
                                                                                <p>Min Length 6 Characters &amp; Upto 18 Characters</p>
                                                                            </li>
                                                                            <li>
                                                                                <p>Min 2 Numerical Characters</p>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                        <input type="password" name="password" class="form-control"  placeholder="Type Your Password">
                                                        <span class="focus-bg error-password"></span>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mt-1 mb-1 mb-sm-0 mt-sm-0 mt-md-0 mb-md-0 mt-lg-0 mb-lg-0 mt-xl-0 mb-xl-0">
                                                        <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Retype_Password')</label>
                                                        <input type="password" name="confirm_password" class="form-control" placeholder="Re-Type Your Password">
                                                        <span class="focus-bg error-confirm_password"></span>
                                                    </div>
                                                    <div class="col-12 checked-stats agree mt-4 ">
                                                        <div class="form-check form-check-input-1 d-flex align-items-center">
                                                            <input class=" form-check-input" type="checkbox"  id="flexCheckDefault" name="agree">
                                                            <label class="form-check-label custom-control-label custom-form-label mb-0" for="flexCheckDefault">
                                                                @lang('frontend.I_Agree_to_All') <a href="{{Hyvikk::frontend('terms')}}">@lang('frontend.Terms_Conditions')</a> @lang('frontend.and') <a href="{{Hyvikk::frontend('privacy_policy')}}">@lang('frontend.Privacy_Policies')</a> 
                                                                @lang('frontend.of_Company')
                                                            </label>
                                                        
                                                        </div>
                                                        <span class="focus-bg error-agree"></span>
                                                    </div>
                                                    <div class="col-12 d-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start  ">
                                                        <button type="button" class="btn btn-square-blue mt-3 mb-5 register-user">
                                                            <div class="spinner-border text-light hide-3 d-none"
                                                            role="status">
                                                            <span class="sr-only"></span>
                                                        </div>
                                                        <div class="hide-4">
                                                            @lang('frontend.sign_up')
                                                        </div>

                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-none d-sm-none d-md-none d-lg-flex d-xl-flex justify-content-center ">
                                <div class="footer-copyright dark-footer mb-4 mt-3" style="position: absolute;bottom: 0;">

                                    <div class="footer_link">
                                        {{-- <a href="javascript:;" target="_blank" class="me-0 me-sm-3 me-md-4 me-lg-4 me-xl-5 mb-sm-0 mb-2">
                                            Company
                                        </a>
                                        <a href="javascript:;" target="_blank" class="me-0 me-sm-3 me-md-4 me-lg-4 me-xl-5 mb-sm-0 mb-2">
                                            About
                                        </a>
                                    
                                        <a href="javascript:;" target="_blank" class="me-0 me-sm-3 me-md-4 me-lg-4 me-xl-5 mb-sm-0 mb-2">
                                            Services
                                        </a> --}}

                                        @if(Hyvikk::frontend('footer_link'))


                                        @foreach(json_decode(Hyvikk::frontend('footer_link')) as $f)
                          
                                            <a href="{{$f->url}}" target="_blank" class="me-0 me-sm-3 me-md-4 me-lg-4 me-xl-5 mb-sm-0 mb-2">{{$f->title}}</a>
                                          
                                        @endforeach
                          
                                        @endif

                                     
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex d-sm-flex d-md-flex d-lg-none d-xl-none order-3 order-sm-3 order-md-3 mt-4 mt-sm-4 mt-md-5 mb-3 mb-sm-3 mb-md-4 ">
                                @include('customer_dashboard.includes.footer')
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection


@section('script')

<script>
    function validateformat(input) {
    // Remove any non-alphabetic characters
    input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
    }
    function validateformat1(input) {
    // Remove any non-alphabetic characters
    input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>
@endsection