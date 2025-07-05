@extends('customer_dashboard.layouts.app')


@section('title')

    <title>@lang('frontend.login') | {{ Hyvikk::get('app_name') }}</title>

@endsection


@section('content')

    <section class="position-relative">

        <div class="page-header">

            <div class="container">

                <div class="msg-login custom-alerts"></div>

                <div class="row">

                    <div class="col-xl-4 col-lg-5 col-md-12 d-flex flex-column">

                        <div

                            class="card card-plain mt-7 mt-sm-7 mt-md-7 mt-lg-6 mt-xl-6 ps-0 ps-sm-0 ps-md-0 ps-lg-5 ps-xl-5">

                            <div class="card-header pb-0 text-left bg-transparent">

                                <p class="font-weight-bolder login_title">@lang('frontend.Welcome')</p>

                                <p class="mb-0 login-content">@lang('frontend.Access_Customer')</p>

                            </div>

                            <div class="card-body">

                                @if (session('success'))

                                    <div  id="successAlert" class="alert alert-success xs-mt" style="margin-bottom: 35px !important;">

                                        {{ session('success') }}

                                    </div>

                                @endif

                                @if (count($errors->login) > 0)

                                    <div id="errorAlert"  class="alert alert-danger p-1">

                                        <ul class="mb-0">

                                            @foreach ($errors->login->all() as $error)

                                                <li style="color:#fff">{{ $error }}</li>

                                            @endforeach

                                        </ul>

                                    </div>

                                @endif

                                <form role="form" method="POST">

                                    {!! csrf_field() !!}

                                    <label class="form_label">@lang('frontend.Email_Id')</label>

                                    <div class="mb-3">

                                        <input type="email" class="form-control user-email" name="email"

                                            value="{{ old('email') }}" placeholder="Enter your Email Address"

                                            aria-label="Email" aria-describedby="email-addon">

                                            <span class="focus-bg error-email1"></span>

                                    </div>

                                    <label class="form_label">@lang('frontend.Password')</label>

                                    <div class="mb-0">

                                        <input type="password" class="form-control user-pass" id="password" name="password"

                                            placeholder="Enter your Password" aria-label="Password"

                                            aria-describedby="password-addon">

                                            <span class="focus-bg error-password1"></span>

                                    </div>

                                    <div class="forgot_pass_link mt-2">

                                        <a href="{{ url('forgot-password') }}" class="me-2">@lang('frontend.Forgot_Password')</a>



                                    </div>

                                    <div class="text-center">

                                        <button type="button" class="btn w-100 mt-3 mb-0 login_btn pt-2 pb-2 login-user">



                                            <div class="spinner-border text-light hide-1 d-none"

                                            role="status">

                                            <span class="sr-only"></span>

                                            </div>

                                            <div class="hide-2">

                                                @lang('frontend.login')

                                            </div>

                                        </button>

                                    </div>

                                </form>

                                <div

                                    class="pt-4 px-lg-2 px-1 d-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start">

                                    <p class="mb-4 text-sm mx-auto">
                                        @lang('frontend.do_have')
                    

                                        <a href="{{ route('sign_up') }}" class="font-weight-bolder"

                                            style="color:rgba(33, 82, 255, 1);">@lang('frontend.sign_up')</a>

                                    </p>

                                </div>

                            </div>



                        </div>

                    </div>

                    <div class="col-md-0 col-lg-6 col-xl-6 ">

                        <div class="oblique position-absolute top-0 h-100 d-lg-block d-none me-n8">

                            <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6"

                                style="background-image:url('{{ asset('assets/customer_dashboard/assets/img/svg/pexels-taras-makarenko\ 1.jpg') }}')">

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection



