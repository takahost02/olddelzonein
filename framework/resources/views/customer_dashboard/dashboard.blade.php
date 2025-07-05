@extends('customer_dashboard.layouts.app')


@section('title')
    <title>@lang('frontend.Dashboard') | {{ Hyvikk::get('app_name') }}</title>
@endsection

@section('css')

<link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/dashboard.css') }}">



@endsection



@section('breadcrumb')

      

    <li class="breadcrumb-item text-sm text-dark active" ><a href="{{url('/customer')}}"aria-current="page">@lang('frontend.Dashboard')</a></li>

   

@endsection

@section('contents')


<div class="custom-alert-msg" style="color:white;"></div>

<div class="row">

    <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5">

        <div class="row">

            <div class=" col-sm-6 mb-xl-0 mb-4">

                <div class="card shadow-sm res-card" style="position: relative;">

                    <div class="card-body p-3">

                        <div class="row">

                            <div class="col-4 text-end">

                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/icon-1.svg') }}">

                                </div>

                            </div>

                            <div class="col-8">

                                <div class="numbers">

                                    <p class="chart-label mb-0 text-capitalize font-weight-bold">@lang('frontend.total_bookings')</p>

                                    <h5 class="font-weight-bolder mb-0">

                                        {{($all??0)}}

                                    </h5>

                                </div>

                            </div>

                            <div class="col-12 p-0">

                                <div class="chart-container pt-3">

                                    <canvas id="line-chart-1" class="chart-canvas" width="350" height="130"></canvas>

                                </div>

                            </div>

                         

                        </div>

                    </div>

                </div>



            </div>

            <div class="col-sm-6 mb-xl-0 mb-4">

                <div class="card shadow-sm res-card">

                    <div class="card-body p-3">

                        <div class="row">

                            <div class="col-4 text-end">

                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/icon-2.svg') }}">

                                </div>

                            </div>

                            <div class="col-8">

                                <div class="numbers">

                                    <p class="chart-label mb-0 text-capitalize font-weight-bold">@lang('frontend.Cancelled')</p>

                                    <h5 class="font-weight-bolder mb-0">

                                        {{$cancel_booking??0}}

                                    </h5>

                                </div>

                            </div>

                            <div class="col-12 p-0">

                                <div class="chart-container pt-3">

                                    <canvas id="line-chart-2" class="chart-canvas"></canvas>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-sm-6  mb-4 mb-sm-4 mb-md-4 mb-lg-0 mb-xl-0 mt-0 mt-sm-0 mt-md-0 mt-lg-3 mt-xl-3">

                <div class="card shadow-sm res-card">

                    <div class="card-body p-3">

                        <div class="row">

                            <div class="col-4 text-end">

                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/icon-3.svg') }}">

                                </div>

                            </div>

                            <div class="col-8">

                                <div class="numbers">

                                    <p class="chart-label mb-0 text-capitalize font-weight-bold">@lang('frontend.Completed')</p>

                                    <h5 class="font-weight-bolder mb-0">

                                        {{$complete_booking??0}}

                                    </h5>

                                </div>

                            </div>

                            <div class="col-12 p-0">

                                <div class="chart-container pt-3">

                                    <canvas id="line-chart-3" class="chart-canvas" width="350" height="130"></canvas>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-sm-6 mb-4 mb-sm-4 mb-md-4 mb-lg-0 mb-xl-0 mt-0 mt-sm-0 mt-md-0 mt-lg-3 mt-xl-3">

                <div class="card shadow-sm res-card">

                    <div class="card-body p-3">

                        <div class="row">

                            <div class="col-4 text-end">

                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/icon-4.svg') }}">

                                </div>

                            </div>

                            <div class="col-8">

                                <div class="numbers">

                                    <p class="chart-label mb-0 text-capitalize font-weight-bold">@lang('frontend.Pending')</p>

                                    <h5 class="font-weight-bolder mb-0">

                                        {{($pending_booking??0)}}

                                    </h5>

                                </div>

                            </div>

                            <div class="col-12 p-0">

                                <div class="chart-container pt-3">

                                    <canvas id="line-chart-4" class="chart-canvas" width="350" height="130"></canvas>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-7">

        <div class="row " style="width:auto;height:100%">

            <div class="col-12">

                <div class="card shadow-sm z-index-2 p-3" style="width:auto;height:100%;">

                    <div class="card-header p-0">

                        <h6>@lang('frontend.Booking_Expense_Summary')</h6>

                        <p class="text-sm">

                            in {{ date('Y') }}

                        </p>

                    </div>

                    <div class="card-body p-0 pt-4">

                        <div class="chart">

                            <canvas id="chart-line" class="chart-canvas"></canvas>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<div class="row mt-4">

    <div class="col-lg-6 mb-lg-0 mb-4">



        <div class="card mb-3 p-3 booking-stat shadow-sm">

            <div class="card-header p-0">

                <div class="row">

                    <div class="col-9">

                        <h6>@lang('frontend.Booking_Status')</h6>

                    </div>

                </div>

            </div>

            <div class="card-body p-0 pt-4">

                <div class="container p-0">

                    <div class="row">

                        @if($complete_booking != 0 || $complete_booking != 0 ||$pending_booking != 0 || $ongoing_booking != 0) 



                        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">

                            <div class="chart">

                                <canvas id="doughnut-chart" class="chart-canvas" height="300" width="100%"></canvas>

                            </div>

                        </div>

                        <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 pt-4 pt-sm-4 pt-md-0 pt-lg-0 pt-xl-0 ps-5 ps-sm-5 ps-md-0 ps-lg-0 ps-xl-0">

                            <div class="checked-stats">

                                <div class="form-check form-check-input-1">

                                    <input class=" form-check-input" type="checkbox" value="" id="completed" onclick="toggleSegmentVisibility('Completed')">

                                    <label class="form-check-label" for="completed">

                                        @lang('frontend.Completed') 

                                    </label>

                                </div>

                                <div class="form-check form-check-input-2">

                                    <input class=" form-check-input" type="checkbox" value="" id="transit"  onclick="toggleSegmentVisibility('In-Transit')">

                                    <label class="form-check-label" for="transit">

                                        @lang('frontend.In_Transit')  

                                    </label>

                                </div>

                                <div class="form-check form-check-input-3">

                                    <input class="form-check-input" type="checkbox" value="" id="cancelled" onclick="toggleSegmentVisibility('Cancelled')">

                                    <label class="form-check-label" for="cancelled">

                                        @lang('frontend.Cancelled')  

                                    </label>

                                </div>

                                <div class="form-check form-check-input-4">

                                    <input class="form-check-input" type="checkbox" value="" id=" pending" onclick="toggleSegmentVisibility('Pending')">

                                    <label class="form-check-label" for=" pending">

                                        @lang('frontend.Pending')   

                                    </label>

                                </div>

                               



                                

                            </div>

                        </div>



                        @else

                        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" style="text-align: end;

                            margin-top: 15%;">

                            <h5>@lang('frontend.No_Bookings')</h5>

                        </div>

                        @endif



                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card z-index-2 p-3 booking-stat shadow-sm">



            <div class="card-body p-0">

                <div class="col-12 mb-1">

                    <div class="row">

                        <div class="col-6">

                            <div class="place-distance-speed-img">

                                <div class="minimal-feature">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/places.svg') }}">

                                </div>

                              

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="place-distance-speed-content">

                                <div class="place-details">

                                    <p class="content-title">@lang('frontend.Places')</p>

                                    <p class="content-detail mb-0 show-places">0</p>

                                </div>

                            </div>

                        </div>

                    </div>



                </div>

                <hr class="horizontal dark mt-4 mb-4 ">

                <div class="col-12 ">

                    <div class="row">

                        <div class="col-6">

                            <div class="place-distance-speed-img">

                                <div class="minimal-feature">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/distance.svg') }}">

                                </div>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="place-distance-speed-content">

                                <div class="place-details">

                                    <p class="content-title ">@lang('frontend.Distance')</p>

                                    <p class="content-detail mb-0 show-distance">0 Kms</p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <hr class="horizontal dark mt-4 mb-4">

                <div class="col-12 ">

                    <div class="row">

                        <div class="col-6">

                            <div class="place-distance-speed-img">

                                <div class="minimal-feature">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/minutes.svg') }}">

                                </div>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="place-distance-speed-content">

                                <div class="place-details">

                                    <p class="content-title ">@lang('frontend.Minutes')</p>

                                    <p class="content-detail mb-0 show-minutes">0</p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="row my-4 mt-5">

    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6   pe-lg-4 pe-xl-4">

        <div class="row">

            <div class="col-md-12 ">

                <h6>@lang('frontend.Ongoing_Bookings')</h6>

                <span class="error_booking_id text-danger"></span>

              

                <div class="row">

                    <div class="col-12 d-flex">

                       

                        <div class="form-group " style="width:100%">

                            

                            <div class="input-group">

                                <span class="input-group-text" id="basic-addon1">

                                    <img src="{{ asset('assets/customer_dashboard/assets/img/svg/icon _Search_.svg') }}">

                                </span>

                                <input type="text" class="form-control ongoing-id" placeholder="Search Booking ID.." aria-label="booking_id" aria-describedby="basic-addon1" onfocus="focused(this)" onfocusout="defocused(this)">

                            </div>

                           



                        </div>

                        <button class="btn btn-icon btn-2 custom-btn-dark ongoing-click" style="" type="button">

                            <span class="btn-inner--icon"><img src="{{ asset('assets/customer_dashboard/assets/img/svg/icon _Horizontal Sliders_.svg') }}"></span>

                        </button>

                    </div>

                    

                    <div class="showinfo">



                    </div>



                </div>

            </div>

        </div>

    </div>



    <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-md-0 mb-4 sec-bordered  ps-lg-4 ps-xl-4">

        <div class="row">

            <div class="col-md-12">

                <div class="row show-single-booking">

                   

                </div>

            </div>

        </div>



    </div>



