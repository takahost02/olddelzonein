@if (!Auth::guest() && (Auth::user()->user_type == 'C'))

<nav class="navbar navbar-main navbar-expand-lg px-0  shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">

    <div class="container-fluid py-1 px-3">

        <nav aria-label="breadcrumb">

          

            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5 d-flex">



                <li class="breadcrumb-item text-sm"><a href="{{url('/dashboard')}}" class="opacity-5 text-dark"> Pages </a></li>



                @yield('breadcrumb')

            </ol>

            <h6 class="font-weight-bolder mb-0">



       

       

                @if(Route::currentRouteName() == "dashboard")

                  @lang('frontend.Dashboard')

                @elseif(Route::currentRouteName() == "my_bookings")

                  @lang('frontend.My_Bookings')

              @elseif(Route::currentRouteName() == "create_booking")

                  @lang('frontend.Create_Booking')

              @elseif(Route::currentRouteName() == "user_profile")

                  @lang('frontend.Profile')

              @elseif(Route::currentRouteName() =="dashboard.booking_details")

                  @lang('frontend.Booking_Details')

              @elseif(Route::currentRouteName() =="dashboard.booking_details_ongoing")

                  @lang('frontend.Booking_Details_Ongoing')

              @else

              {{ ucwords(str_replace('_', ' ', Route::currentRouteName())) }}

              @endif

            </h6>

        </nav>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">

            <div class="ms-sm-auto pe-md-3 d-flex align-items-center">

                <ul class="navbar-nav  justify-content-end">

                    <li class="nav-item d-flex align-items-center">

                        <a href="#" class="nav-link text-body font-weight-bold px-0" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                            {{-- <i class="fa fa-user me-sm-1 "> </i>  --}}

                            <i class="fa fa-sign-out-alt"></i>

                            <span class="d-sm-inline d-none">@lang('frontend.logout')</span>

                        </a>

                    </li>

                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">

                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">

                            <div class="sidenav-toggler-inner">

                                <i class="sidenav-toggler-line"></i>

                                <i class="sidenav-toggler-line"></i>

                                <i class="sidenav-toggler-line"></i>

                            </div>

                        </a>

                    </li>

                </ul>

            </div>

        </div>

        <form id="logout-form" action="{{ url('user-logout') }}" method="POST" style="display: none;">

            {{ csrf_field() }}

        </form>

    </div>

</nav>



@else

