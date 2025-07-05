





@extends('customer_dashboard.layouts.app')


@section('title')
    <title>@lang('frontend.Reset_Password') | {{ Hyvikk::get('app_name') }}</title>
@endsection


@section('content')





@if(Route::currentRouteName() == "new_password")



<section class="position-relative">

  <div class="page-header">

      <div class="container">

       

        <div class="row ">

          

          <div class="col-xl-4 col-lg-5 col-md-12 d-flex flex-column">

            <div class="card card-plain  mt-7 mt-sm-7 mt-md-7 mt-lg-6 mt-xl-6 ps-0 ps-sm-0 ps-md-0 ps-lg-5 ps-xl-5">

              <div class="card-header p-0 text-left bg-transparent">

                <p class="font-weight-bolder login_title">@lang('frontend.Reset_Password')</p>

              </div>



              <div class="msg-forget-email custom-alerts" >

                 

              </div>



              

              <div class="card-body px-0">

                <form method="post" id="reset-password-email">

                  

                  <input type="hidden" name="token" value="{{$token}}">



                  <label class="form_label">@lang('frontend.email')</label>

                  <div class="mb-3">

                    <input type="email" name="email" id="email" class="form-control" value="{{ isset($_GET['email']) ? $_GET['email'] : '' }}" placeholder="@lang('frontend.email_placeholder')" aria-label="Email" aria-describedby="email-addon">

                    <span class="focus-bg error-reset-email"></span>

                  </div>



                  <div class="mb-3">

                    <label class="form_label">@lang('frontend.password')</label>

                    <input type="password" name="password" id="email" class="effect-22 form-control" placeholder="@lang('frontend.password')"/>

                    <span class="focus-bg error-reset-password"></span>

                  </div>







                  <div class="mb-3">

                    <label class="form_label">@lang('frontend.confirm_password')</label>

                    <input type="password" name="password_confirmation" id="email" class="effect-22 form-control" placeholder="@lang('frontend.confirm_password')"/>

                    <span class="focus-bg"></span>

                  </div>





                  <div class="text-center">

                    

                    <button type="button"

                          id="submit"

                          class="reset-password-email btn w-100 mt-3 mb-0 login_btn pt-2 pb-2 forget-password-email">

                          



                          <div class="spinner-border text-light hide-9 d-none"

                              role="status">

                              <span class="sr-only"></span>

                          </div>

                          <div class="hide-10">

                              @lang('frontend.reset_your_password')

                          </div>

                      </button>

                  </div>

                </form>           

              </div>           

            </div>

          </div>

          <div class="col-md-0 col-lg-6 col-xl-6 ">

            <div class="oblique position-absolute top-0 h-100 d-lg-block d-none me-n8">

              <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('{{ asset('assets/customer_dashboard/assets/img/svg/pexels-taras-makarenko\ 1.jpg') }}')"></div>

            </div>

          </div>

        </div>

      </div>

  </div>

</section>



@else



<section class="position-relative">

    <div class="page-header">

        <div class="container">

         

          <div class="row ">

            

            <div class="col-xl-4 col-lg-5 col-md-12 d-flex flex-column">

              <div class="card card-plain  mt-7 mt-sm-7 mt-md-7 mt-lg-6 mt-xl-6 ps-0 ps-sm-0 ps-md-0 ps-lg-5 ps-xl-5">

                <div class="card-header p-0 text-left bg-transparent">

                  <p class="font-weight-bolder login_title">@lang('frontend.Reset_Password')</p>

                  <p class="mb-0 login-content">You will Receive an email within  Few Minutes to Reset your Password.</p>

                </div>



                <div class="msg-forget-email custom-alerts" >

                 

                </div>

                <div class="card-body px-0">

                  <form method="post" id="forget-password-email">

                    <label class="form_label">@lang('frontend.email')</label>

                    <div class="mb-3">

                      <input type="email" name="email" class="form-control" placeholder="Enter your Email Address" aria-label="Email" aria-describedby="email-addon">

                      <span

                      class="focus-bg error-forget-email"></span>

                    </div>

                    <div class="text-center">

                      <button type="button" class="btn w-100 mt-3 mb-0 login_btn pt-2 pb-2 forget-password-email">

                        

                        <div class="spinner-border text-light hide-11 d-none"

                        role="status">

                        <span class="sr-only"></span>

                        </div>

                        <div class="hide-12">

                            @lang('frontend.reset_link')

                        </div>



                      </button>

                    </div>

                  </form>           

                </div>           

              </div>

            </div>

            <div class="col-md-0 col-lg-6 col-xl-6 ">

              <div class="oblique position-absolute top-0 h-100 d-lg-block d-none me-n8">

                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('{{ asset('assets/customer_dashboard/assets/img/svg/pexels-taras-makarenko\ 1.jpg') }}')"></div>

              </div>

            </div>

          </div>

        </div>

    </div>

</section>



@endif



@endsection