</div>



@endsection



@section('script')

<script src="https://maps.googleapis.com/maps/api/js?key={{ Hyvikk::api('api_key')}}&libraries=places&callback=initMap"

async defer></script>




<script>

  $(document).ready(function() {

    $('.tooltip-btn').hover(function() {

     

        if (!$(this).find('.my-tooltip').length) {

            const tooltipText = $(this).attr('data-tooltip');

            $(this).append(`<div class="my-tooltip">${tooltipText}</div>`);

        }

  

        $(this).find('.my-tooltip').addClass('show');

    }, function() {

     

        $(this).find('.my-tooltip').removeClass('show');

    });

});











$(document).ready(function(){

    $(".ongoing-click").on("click", function(){

        var booking_id = $(".ongoing-id").val();

        

        if (booking_id === "" || isNaN(booking_id)) {



            var message=`<div class="alert alert-danger" role="alert">

                      <strong style="color:white;">Please enter a valid numeric booking ID</strong>

                     </div>`;



            $(".error_booking_id").html(message);



            setTimeout(function() {

                            $(".error_booking_id").html('');

                }, 3000);

        } else {

            $(".error_booking_id").html('');

            $.ajax({

                url: "{{route('dashboard.single_ongoing_booking')}}", 

                type: "get",

                data: {

                    "booking_id": booking_id

                },

                success: function(data) {

                    $(".showinfo").html(data);

                }

            });

        }

    });

});