<nav class="login_info navbar navbar-expand-lg  top-0 z-index-3 position-absolute my-0 py-2 start-0 end-0 ">

    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 space" href="{{url('/')}}">

        <img src="{{ asset('assets/images/'. Hyvikk::get('logo_img') ) }}" width="172px" height="auto">

    </a>



    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation2" aria-controls="navigation2" aria-expanded="false" aria-label="Toggle navigation2">

        <span class="navbar-toggler-icon mt-2">

            <span class="navbar-toggler-bar bar1"></span>

            <span class="navbar-toggler-bar bar2"></span>

            <span class="navbar-toggler-bar bar3"></span>

        </span>

    </button>

    <div class="collapse navbar-collapse" id="navigation2">

        <div class="container">

            <div class="row space">

                <!--<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 ">-->

                <!--    <ul class="navbar_form navbar-nav">-->

                <!--        <li class="nav-item">-->

                <!--            <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{url('/')}}">-->

                <!--              @lang('frontend.home')-->

                <!--            </a>-->

                <!--        </li>-->

                <!--        <li class="nav-item">-->

                <!--            <a class="nav-link me-2" href="{{url('about')}}">-->



                <!--                @lang('frontend.about')-->

                <!--            </a>-->

                <!--        </li>-->

                <!--        <li class="nav-item">-->

                <!--            <a class="nav-link me-2" href="{{url('contact')}}">-->

                             

                <!--                @lang('frontend.contact')-->

                <!--            </a>-->

                <!--        </li>-->

                       

                <!--    </ul>-->

                <!--</div>-->

                <!--<div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">-->

                <!--    <ul class="navbar-nav  d-flex flex-row flex-sm-row flex-md-row justify-content-center justify-content-sm-around justify-content-md-around justify-content-lg-end justify-content-xl-end align-items-start align-items-sm-start align-items-md-start align-items-lg-center align-items-xl-center justify-content-end mt-3 mt-sm-3 mt-md-3 mt-lg-0 mt-xl-0">-->

                <!--        <li class="nav-item d-flex align-items-center">-->

                <!--            <a class="btn btn-round btn-sm mb-0 login_btn me-2 "  href="{{ route('log_in') }}">@lang('frontend.login')</a>-->

                <!--        </li>-->

                <!--        <li class="nav-item d-flex align-items-center ms-0 ms-sm-0 ms-md-0 ms-lg-4 ms-xl-4">-->

                <!--            <a href="{{ route('sign_up') }}" class="btn btn-sm btn-round mb-0 me-1 sign_up_btn mt-0 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0">@lang('frontend.sign_up')</a>-->

                <!--        </li>-->

                <!--    </ul>-->

                <!--</div>-->



            </div>

        </div>

        <div class="social-media-icons mt-4 mt-sm-4 mt-md-4 mt-lg-0 mt-xl-0 mb-2 mb-sm-2 mb-md-2 mb-lg-0 mb-xl-0 d-flex justify-content-center ">

            <div class="social-media">

                <div class="social_icon">

                    <a href="{{ Hyvikk::frontend('twitter') }}" class="social_icon_link d-none d-sm-none d-md-none d-lg-flex d-xl-flex">



                        <svg version="1.1" id="svg1" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22px" height="22px" viewBox="0 0 2122 2122" style="enable-background:new 0 0 2122 2122;" xml:space="preserve">



                            <g>

                                <path class="st0" d="M1118.2,1028.6l-45-65.4L776.7,532.3H618.9l366.7,532.8l45,65.4l316,459.2h157.7L1118.2,1028.6z

                                  M1118.2,1028.6l-45-65.4L776.7,532.3H618.9l366.7,532.8l45,65.4l316,459.2h157.7L1118.2,1028.6z M1118.2,1028.6l-45-65.4

                                  L776.7,532.3H618.9l366.7,532.8l45,65.4l316,459.2h157.7L1118.2,1028.6z M1473.9,83.7C1347,30,1207.4,0.3,1061,0.3

                                  c-439.3,0-816.3,267.1-977.3,647.8C30,775,0.3,914.5,0.3,1061c0,585.8,474.9,1060.7,1060.7,1060.7c439.3,0,816.3-267.1,977.3-647.8

                                  c53.7-126.9,83.4-266.4,83.4-412.9C2121.7,621.7,1854.5,244.7,1473.9,83.7z M1305.1,1668.8l-23.6-34.2l-304.2-442L568,1668.8H467

                                  l465.3-541.5L553.7,577.2l-85.3-124h349.9l23.6,34.3l284.7,413.6l298.9-347.9l86.4-100h100.9l-441.2,513.3l398,578.3l85.3,124

                                  H1305.1z M1073.2,963.2L776.7,532.3H618.9l366.7,532.8l45,65.4l316,459.2h157.7l-386.2-561.1L1073.2,963.2z M1118.2,1028.6

                                  l-45-65.4L776.7,532.3H618.9l366.7,532.8l45,65.4l316,459.2h157.7L1118.2,1028.6z M1118.2,1028.6l-45-65.4L776.7,532.3H618.9

                                  l366.7,532.8l45,65.4l316,459.2h157.7L1118.2,1028.6z M1118.2,1028.6l-45-65.4L776.7,532.3H618.9l366.7,532.8l45,65.4l316,459.2

                                  h157.7L1118.2,1028.6z" />

                            </g>

                        </svg>

                    </a>



                    <a href="{{ Hyvikk::frontend('twitter') }}" class="me-4 me-sm-4 me-md-4 me-lg-0 me-xl-0  d-flex d-sm-flex d-md-flex  d-lg-none d-xl-none">

                        <svg xmlns="http://www.w3.org/2000/svg" height="22" width="22" viewBox="0 0 512 512">

                            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" /></svg>

                    </a>



                   

                </div>

                <div class="social_icon">

                    <a href="{{ Hyvikk::frontend('facebook') }}" class="social_icon_link d-none d-sm-none d-md-none d-lg-flex d-xl-flex">

                        <svg id="svg2" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" fill="#fff">

                            <g clip-path="url(#clip0_284_507)">

                                <g filter="url(#filter0_d_284_507)">

                                    <path d="M11.0002 1.87C5.9585 1.87 1.8335 5.98583 1.8335 11.055C1.8335 15.6383 5.1885 19.4425 9.57016 20.13V13.7133H7.24183V11.055H9.57016V9.02916C9.57016 6.72833 10.936 5.46333 13.0352 5.46333C14.0343 5.46333 15.0793 5.6375 15.0793 5.6375V7.90166H13.9243C12.7877 7.90166 12.4302 8.6075 12.4302 9.33166V11.055H14.9785L14.566 13.7133H12.4302V20.13C14.5902 19.7889 16.5572 18.6867 17.9759 17.0226C19.3947 15.3584 20.1717 13.2418 20.1668 11.055C20.1668 5.98583 16.0418 1.87 11.0002 1.87Z" />

                                </g>

                            </g>

                            <defs>

                                <filter id="filter0_d_284_507" x="-2.1665" y="-1.13" width="26.3335" height="26.26" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">

                                    <feFlood flood-opacity="0" result="BackgroundImageFix" />

                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />

                                    <feOffset dy="1" />

                                    <feGaussianBlur stdDeviation="2" />

                                    <feComposite in2="hardAlpha" operator="out" />

                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />

                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_284_507" />

                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_284_507" result="shape" />

                                </filter>

                                <clipPath id="clip0_284_507">

                                    <rect width="22" height="22" fill="#fff" />

                                </clipPath>

                            </defs>

                        </svg> </a>

                    <a href="{{ Hyvikk::frontend('facebook') }}" class="me-4 me-sm-4 me-md-4 me-lg-0 me-xl-0"><i class="fab fa-facebook d-flex d-sm-flex d-md-flex  d-lg-none d-xl-none" style="color:#316FF6; font-size:24px"></i> </a>

                </div>

                <div class="social_icon">

                    <a href="{{ Hyvikk::frontend('linkedin') }}" class="social_icon_link d-none d-sm-none d-md-none d-lg-flex d-xl-flex">

                        <svg id="svg3" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" fill="#fff">

                            <g clip-path="url(#clip0_284_509)">

                                <g filter="url(#filter0_d_284_509)">

                                    <path d="M17.4167 2.75C17.9029 2.75 18.3692 2.94315 18.713 3.28697C19.0568 3.63079 19.25 4.0971 19.25 4.58333V17.4167C19.25 17.9029 19.0568 18.3692 18.713 18.713C18.3692 19.0568 17.9029 19.25 17.4167 19.25H4.58333C4.0971 19.25 3.63079 19.0568 3.28697 18.713C2.94315 18.3692 2.75 17.9029 2.75 17.4167V4.58333C2.75 4.0971 2.94315 3.63079 3.28697 3.28697C3.63079 2.94315 4.0971 2.75 4.58333 2.75H17.4167ZM16.9583 16.9583V12.1C16.9583 11.3074 16.6435 10.5474 16.0831 9.98693C15.5226 9.42651 14.7626 9.11167 13.97 9.11167C13.1908 9.11167 12.2833 9.58833 11.8433 10.3033V9.28583H9.28583V16.9583H11.8433V12.4392C11.8433 11.7333 12.4117 11.1558 13.1175 11.1558C13.4579 11.1558 13.7843 11.291 14.025 11.5317C14.2656 11.7724 14.4008 12.0988 14.4008 12.4392V16.9583H16.9583ZM6.30667 7.84667C6.7151 7.84667 7.10681 7.68442 7.39561 7.39561C7.68442 7.10681 7.84667 6.7151 7.84667 6.30667C7.84667 5.45417 7.15917 4.7575 6.30667 4.7575C5.8958 4.7575 5.50177 4.92072 5.21124 5.21124C4.92072 5.50177 4.7575 5.8958 4.7575 6.30667C4.7575 7.15917 5.45417 7.84667 6.30667 7.84667ZM7.58083 16.9583V9.28583H5.04167V16.9583H7.58083Z" />

                                </g>

                            </g>

                            <defs>

                                <filter id="filter0_d_284_509" x="-1.25" y="-0.25" width="24.5" height="24.5" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">

                                    <feFlood flood-opacity="0" result="BackgroundImageFix" />

                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />

                                    <feOffset dy="1" />

                                    <feGaussianBlur stdDeviation="2" />

                                    <feComposite in2="hardAlpha" operator="out" />

                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />

                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_284_509" />

                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_284_509" result="shape" />

                                </filter>

                                <clipPath id="clip0_284_509">

                                    <rect width="22" height="22" fill="#fff" />

                                </clipPath>

                            </defs>

                        </svg>

                    </a>

                    <a href="{{ Hyvikk::frontend('linkedin') }}" class="me-4 me-sm-4 me-md-4 me-lg-0 me-xl-0"><i class="fab fa-linkedin d-flex d-sm-flex d-md-flex d-lg-none d-xl-none" style="color:#0A66C2;font-size:24px"></i> </a>

                </div>

                <div class="social_icon">

                    <a href="{{ Hyvikk::frontend('instagram') }}" class="social_icon_link d-none d-sm-none d-md-none d-lg-flex d-xl-flex">

                        <svg id="svg4" width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg" fill="#fff">

                            <g clip-path="url(#clip0_284_513)">

                                <g filter="url(#filter0_d_284_513)">

                                    <path d="M7.15016 1.83331H14.8502C17.7835 1.83331 20.1668 4.21665 20.1668 7.14998V14.85C20.1668 16.26 19.6067 17.6124 18.6096 18.6094C17.6125 19.6065 16.2602 20.1666 14.8502 20.1666H7.15016C4.21683 20.1666 1.8335 17.7833 1.8335 14.85V7.14998C1.8335 5.73991 2.39364 4.3876 3.39071 3.39053C4.38778 2.39346 5.7401 1.83331 7.15016 1.83331ZM6.96683 3.66665C6.09161 3.66665 5.25225 4.01432 4.63338 4.63319C4.01451 5.25206 3.66683 6.09143 3.66683 6.96665V15.0333C3.66683 16.8575 5.14266 18.3333 6.96683 18.3333H15.0335C15.9087 18.3333 16.7481 17.9856 17.3669 17.3668C17.9858 16.7479 18.3335 15.9085 18.3335 15.0333V6.96665C18.3335 5.14248 16.8577 3.66665 15.0335 3.66665H6.96683ZM15.8127 5.04165C16.1166 5.04165 16.408 5.16237 16.6229 5.37725C16.8378 5.59214 16.9585 5.88359 16.9585 6.18748C16.9585 6.49137 16.8378 6.78282 16.6229 6.99771C16.408 7.21259 16.1166 7.33331 15.8127 7.33331C15.5088 7.33331 15.2173 7.21259 15.0024 6.99771C14.7875 6.78282 14.6668 6.49137 14.6668 6.18748C14.6668 5.88359 14.7875 5.59214 15.0024 5.37725C15.2173 5.16237 15.5088 5.04165 15.8127 5.04165ZM11.0002 6.41665C12.2157 6.41665 13.3815 6.89953 14.2411 7.75907C15.1006 8.61862 15.5835 9.7844 15.5835 11C15.5835 12.2156 15.1006 13.3813 14.2411 14.2409C13.3815 15.1004 12.2157 15.5833 11.0002 15.5833C9.78459 15.5833 8.6188 15.1004 7.75926 14.2409C6.89971 13.3813 6.41683 12.2156 6.41683 11C6.41683 9.7844 6.89971 8.61862 7.75926 7.75907C8.6188 6.89953 9.78459 6.41665 11.0002 6.41665ZM11.0002 8.24998C10.2708 8.24998 9.57134 8.53971 9.05562 9.05544C8.53989 9.57116 8.25016 10.2706 8.25016 11C8.25016 11.7293 8.53989 12.4288 9.05562 12.9445C9.57134 13.4602 10.2708 13.75 11.0002 13.75C11.7295 13.75 12.429 13.4602 12.9447 12.9445C13.4604 12.4288 13.7502 11.7293 13.7502 11C13.7502 10.2706 13.4604 9.57116 12.9447 9.05544C12.429 8.53971 11.7295 8.24998 11.0002 8.24998Z" />

                                </g>

                            </g>

                            <defs>

                                <filter id="filter0_d_284_513" x="-2.1665" y="-1.16669" width="26.3335" height="26.3333" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">

                                    <feFlood flood-opacity="0" result="BackgroundImageFix" />

                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />

                                    <feOffset dy="1" />

                                    <feGaussianBlur stdDeviation="2" />

                                    <feComposite in2="hardAlpha" operator="out" />

                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />

                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_284_513" />

                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_284_513" result="shape" />

                                </filter>

                                <clipPath id="clip0_284_513">

                                    <rect width="22" height="22" fill="#fff" />

                                </clipPath>

                            </defs>

                        </svg>

                    </a>

                    <a href="{{ Hyvikk::frontend('instagram') }}"><i class="fab fa-instagram d-flex d-sm-flex d-md-flex d-lg-none d-xl-none" style="font-size:24px"></i></a>

                </div>



            </div>

        </div>

    </div>

</nav>

@endif

