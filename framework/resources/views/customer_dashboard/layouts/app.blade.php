<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">

    @yield('title')
    <link rel="icon" href="{{ asset('assets/images/' . Hyvikk::get('icon_img')) }}" type="icon_img">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <script src="https://kit.fontawesome.com/9b713d2ab4.js" crossorigin="anonymous"></script>


    <link id="pagestyle" href="{{ asset('assets/customer_dashboard/assets/css/soft-ui-dashboard.css?v=345435') }} "
        rel="stylesheet" />


    <link href="{{ asset('assets/customer_dashboard/assets/css/style.css') }}?v=1.1234568" rel="stylesheet" />
    @if (!Auth::guest() && Auth::user()->user_type == 'C')
    @else
        <link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/app.css') }}">

    @endif

    <link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/app1.css') }}">


    @yield('css')

</head>

<body class="g-sidenav-show  bg-gray-100">




    @if (request()->is('sign_up'))

        @include('customer_dashboard.includes.header3')
        @yield('content')
    @elseif(!Auth::guest() && Auth::user()->user_type == 'C')
        @include('customer_dashboard.includes.sidebar')

        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
            @if (!request()->is('new_profile') && !request()->is('booking_details') && !request()->is('booking_details_ongoing'))
                @include('customer_dashboard.includes.header')
                <div class="container-fluid py-4" style="position:relative;">
                    @yield('contents')
                    @include('customer_dashboard.includes.footer')
                </div>
            @elseif(request()->is('new_profile'))
                @include('customer_dashboard.includes.header2')
                <div class="container-fluid py-0" style="position:relative;">

                    @yield('contents')

                </div>
                @yield('contented')
            @elseif(request()->is('booking_details'))
                @include('customer_dashboard.includes.header')
                <div class="container-fluid py-0" style="position:relative;">
                    @yield('contents')
                    @include('customer_dashboard.includes.footer')
                </div>
            @elseif(request()->is('booking_details_ongoing'))
                @include('customer_dashboard.includes.header')
                <div class="container-fluid py-0" style="position:relative;">
                    @yield('contents')
                    @include('customer_dashboard.includes.footer')
                </div>
            @endif
        </main>
    @elseif(request()->is('login') || request()->is('forgot-password'))
        <div class="container position-sticky z-index-sticky top-0 p-0">
            <div class="row">
                <div class="col-12">
                    @include('customer_dashboard.includes.header')
                </div>
            </div>
        </div>
        <main class="main-content mt-0">
            @yield('content')
            @include('customer_dashboard.includes.footer')
        </main>
    @else
        <div class="container position-sticky z-index-sticky top-0 p-0">
            <div class="row">
                <div class="col-12">
                    @include('customer_dashboard.includes.header')
                </div>
            </div>
        </div>
        <main class="main-content mt-0">
            @yield('content')
            @include('customer_dashboard.includes.footer')
        </main>


    @endif









    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="{{ asset('assets/customer_dashboard/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/customer_dashboard/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/customer_dashboard/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/customer_dashboard/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/customer_dashboard/assets/js/plugins/chartjs.min.js') }}"></script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('assets/js/all.js') }}"></script>





    <script>
        $(document).ready(function() {

            $("#successAlert").fadeTo(5000, 0).slideUp(500, function() {
                $(this).remove();
            });


            $("#errorAlert").fadeTo(5000, 0).slideUp(500, function() {
                $(this).remove();
            });
        });
    </script>

    @yield('script')


    <script>
        var Home = "{{ url('/') }}";
        var login = "{{ url('user-login') }}";
        var register = "{{ url('user-register') }}";
        var forgot_password = "{{ url('forgot-password') }}";
        var reset_password_email = "{{ url('reset-password-email') }}";
        var reset_password = "{{ url('reset-password') }}";
        var booking_alert = "{{ url('save-booking-alert') }}";
    </script>


    <script>
        function mouseover() {
            document.getElementById("img1").style.display = "none";
            document.getElementById("img2").style.display = "block";
        }

        function mouseout() {
            document.getElementById("img1").style.display = "block";
            document.getElementById("img2").style.display = "none";
        }
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>


    <script>
        $(document).ready(function() {

            var firstDropdownSelected = false;

            $('#stage + .dropdown-menu .dropdown-item').click(function() {
                var selectedValue = $(this).text();
                $('#stage').html(selectedValue +
                    ' <span class="arrow-icon"><img src="assets/customer_dashboard/assets/img/svg/icon _chevron-down_.svg"></span>'
                    );
                firstDropdownSelected = true;
            });

            if (!firstDropdownSelected) {
                $('#stage').html('Pending' +
                    ' <span class="arrow-icon"><img src="assets/customer_dashboard/assets/img/svg/icon _chevron-down_.svg"></span>'
                    );
            }
            var secondDropdownSelected = false;

            $('#entries_page + .dropdown-menu .dropdown-item').click(function() {
                var selectedValue = $(this).text();
                $('#entries_page').html(selectedValue +
                    ' <span class="arrow-icon"><img src="assets/customer_dashboard/assets/img/svg/icon _chevron-down_.svg"></span>'
                    );
                secondDropdownSelected = true;
            });

            if (!secondDropdownSelected) {
                $('#entries_page').html('10' +
                    ' <span class="arrow-icon"><img src="assets/customer_dashboard/assets/img/svg/icon _chevron-down_.svg"></span>'
                    );
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            var $filterBtn = $("#dropdownMenuButton1");
            var $filterOptions = $("#filterOptions");

            $filterBtn.on("click", function(event) {
                event.stopPropagation();
                if ($filterOptions.css("display") === "none") {
                    $filterOptions.css("display", "block");
                } else {
                    $filterOptions.css("display", "none");
                }
            });

            $("body").on("click", function(event) {
                if (!$filterOptions.is(event.target) && $filterOptions.has(event.target).length === 0 &&
                    $filterOptions.css("display") === "block") {
                    $filterOptions.css("display", "none");
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.custom-date-picker input[type="date"]').on('click', function() {

            });
            $('.custom-date-picker input[type="text"]').datepicker({
                dateFormat: 'dd/mm/yy',
                showOn: 'both',
                buttonText: 'Select a date',
                onSelect: function(dateText, inst) {}
            });

        });
    </script>

    <script>
        $(document).ready(function() {

            $("#fileUploadButton").click(function() {
                $("#myFile").click();
            });
            $("#myFile").change(function() {
                var fileName = $(this).val().split('\\').pop();
                $("#fileUploadButton.text").text(fileName);
            });
        });


        // Toggle Sidenav
        const iconNavbarSidenav = document.getElementById('iconNavbarSidenav');
        const iconSidenav = document.getElementById('iconSidenav');
        const sidenav = document.getElementById('sidenav-main');
        let body = document.getElementsByTagName('body')[0];
        let className = 'g-sidenav-pinned';

        if (iconNavbarSidenav) {
            iconNavbarSidenav.addEventListener("click", toggleSidenav);
        }

        if (iconSidenav) {
            iconSidenav.addEventListener("click", toggleSidenav);
        }

        function toggleSidenav() {
            if (body.classList.contains(className)) {
                body.classList.remove(className);
                setTimeout(function() {
                    sidenav.classList.remove('bg-white');
                }, 100);
                sidenav.classList.remove('bg-transparent');

            } else {
                body.classList.add(className);
                sidenav.classList.add('bg-white');
                sidenav.classList.remove('bg-transparent');
                iconSidenav.classList.remove('d-none');
            }
        }
    </script>


    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
