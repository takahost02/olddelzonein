<div class="main-section-background">
    @if (!Auth::guest() && (Auth::user()->user_type == 'C' || Auth::user()->user_type == 'D'))
        <div class="header" style="position:relative">
        @else
            <div class="header header-first " style="position:relative">
    @endif
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="main-menubar">
                <a class="navbar-brand" href="{{ route('frontend.home') }}"> <img src="{{ asset('assets/images/'. Hyvikk::get('logo_img') ) }}" width="172px" height="auto"></a> 

                <div class="res-collapse d-flex d-sm-flex d-md-flex d-lg-none d-xl-none ">
                    @if (!Auth::guest() && (Auth::user()->user_type == 'C' || Auth::user()->user_type == 'D'))
                        <div class="login-btn-res d-none">
                            <a href="{{url('/login')}}" style="text-decoration:none;border-radious:2px;"> <button type="button" class="btn " data-bs-toggle="modal"
                                >@lang('frontend.login')</button></a>
                        </div>
                    @else
                        <div class="login-btn-res">
                            <a href="{{url('/login')}}" style="text-decoration:none;border-radious:2px;"><button type="button" class="btn " data-bs-toggle="modal"
                               >@lang('frontend.login')</button></a>
                        </div>
                    @endif
                    @if (Request::is('/') || Request::is('booking-history*'))
                        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="my-1 mx-2 close"><i class="fa fa-xmark"></i></span>
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    @else
                        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="my-1 mx-2 close"><i class="fa fa-xmark"></i></span>
                            <span class="custom-toggler navbar-toggler-icon"></span>
                        </button>
                    @endif
                
                </div>
                <div class="collapse navbar-collapse" id="navbarsExample09">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex gap-1">
                
                        <!-- other nav items -->
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">@lang('frontend.home')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.about') }}">@lang('frontend.about')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.service') }}">@lang('frontend.service')</a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('frontend.contact') }}">@lang('frontend.contact')</a>
                        </li>
                
                        @if (!Auth::guest() && (Auth::user()->user_type == 'C' || Auth::user()->user_type == 'D'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.booking_history', Auth::user()->id) }}">
                                    @lang('frontend.booking_history')
                                </a>
                            </li>
                            <!-- Proper logout button for mobile -->
                            <li class="nav-item d-lg-none">
                                <a class="nav-link btn btn-sm btn-outline-danger w-100 text-center"
                                   href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    @lang('frontend.logout')
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>



               
            </div>
            @if (!Auth::guest() && (Auth::user()->user_type == 'C' || Auth::user()->user_type == 'D'))

                <div class="dropdown text-end refres" style="z-index: 999;">
                    <a href="#" class="d-block link-dark-none text-decoration-none" data-bs-toggle="modal"
                        data-bs-target="#profile-detail">
                        <div class="img-back-shadow1 img-back-shadow2">
                            <img src="{{ isset(Auth::user()->profile_pic) ? asset('uploads/'.Auth::user()->profile_pic) : asset('assets/images/l6.png') }}"
                                alt="mdo" class="rounded-circle refresh-image">
                        </div>
                    </a>
                </div>
                <div class="profile-detail-modal">
                    <div class="modal fade" id="profile-detail" tabindex="-1" aria-labelledby="profile-detail"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content">
                              <div class="modal-header">
                                   <div class="edit-profile d-xl-flex d-lg-flex d-md-flex d-sm-none d-none">
                                        <a href="{{url('dashboard')}}" target="_blank" class="custom-btn">@lang('frontend.Dashboard')</a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#edit_profile" class="custom-btn">
                                            @lang('frontend.Edit_Profile')</a>
                                    </div>
                                    
                                    <button type="button" class="close btn d-lg-none d-md-none d-sm-flex"
                                        data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body pt-1">
                                   
                                    <div class="container">
                                        <div class="row profile-sec">
                                            <div class="col-lg-6 col-md-6 col-12  set-profile">
                                                <div class="profile-img">


                                                    <img src="{{ isset(Auth::user()->profile_pic) ? asset('uploads/'.Auth::user()->profile_pic) : asset('assets/images/l6.png') }}"
                                                        class="refresh-image" style="border-radius: 5px;">

                                                    <div class="profile-img-on-name ">
                                                        <p style="font-weight: bold;letter-spacing: .8px;">
                                                            {{ Auth::user()->name }}</p>
                                                        <p style="font-weight: lighter;" class="ps-5">
                                                            @if (Auth::user()->gender == 1)
                                                            @lang('frontend.male')
                                                            @elseif(Auth::user()->gender == 0)
                                                                    @lang('frontend.female')
                                                            @endif

                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="profile-img-bottom-name ">
                                                    <p style="font-weight: bold;letter-spacing: .8px;">
                                                        {{ Auth::user()->name }}</p>
                                                    <p style="font-weight: lighter;">
                                                        @if (Auth::user()->gender == 1)
                                                        @lang('frontend.male')
                                                        @elseif(Auth::user()->gender == 0)
                                                                @lang('frontend.female')
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="edit-profile d-xl-none d-lg-none d-md-none d-sm-flex d-flex justify-content-center pt-4">
                                                    <a href="{{url('dashboard')}}" target="_blank" class="custom-btn">@lang('frontend.Dashboard')</a>
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#edit_profile" class="custom-btn">@lang('frontend.Edit_Profile')</a>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-12">
                                                <div class="profile-phone-email">
                                                    <div class="row">
                                                        <div class="col-12 ">
                                                            <div class="profile-phone">
                                                                <p>@lang('frontend.profile_Phone')</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 ">
                                                            <div class="profile-phone-nu">
                                                                <p>{{ Auth::user()->mobno }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 ">
                                                            <div class="profile-phone">
                                                                <p>@lang('frontend.Email')</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 ">
                                                            <div class="profile-phone-nu">
                                                                <p>{{ Auth::user()->email }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="profile-logout">
                                                                {{-- <a href="#" class="btn">Logout</a>  --}}
                                                                <a href="#" class="btn"
                                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">@lang('frontend.logout')</a>
                                                            </div>

                                                        </div>
                                                        <form id="logout-form" action="{{ url('user-logout') }}"
                                                            method="POST" style="display: none;">
                                                            {{ csrf_field() }}
                                                        </form>
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
                <div class="edit-profile-modal">
                    <div class="modal fade" id="edit_profile" tabindex="-1" aria-labelledby="edit_profile"
                        aria-hidden="true">

                        <div class="modal-dialog modal-md">
                            <div class="msg-edit-profile"></div>
                            <div class="modal-content">
                                <div class="modal-header d-lg-none d-md-none d-sm-flex d-flex"
                                    style="padding: 0rem 1.5rem;">
                                    <div class="edit-title  mt-0">
                                      
                                        <h3>@lang('frontend.edit_profile')</h3>
                                        <div class="line"></div>
                                    </div>
                                    <button type="button" class="close btn" data-bs-dismiss="modal"
                                        aria-label="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                           
                                <div class="modal-body">

                                    <div class="container-fluid">

                                        <div class="row">

                                            <form class="row mt-4" method="POST" id="profile_form_update"
                                                enctype="multipart/form-data">
                                                <div
                                                    class="col-md-8 col-sm-12  pe-0 ps-0 order-last order-sm-last order-md-first oder-lg-first ">
                                                    <div class="edit-profile-popup">
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col-12 p-0">
                                                                    <div class="edit-profile-title ">
                                                                        <div
                                                                            class="edit-title d-none d-sm-none d-md-block d-xl-block">
                                                                            <h3>@lang('frontend.edit_profile')</h3>
                                                                            <div class="line"></div>
                                                                        </div>
                                                                        <div class="edit-profile-form">
                                                                            <div class="container">
                                                                                <div class="row">

                                                                                    <div class="col-12">

                                                                                        <div class="col-md-12  gender-btn-edit-profile">
                                                                                            <div class="row ">
                                                                                                <div class="col-4">
                                                                                                    <label class="form-label gender-label">Gender</label>
                                                                                                </div>
                                                                                                <div class="col-8 p-0">
                                                                                                    <input type="hidden" value="{{Auth::user()->gender}}" class="hide_gender">
                                                                                                    <ul class="edit-gender-btn">
                                                                                                        <li>
                                                                                                            <input type="radio" id="a25"  class="edit_gender" style="cursor:pointer" name="gender" value="1" @if (Auth::user()->gender == 1) checked @endif />
                                                                                                            <label for="a25">@lang('frontend.male')</label>
                                                                                                        </li>
                                                                                                        <li>
                                                                                                            <input type="radio" id="a50"  class="edit_gender" style="cursor:pointer" name="gender" value="0" @if (Auth::user()->gender == 0) checked @endif />
                                                                                                            <label for="a50">@lang('frontend.female')</label>
                                                                                                        </li>
                                                                                                    </ul>

                                                                                                    <span
                                                                                                    class="focus-bg error-edit-gender"></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>




                                                                                        <div
                                                                                            class="col-12  input-effect edit-form">
                                                                                            <input
                                                                                                class="effect-22 form-control edit_fullname @if(Auth::check() && Auth::user()->name != '') has-content @endif" 
                                                                                                type="text"
                                                                                                name="full_name"
                                                                                                value="{{ Auth::user()->name ?? '' }}">
                                                                                            <label class="form-label"
                                                                                                style="position:absolute;">@lang('frontend.Name')</label>
                                                                                            <span
                                                                                                class="focus-bg error-edit-fullname"></span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-12  input-effect edit-form">
                                                                                            <input
                                                                                                class="effect-22 form-control edit_phone @if(Auth::check() && Auth::user()->mobno != '') has-content @endif"
                                                                                                type="text"
                                                                                                name="phone"
                                                                                                value="{{ Auth::user()->mobno ?? '' }}">
                                                                                            <label class="form-label"
                                                                                                style="position:absolute;">@lang('frontend.Mobile')</label>
                                                                                            <span
                                                                                                class="focus-bg error-edit-phone"></span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-12 input-effect edit-form">
                                                                                            <input
                                                                                                class="effect-22 form-control edit_email @if(Auth::check() && Auth::user()->email != '') has-content @endif"
                                                                                                type="email"
                                                                                                name="email"
                                                                                                value="{{ Auth::user()->email ?? '' }}">
                                                                                            <label class="form-label"
                                                                                                style="position:absolute;">@lang('frontend.Email')</label>
                                                                                            <span
                                                                                                class="focus-bg error-edit-email"></span>
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-12 edit-profile-confirm-btn">
                                                                                            <a href="#"><button
                                                                                                    class="btn profile_save"
                                                                                                    type="button">
                                                                                                    <div class="spinner-border text-light hide-5 d-none"
                                                                                                        role="status">
                                                                                                        <span
                                                                                                            class="sr-only"></span>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="hide-6">
                                                                                                        Confirm
                                                                                                    </div>

                                                                                                </button></a>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <div
                                                                            class="reset-password-link mt-3 mb-4 mb-sm-4  mb-md-0 mb-lg-0 mb-xl-0">
                                                                            <a href="#" data-bs-toggle="modal"
                                                                                data-bs-target="#reset_password">@lang('frontend.reset_password')</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div
                                                    class="col-md-4 col-sm-12 ps-0 pe-0  order-sm-first order-lg-last">
                                                    <div
                                                        class="edit-title d-lg-none d-md-none d-sm-none d-none ps-sm-5 ps-5">
                                                        <h3>@lang('frontend.Edit_Profile')</h3>
                                                        <div class="line"></div>
                                                    </div>
                                                    <div class="edit-profile-img">
                                                        <div class="profile-round-img">
                                                            <div class="p-r-img ">
                                                                <img class="user-profile refresh-image"
                                                                    src="{{ isset(Auth::user()->profile_pic) ? asset('uploads/'.Auth::user()->profile_pic) : asset('assets/images/l6.png') }}">

                                                                <div class="p-r-img-overlay">
                                                                    <a href="#" class="upload-button">@lang('frontend.Change_Photo')</a>
                                                                    <input class="file-upload edit_image"
                                                                        type="file" name="image"
                                                                        accept="image/*">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="profile-bottom-round-img">
                                                            <div class="round-profile">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="reset-password background-container">
                    <div class="modal fade" id="reset_password" tabindex="-1" aria-labelledby="reset_password"
                        aria-hidden="true">

                        <div class="modal-dialog modal-md">
                            <div class="msg-reset-password"></div>
                            <div class="modal-content">
                                <div class="modal-header d-lg-none d-md-none d-sm-flex d-flex"
                                    style="padding: 0rem 1.5rem;">
                                    <div class="reset-title  mt-0">
                                        <h3>@lang('frontend.reset_password')</h3>
                                        <div class="line"></div>
                                    </div>
                                    <button type="button" class="close btn" data-bs-dismiss="modal"
                                        aria-label="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>



                                <div class="modal-body">
                                    <div class="container">

                                        <div class="row">
                                            <div
                                                class="col-md-8 col-sm-12 ps-0 pe-0 order-last order-sm-last order-md-first oder-lg-first">
                                                <div class="resetpass-profile-popup">
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="resetpass-profile-title">
                                                                    <div
                                                                        class="reset-title d-lg-block d-md-block d-sm-none d-none">
                                                                        <h3>@lang('frontend.reset_password')</h3>
                                                                        <div class="line"></div>
                                                                    </div>
                                                                    <div class="reset-pass-form">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <form class="row mt-4" method="post"
                                                                                    id="reset-password">
                                                                                    <div
                                                                                        class="col-12  input-effect reset-form">
                                                                                        <input
                                                                                            class="effect-22 form-control"
                                                                                            type="password"
                                                                                            name="password" autocomplete="on">
                                                                                        <label class="form-label"
                                                                                            style="position:absolute;">
                                                                                            @lang('frontend.Enter_current_password')
                                                                                           </label>
                                                                                        <span
                                                                                            class="focus-bg error-current-password"></span>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="enter-pass-line">

                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12  input-effect reset-form"
                                                                                        style="margin-top:10px">
                                                                                        <input
                                                                                            class="effect-22 form-control"
                                                                                            type="password"
                                                                                            name="new_password" autocomplete="on">
                                                                                        <label class="form-label"
                                                                                            style="position:absolute;">
                                                                                            @lang('frontend.Enter_new_password')</label>
                                                                                        <span
                                                                                            class="focus-bg error-new-password"></span>
                                                                                    </div>

                                                                                    <div
                                                                                        class="col-12 input-effect reset-form">
                                                                                        <input
                                                                                            class="effect-22 form-control"
                                                                                            type="password"
                                                                                            name="confirm_password" autocomplete="on">
                                                                                        <label class="form-label"
                                                                                            style="position:absolute;">@lang('frontend.Confirm_new_password')
                                                                                        </label>
                                                                                        <span
                                                                                            class="focus-bg error-confirm-password"></span>

                                                                                    </div>
                                                                                    <div
                                                                                        class="col-12 reset-update-pass-btn">
                                                                                       
                                                                                        <button type="button"
                                                                                            id="submit"
                                                                                            class="reset-user-password bg-colored1 click-effect white bold qdr-hover-6 classic_form uppercase no-border radius">


                                                                                            <div class="spinner-border text-light hide-7 d-none"
                                                                                                role="status">
                                                                                                <span
                                                                                                    class="sr-only"></span>
                                                                                            </div>
                                                                                            <div class="hide-8">
                                                                                                Update password
                                                                                            </div>
                                                                                        </button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12 ps-0 pe-0  order-sm-first order-lg-last">
                                              
                                                <div class="reset-pass-img" style="">
                                                    <div class="reset-round-img">
                                                        <div class="r-r-img">
                                                            <img src="{{ isset(Auth::user()->profile_pic) ? asset('uploads/'.Auth::user()->profile_pic) : asset('assets/images/l6.png') }}"
                                                                class="refresh-image">
                                                        </div>
                                                    </div>
                                                    <div class="profile-bottom-round-img">
                                                        <div class="round-profile">
                                                            <img src="{{ asset('assets/images/profile-round.png') }}"
                                                                alt="">
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
            @else
                @if (session('success'))
                    <div class="alert alert-primary col-sm-10 offset-sm-1 "
                        style="position:absolute;right:10px;top:33px;width:auto;z-index:9">
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
                @endif
                <div class="signin-signup-btn d-md-none d-lg-flex">
                
                        <a href="{{url('/login')}}" style="text-decoration:none;border-radious:2px;"><button type="button" class="btn login d-none d-sm-none d-md-none d-lg-flex">@lang('frontend.login')</button></a>

                        <a href="{{url('/sign_up')}}" style="text-decoration:none;border-radious:2px;"><button type="button" class="btn sign-up d-md-none d-lg-flex">@lang('frontend.sign_up')</button></a>

                </div>
             
        </div>
        @endif



</div>
</nav>


