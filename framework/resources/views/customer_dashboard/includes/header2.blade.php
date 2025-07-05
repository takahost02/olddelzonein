<nav class="navbar navbar-main navbar-expand-lg bg-transparent shadow-none position-absolute px-4 w-100 z-index-2 pt-3 pt-sm-4 pt-md-4 pt-lg-4 pt-xl-4">

    <div class="container-fluid py-1 px-3 px-sm-3 px-md-3 px-lg-3 px-xl-3  ">

        <nav aria-label="breadcrumb">

        

            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 ps-2 me-sm-6 me-0 me-sm-0 me-md-5 me-lg-5 me-xl-5">



                <li class="breadcrumb-item text-sm"><a href="{{url('/dashboard')}}" class="opacity-5 text-dark"> Pages </a></li>



                @yield('breadcrumb')

            </ol>

            <h6 class="font-weight-bolder ms-2">

                @if(Route::currentRouteName() == 'dashboard')

                @lang('frontend.Dashboard')

                @elseif(Route::currentRouteName() == "user_profile")

                @lang('frontend.user_profile')

                @else

                {{ ucwords(str_replace('_', ' ', Route::currentRouteName())) }}

                @endif

            </h6>

        </nav>

        <div class="collapse navbar-collapse me-md-0 me-sm-4 mt-sm-0 mt-2" id="navbar">

            <div class="ms-sm-auto pe-md-3 d-flex align-items-center">



            </div>

            <ul class="navbar-nav justify-content-end">

                <li class="nav-item d-flex align-items-center">

                    

                    <a class="btn btn-outline-blue btn-sm mb-0 me-3 py-2 px-3" href="#" id="fileUploadButton">

                        <span class="text">@lang('frontend.Change_Photo')</span>

                        <img src="{{ asset('assets/customer_dashboard/assets/img/svg/camera.svg') }}" class="icon ms-0 ms-sm-0 ms-md-2 ms-lg-2 ms-xl-2">

                    </a>

                    <input type="file" id="myFile" name="filename"  style="display: none;" accept="image/*" >



                </li>

          <li class="nav-item d-flex align-items-center">

                        <a href="#" class="nav-link text-body font-weight-bold px-0" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                             <i class="fa fa-sign-out-alt"></i>

                            <span class="d-sm-inline d-none" >@lang('frontend.logout')</span>

                        </a>

                    </li>   

                <li class="nav-item d-xl-none ps-3 pe-0 d-flex align-items-center">

                    <a href="javascript:;" class="nav-link text-white p-0">

                        <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">

                            <div class="sidenav-toggler-inner">

                                <i class="sidenav-toggler-line "></i>

                                <i class="sidenav-toggler-line "></i>

                                <i class="sidenav-toggler-line "></i>

                            </div>

                        </a>

                    </a>

                </li>





            </ul>

        </div>

               <form id="logout-form" action="{{ url('user-logout') }}" method="POST" style="display: none;">

                                                               {{ csrf_field() }}

                                                           </form>

    </div>

</nav>