var map;

var marker;



function initMap() {

    $(document).ready(function() {

        var mapDivs = document.querySelectorAll('.map');



        

        var startIcon = "{{ asset('assets/images/pickup.png') }}";

        var endIcon = "{{ asset('assets/images/dropup.png') }}";

        var currentIcon = "{{ asset('assets/images/current.png') }}";



       

        mapDivs.forEach(function(mapDiv) {

            // Initialize the map

            map = new google.maps.Map(mapDiv, {

                zoom: 10,

                center: { lat: 0, lng: 0 }

            });



            var startAddress = $("#pickup-address").text();

            var endAddress = $("#dropoff-address").text();



           

            var geocoder = new google.maps.Geocoder();



            geocodeAddress(geocoder, map, startAddress, endAddress, startIcon, endIcon);



            

            marker = null;



            current_location();



            setInterval(current_location, 30000); 

        });

    });

}



function current_location() {

    $.ajax({

        url: "{{url('current-location')}}",

        type: "GET",

        data: { "driver_id": $("#driver-id").text() },

        success: function(data) {

            if (data.latitude && data.longitude) {

                var driverLatLng = new google.maps.LatLng(data.latitude, data.longitude);

                if (marker) {

                    marker.setPosition(driverLatLng);

                } else {

                    marker = new google.maps.Marker({

                        map: map,

                        position: driverLatLng,

                        title: "Driver's Current Location",

                        icon: "{{ asset('assets/images/current.png') }}" 

                    });

                }

                map.setCenter(driverLatLng);



                $(".current-location").text(data.current_location || '-');

                $(".current_speed").text(data.speed || '-');

            } else {

                console.error('Driver location not available');

                $(".current-location").text('-');

                $(".current_speed").text('-');

            }

        },

        error: function() {

            console.error('Failed to retrieve driver location');

            $(".current-location").text('-');

            $(".current_speed").text('-');

        }

    });

}



