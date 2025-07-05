<!DOCTYPE html>
@php($language = Hyvikk::frontend('language'))
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="manifest" href="{{ asset('manifest.json?v2') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- <title>{{ Hyvikk::get('app_name') }}</title> --}}

    @yield('title')

    <link rel="icon" href="{{ asset('assets/images/' . Hyvikk::get('icon_img')) }}" type="icon_img">

    <link rel="stylesheet" href="{{ asset('assets/css/frontend-slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}?v=1.1435">


    <style>
        .custom-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .custom-btn {
            background-color: #F8F8FA !important;
            color: #130F40 !important;
            border-radius: 36px;
            font-size: 17px !important;
            padding: 10px 31px;
            margin-right: 15px;
        }

        .profile-logout a:hover {
            background-color: #130F40 !important;
            color: #F8F8FA !important;
        }

        .custom-btn:hover {
            background-color: #130F40 !important;
            color: #F8F8FA !important;
        }
    </style>
    @yield('css')
</head>

<body @if ($language == 'Arabic-ar') dir="rtl" @endif>


    <div class="reset-alert-message">

    </div>

    @include('frontend.includes.navigation')


    <section id="content">

        @yield('content')

    </section>
    @include('frontend.includes.footer')




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <script src="{{ asset('assets/js/frontend-slick.min.js') }}"></script>

    <script src="{{ asset('assets/js/frontend-moment.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets/js/frontend-main.js') }}"></script>
    <script src="{{ asset('assets/js/frontend-plugin-select2.full.min.js') }}"></script>
     {{-- <script src="{{ asset('sw.js?v5') }}"></script>
    <script src="{{ asset('web-sw.js?v1') }}"></script>  --}}


    <script src="{{ asset('assets/js/datepicker.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/all.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>



    @yield('script')

    <script>
        var Home = "{{ url('/') }}";
        var login = "{{ url('user-login') }}";
        var register = "{{ url('user-register') }}";
        var edit_profile = "{{ url('edit_profile') }}";
        var forgot_password = "{{ url('forgot-password') }}";
        var reset_password_email = "{{ url('reset-password-email') }}";
        var reset_password = "{{ url('reset-password') }}";
        var book = "{{ url('book') }}";

        var login_url = "{{ url('login') }}";
        var redirect_url = "{{ url('redirect-payment') }}";
    </script>



   
    @yield('scripts')
    @yield('javascript')
    @if (count($errors->register) > 0)
        <script type="text/javascript">
            $('#login-modal').addClass('active');
            $('#password_label').addClass('label-top');
            $('#password_label').addClass('stay');
            $('#password').addClass('active');
        </script>
    @endif
    <script>
        $(function() {
            var email = $('#email').val();
            var password = $('#password').val();
            if ((email != null || email != '') || (password != null || password != '')) {

                $('#password_label').addClass('label-top');
                $('#password_label').addClass('stay');
                $('#password').addClass('active');
                $('#email_label').addClass('label-top');
                $('#email_label').addClass('stay');
                $('#email').addClass('active');
            } else {
                $('#password_label').removeClass('label-top');
                $('#password_label').removeClass('stay');
                $('#password').removeClass('active');
                $('#email_label').removeClass('label-top');
                $('#email_label').removeClass('stay');
                $('#email').removeClass('active');
            }
        });
    </script>



    <script>
        var google_api = "{{ Hyvikk::api('google_api') }}";
    </script>
    <script src="{{ asset('assets/js/fleet-frontend.js?v=1.0.0') }}"></script>
    @if (session('success') || $errors->any())
        <script>
            var scrollToDiv = $("#book_now");
            $('html, body').animate({
                scrollTop: scrollToDiv.offset().top
            }, 1000);
        </script>
    @endif


    @if (request()->is('/'))

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
            <script
                src="https://maps.googleapis.com/maps/api/js?key={{ Hyvikk::api('api_key') }}&libraries=places&callback=initMap"
                async defer></script>
        @endif
    @endif
</body>
<!-- Body End -->

</html>