function geocodeAddress(geocoder, map, startAddress, endAddress, startIcon, endIcon) {

    geocoder.geocode({ 'address': startAddress }, function(resultsStart, status) {

        if (status === 'OK') {

            var startLocation = resultsStart[0].geometry.location;

            geocoder.geocode({ 'address': endAddress }, function(resultsEnd, status) {

                if (status === 'OK') {

                    var endLocation = resultsEnd[0].geometry.location;



                    

                    new google.maps.Marker({

                        position: startLocation,

                        map: map,

                        title: 'Start',

                        icon: startIcon

                    });



                

                    new google.maps.Marker({

                        position: endLocation,

                        map: map,

                        title: 'End',

                        icon: endIcon 

                    });



                   

                    displayRoute(map, startLocation, endLocation);

                } else {

                    console.error('Geocode was not successful for end address: ' + status);

                }

            });

        } else {

            console.error('Geocode was not successful for start address: ' + status);

        }

    });

}





function displayRoute(map, startLocation, endLocation) {

    var directionsService = new google.maps.DirectionsService();

    var directionsRenderer = new google.maps.DirectionsRenderer({

        map: map,

        suppressMarkers: true 

    });

    var request = {

        origin: startLocation,

        destination: endLocation,

        travelMode: 'DRIVING'

    };



    directionsService.route(request, function(response, status) {

        if (status === 'OK') {

            directionsRenderer.setDirections(response);

        } else {

            console.error('Directions request failed due to ' + status);

        }

    });

}







$(document).on("click", '[data-action="classfound"]', function (e) {







$('[data-action="classfound"]').find('img').attr("src", "{{url('/assets/customer_dashboard/assets/img/svg/dropdown_dots.svg')}}");

$('[data-action="classfound"]').parent().find('.dark-timeline').removeClass('dark-timeline').addClass('white-timeline');

$('[data-action="classfound"]').removeClass('blue-timeline').addClass('light-timeline');



$(this).removeClass('light-timeline');

$(this).parent().find('.white-timeline').removeClass('white-timeline').addClass('dark-timeline');

$(this).find(".img-set").find('img').attr("src", "{{url('/assets/customer_dashboard/assets/img/svg/dropdown_dots_light.svg')}}");

$(this).addClass('blue-timeline');







$.ajax({

    url:"{{route('dashboard.single_booking_info')}}",

    type:"get",

    data:{

        "id":$(this).data('id')

    },

    success:function(data)

    {

        initMap();

        $(".show-single-booking").html(data);

    }

});



});





document.addEventListener("DOMContentLoaded", function() {



      $.ajax({

        url:"{{route('dashboard.showinfo')}}",

        type:"get",

        success:function(data)

        {

          $(".showinfo").html(data);



           

          var $element = $('body [data-action="classfound"]:first');



            if ($element.length > 0) {

                $element.find('img').attr("src", "{{url('/assets/customer_dashboard/assets/img/svg/dropdown_dots.svg')}}");

                $element.parent().find('.dark-timeline').removeClass('dark-timeline').addClass('white-timeline');

                $element.removeClass('blue-timeline').addClass('light-timeline');



                $element.each(function() {

                    $(this).removeClass('light-timeline');

                    $(this).parent().find('.white-timeline').removeClass('white-timeline').addClass('dark-timeline');

                    $(this).find(".img-set").find('img').attr("src", "{{url('/assets/customer_dashboard/assets/img/svg/dropdown_dots_light.svg')}}");

                    $(this).addClass('blue-timeline');



                    $.ajax({

                        url: "{{route('dashboard.single_booking_info')}}",

                        type: "get",

                        data: {

                            "id": $(this).data('id')

                        },

                        success: function(data) {

                            initMap();

                            $(".show-single-booking").html(data);

                        },

                        error: function(xhr, status, error) {

                            console.error(error);

                        }

                    });

                });

            } else {

                console.log("No element found matching the selector.");

            }



        }



    });



    $.ajax({

        url:"{{route('dashboard.getinfo')}}",

        type:"get",

        success:function(data)

        {

            $(".show-places").text(data['places']);

            $(".show-distance").text(data['kms'] + " Kms");

            $(".show-minutes").text(data['minutes']);

        }



    });





    var ctx1 = document.getElementById("line-chart-1").getContext("2d");

    var months1 = @json($monthsall);

    var counts1 = @json($countsall);



    var pointRadii1 = counts1.map(count => count > 0 ? 5 : 0);





    new Chart(ctx1, {

        type: "line"

        , data: {

            labels: months1

            , datasets: [{



                }

                , {



                }

                , {

                    label: "Direct"

                    , tension: 0.49

                    , borderWidth: 3

                    , borderCapStyle: 'butt'

                    , pointRadius: pointRadii1

                    , pointHoverRadius:pointRadii1

                    , pointBorderColor: "#fff"

                    , pointHoverBorderColor: "#fff"

                    , pointBorderWidth: 2

                    , pointBackgroundColor: "#344767"

                    , borderColor: "#344767"

                    , borderWidth: 3,

                    

                    data: counts1

                    , maxBarThickness: 6

                }

            , ]

        , }

        , options: {

        

            responsive: true

            , maintainAspectRatio: false

            , plugins: {

                legend: {

                    display: false

                , }

            }

            , interaction: {

                intersect: false

                , mode: 'index'

            , }

            , scales: {

                y: {

                    beginAtZero: true,

                    display: false, 

                    title: {

                        display: false, 

                        text: 'Y-Axis Label',

                    }

                    , ticks: {

                        display: true

                    , }

                    , grid: {

                        drawBorder: false, 

                    }

                , }

                , x: {

                    display: false, 

                    title: {

                        display: false, 

                        text: 'X-Axis Label', 

                    }

                    , ticks: {

                        display: false

                    , }

                    , grid: {

                        drawBorder: false, 

                    }

                , }

            , }

        }

    , });













    var ctx2 = document.getElementById("line-chart-2").getContext("2d");

        var months2 = @json($monthscancel);

        var counts2 = @json($countscancel);



        var pointRadii2 = counts2.map(count => count > 0 ? 5 : 0); 



        new Chart(ctx2, {

            type: "line",

            data: {

                

                labels: months2,

                datasets: [{

                    label: "Direct",

                    tension: 0.4,

                    borderWidth: 3,

                    borderCapStyle: 'butt',

                    pointRadius: pointRadii2,

                    pointHoverRadius: pointRadii2,

                    pointBorderColor: "#fff",

                    pointHoverBorderColor: "#fff",

                    pointBorderWidth: 2,

                    pointBackgroundColor: "#37EA3E",

                    borderColor: "#37EA3E",

                    data: counts2,

                    maxBarThickness: 6

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false

                    }

                },

                interaction: {

                    intersect: false,

                    mode: 'index'

                },

                scales: {

                    y: {

                        

                        beginAtZero: true,

                        display: false,

                        title: {

                            display: false,

                            text: 'Y-Axis Label'

                        },

                        grid: {

                            drawBorder: false

                        }

                    },

                    x: {

                        

                        display: false,

                        title: {

                            display: false,

                            text: 'X-Axis Label'

                        },

                        grid: {

                            drawBorder: false

                        }

                    }

                },



            }

        });





    // -------------------------------****************************--------------------------------------





    var ctx3 = document.getElementById("line-chart-3").getContext("2d");



    var months3 = @json($monthscomplete);

    var counts3 = @json($countscomplete);



    

    var pointRadii3 = counts3.map(count => count > 0 ? 5 : 0);



    new Chart(ctx3, {

        type: "line"

        , data: {

            labels: months3

            , datasets: [{

                 

                }

                , {

                    

                }

                , {



                    label: "Direct"

                    , tension: 0.4

                    , borderWidth: 3

                    , borderCapStyle: 'butt'

                    , pointRadius: pointRadii3

                    , pointHoverRadius: pointRadii3

                    , pointBorderColor: "#fff"

                    , pointHoverBorderColor: "#fff"

                    , pointBorderWidth: 2

                    , pointBackgroundColor: "#2152FF"

                    , borderColor: "#2152FF"

                    , borderWidth: 3,

                    

                    data: counts3

                    , maxBarThickness: 6

                }

            , ]

        , }

        , options: {

            responsive: true

            , maintainAspectRatio: false

            , plugins: {

                legend: {

                    display: false

                , }

            }

            , interaction: {

                intersect: false

                , mode: 'index'

            , }

            , scales: {

                y: {

                    beginAtZero: true, 

                    display: false,

                    title: {

                        display: false,

                        text: 'Y-Axis Label', 

                    }

                    , ticks: {

                        display: true

                    , }

                    , grid: {

                        drawBorder: false, 

                    }

                , }

                , x: {

                    display: false, 

                    title: {

                        display: false,

                        text: 'X-Axis Label',

                    }

                    , ticks: {

                        display: false

                    , }

                    , grid: {

                        drawBorder: false, 

                    }

                , }

            , }

        }

    , });







    // -------------------------------****************************--------------------------------------



    var ctx4 = document.getElementById("line-chart-4").getContext("2d");

    var months4 = @json($monthspending);

    var counts4 = @json($countspending);

    var pointRadii4 = counts4.map(count => count > 0 ? 5 : 0);

    new Chart(ctx4, {

        type: "line"

        , data: {

            labels: months4

            , datasets: [{

                    

                }

                , {

                 

                }

                , {



                    label: "Direct"

                    , tension: 0.4

                    , borderWidth: 3

                    , borderCapStyle: 'butt'

                    , pointRadius: pointRadii4

                    , pointHoverRadius: pointRadii4

                    , pointBorderColor: "#fff"

                    , pointHoverBorderColor: "#fff"

                    , pointBorderWidth: 2

                    , pointBackgroundColor: "#FAC234"

                    , borderColor: "#FAC234"

                    , borderWidth: 3,

                    

                    data: counts4

                    , maxBarThickness: 6

                }

            , ]

        , }

        , options: {

            responsive: true

            , maintainAspectRatio: false

            , plugins: {

                legend: {

                    display: false

                , }

            }

            , interaction: {

                intersect: false

                , mode: 'index'

            , }

            , scales: {

                y: {

                    beginAtZero: true, 

                    display: false, 

                    title: {

                        display: false, 

                        text: 'Y-Axis Label', 

                    }

                    , ticks: {

                        display: true

                    , }

                    , grid: {

                        drawBorder: false, 

                    }

                , }

                , x: {

                    display: false, 

                    title: {

                        display: false, 

                        text: 'X-Axis Label', 

                    }

                    , ticks: {

                        display: false

                    , }

                    , grid: {

                        drawBorder: false, 

                    }

                , }

            , }

        }

    , });













    var ctx5 = document.getElementById("chart-line").getContext("2d");

        var months5 = @json($monthsexpense);

        var counts5 = @json($countsexpense);



        new Chart(ctx5, {

            type: "line",

            data: {

                labels: months5,

                datasets: [{

                    label: "Expense", 

                    tension: 0.4, 

                    borderWidth: 0, 

                    pointRadius: 0, 

                    borderColor: "#2152FF", 

                    borderWidth: 3, 

                    fill: false,

                    data: counts5,

                    maxBarThickness: 6

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        display: false,

                    }

                },

                interaction: {

                    intersect: false,

                    mode: 'index'

                },

                scales: {

                    y: {

                        grid: {

                            drawBorder: false,

                            display: true,

                            drawOnChartArea: true,

                            drawTicks: false,

                            borderDash: [5, 5],

                            borderColor: "#000",

                            borderDashOffset: 0,

                            color: (context) => context.tick.value === 0 ? "#b2b9bf" : 'transparent', // Color for the zero line

                        },

                        ticks: {

                            stepSize: 1000,

                            callback: function(value, index, values) {

                                return value; 

                            },

                            color: '#b2b9bf',

                            padding: 20,

                            font: {

                                size: 11,

                                family: "Open Sans",

                                style: 'normal',

                                lineHeight: 2

                            }

                        }

                    },

                    x: {

                        grid: {

                            drawBorder: false,

                            display: false,

                            drawOnChartArea: false,

                            drawTicks: false,

                            borderDash: [0],

                            borderColor: "#000",

                            borderDashOffset: 0,

                        },

                        ticks: {

                            display: true,

                            color: '#b2b9bf',

                            padding: 20,

                            font: {

                                size: 11,

                                family: "Open Sans",

                                style: 'normal',

                                lineHeight: 2

                            }

                        }

                    }

                }

            }

        });



 });







 var ctx33 = document.getElementById("doughnut-chart").getContext("2d");

    // let cutoutValue = 130;

    var myDoughnutChart;



    var complete_count = "{{ $complete_booking }}";

    var intransit_count = "{{ $ongoing_booking }}";

    var cancel_count = "{{ $cancel_booking }}";

    var pending_count = "{{ $pending_booking }}";



   myDoughnutChart = new Chart(ctx33, {

        type: "doughnut"

        , data: {

            labels: ['Completed', 'In-Transit', 'Cancelled', 'Pending']

            , datasets: [{

                label: "Projects"

                , weight: 9

                , cutout: 120

                , tension: 0.9

                , pointRadius: 2

                , borderWidth: 2

                , backgroundColor: ['#2152FF', '#37EA3E', '#A8B8D8', '#FAC234']

                , data: [complete_count, intransit_count, cancel_count, pending_count]

                , fill: false

            }]

        , }

        , options: {

            responsive: true

            , maintainAspectRatio: false

            , plugins: {

                legend: {

                    display: false

                , }

            }

            , interaction: {

                intersect: false

                , mode: 'index'

            , }

            , scales: {

                y: {

                    grid: {

                        drawBorder: false

                        , display: false

                        , drawOnChartArea: false

                        , drawTicks: false

                    , }

                    , ticks: {

                        display: false

                    }

                }

                , x: {

                    grid: {

                        drawBorder: false

                        , display: false

                        , drawOnChartArea: false

                        , drawTicks: false

                    , }

                    , ticks: {

                        display: false

                    , }

                }

            , }

        , },



    });



    var visibilityFlags = {

        'Completed': true,

        'In-Transit': true,

        'Cancelled': true,

        'Pending':true

    };



function toggleSegmentVisibility(label) {

           

        visibilityFlags[label] = !visibilityFlags[label];



        var newData = visibilityFlags['Completed'] ? [complete_count] : [0];

        newData.push(visibilityFlags['In-Transit'] ? intransit_count : 0);

        newData.push(visibilityFlags['Cancelled'] ? cancel_count : 0);

        newData.push(visibilityFlags['Pending'] ? pending_count : 0);



        

        myDoughnutChart.data.datasets[0].data = newData;

        myDoughnutChart.update();



        

    }



    

 











var canvas = document.getElementById("line-chart-1");



canvas.style.width = "300px";

canvas.style.height = "80px";



canvas.setAttribute("width", 350);

canvas.setAttribute("height", 130);





var canvas = document.getElementById("line-chart-2");



canvas.style.width = "300px";

canvas.style.height = "80px";





canvas.setAttribute("width", 350);

canvas.setAttribute("height", 130);





var canvas = document.getElementById("line-chart-3");





canvas.style.width = "300px";

canvas.style.height = "80px";





canvas.setAttribute("width", 350);

canvas.setAttribute("height", 130);





var canvas = document.getElementById("line-chart-4");





canvas.style.width = "300px";

canvas.style.height = "80px";





canvas.setAttribute("width", 350);

canvas.setAttribute("height", 130);





</script>



@endsection