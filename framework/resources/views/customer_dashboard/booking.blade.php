@extends('customer_dashboard.layouts.app')
@section('title')
    <title>@lang('frontend.My_Bookings') | {{ Hyvikk::get('app_name') }}</title>
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/booking.css') }}">
@endsection
@section('contents')
@section('breadcrumb')
   <li class="breadcrumb-item text-sm text-dark active" ><a href="{{url('/my-bookings')}}"aria-current="page">@lang('frontend.My_Bookings')</a></li>

    <style>

        .daterangepicker{
            top: 165.6px !important; 
            left: auto !important; 
            right: 25.4499px !important; 
        }

        .daterangepicker .drp-buttons .applyBtn  {
            color: #fff !important;
            background-color: #007bff !important;
            border-color: #007bff !important;
        }

        #loadingOverlay {
    position: fixed;
    top: 0;
    left: 250px;
    width: calc(100% - 250px);
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 0.5;
    background-color: #45454563;
  
}

.loading-overlay-content {
    text-align: center;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.loader {
    border: 5px solid #F3F3F3; 
    border-top: 5px solid #3498DB;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#loadingOverlay.visible {
   display: flex;
}

/* Media query for tablets and smaller devices */
@media (max-width: 768px) {
    #loadingOverlay {
        left: 0;
        width: 100%;
    }

    .loading-overlay-content {
        font-size: 0.9em;
    }

    .loader {
        width: 50px;
        height: 50px;
        border-width: 4px;
    }
}

/* Media query for phones */
@media (max-width: 480px) {
    .loading-overlay-content {
        font-size: 0.8em;
    }

    .loader {
        width: 40px;
        height: 40px;
        border-width: 3px;
    }
}



    </style>

@endsection

<div id="loadingOverlay" class="d-none">
    <div class="loading-overlay-content">
        <div class="loader"></div>
    </div>
  </div>

<div class="row">
    <div class="col-12">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-12 col-sm-7 col-md-8 col-lg-9 col-xl-9 ">
                    <div class="filter_area">
                        <div class="container-fluid px-0">
                            <div class="row">
                                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                    <div class="sel_entries">
                                        <div class="custom-dropdown entry-custom-dropdown justify-content-center justify-content-sm-center  justify-content-md-start justify-content-lg-start justify-content-xl-start" style=" position: relative;">
                                            <select class="form-select form-select-md select-per-page" style="padding-right:30px;">
                                               <option selected value="10">10</option>
                                               <option value="20">20</option>
                                               <option value="50">50</option>
                                               <option value="100">100</option>
                                               <option value="550">550</option>
                                            </select>
                                        </div>
                                        <span class="dropdown_label ms-1 d-none d-sm-none d-md-flex d-lg-flex d-xl-flex">@lang('frontend.Entries_per_Page')</span>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="input-group  d-sm-flex d-md-flex d-lg-flex d-xl-flex" style="border:.5px solid rgba(167, 167, 167, 1);border-radius:5px">
                                        <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true" style="font-size:22px;"></i></span>
                                        <input type="text" class="form-control search-text" placeholder="@lang('frontend.Search_Booking_by_address')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-5 col-md-4 col-lg-3 col-xl-3 mt-3 mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0   d-flex" style="align-items: center;">
                    <div class="container-fluid px-0">
                        <div class="row">
                            <div class="col-6 mb-5">
                                        <select class="form-select form-select-md select-status">
                                            <option selected value="">Select status</option>
                                            <option value="Pending">pending</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Ongoing">in transit</option>
                                            <option value="Completed">completed</option>
                                         </select>
                            </div>
                            <div class="col-6">
                                <div class="dropdown d-flex justify-content-end justify-content-sm-center justify-content-md-center justify-content-lg-center justify-content-xl-center">
                                    <div class="position-relative">
                                        <input type="hidden" class="start-date">
                                        <input type="hidden" class="end-date">
                                
                                        <button class="btn btn-icon btn-3 filter_btn mb-0 dropdown-toggle myClass filterBtn"
                                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="true">
                                            <span class="btn-inner--icon">
                                                <img src="{{ asset('assets/customer_dashboard/assets/img/svg/icon _Horizontal Sliders_.svg') }}">
                                            </span>
                                            <span class="btn-inner--text">@lang('frontend.filters')</span>
                                        </button>
                                
                                        <div class="dropdown-menu top-control card" id="filterOptions"
                                             style="width:100%;max-width:400px;position:absolute;top:40px;right:0px;z-index:99;border-radius:10px">
                                            <input type="hidden" id="daterange_input" name="daterange"
                                                   value="{{ date('d-m-Y') }}" class="form-control" />
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
<div class="row mt-4 show-bookings-info">
</div>
@endsection
@section('script')


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>

$(document).ready(function() {
   $("#dropdownMenuButton1").on("click",()=>{
       $("#daterange_input").click();
   })
});


$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left',
    locale: {
      format: 'DD-MM-YYYY'
    }
  });

  $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
    
    $(".start-date").val(picker.startDate.format('YYYY-MM-DD'));

    $(".end-date").val(picker.endDate.format('YYYY-MM-DD'));


    $.ajax({
        url: "{{ url('show-booking-info') }}?page=" + 1,
        beforeSend: function() {
            $("#loadingOverlay").removeClass('d-none');
        },
        data: {
            'perpage': $(".select-per-page :selected").val(),
            'search_text': $(".search-text").val(),
            'status': $('.select-status :selected').val(),
            'start_date': $(".start-date").val(),
            'end_date': $(".end-date").val()
        },
        success: function(data) {
            $("#loadingOverlay").addClass('d-none');

            $(".show-bookings-info").html(data);
            $(".show-bookings-info").scrollTop(0);
        },
        error: function (xhr, status, errorThrown) {
          $("#loadingOverlay").addClass('d-none');
        }
    });


    
  });
});


$(document).on("click", '[data-action="classfound1"]', function (e) {
    $('[data-action="classfound1"]').find('.booking-light-timeline').removeClass('in-transit');
    $('[data-action="classfound1"]').css("color",""); 
    $('[data-action="classfound1"]').find('.progress-bar').css("background-color",""); 
    $('[data-action="classfound1"]').find('.custom-msg').removeClass('state_label');
    $('[data-action="classfound1"]').find(".drop-img").attr("src", "{{url('/assets/customer_dashboard/assets/img/svg/dropdown_dots.svg')}}");
    $('[data-action="classfound1"]').find(".return-way").attr("src", "{{url('/assets/customer_dashboard/assets/img/return_way.svg')}}");
    $('[data-action="classfound1"]').find(".one-way").attr("src", "{{url('/assets/customer_dashboard/assets/img/one_way.svg')}}");
    $(this).find('.booking-light-timeline').addClass('in-transit');
    $(this).css("color","white");
    $(this).find('.progress-bar').css("background-color","rgba(52, 71, 103, 1) !important");
    $(this).find('.custom-msg').addClass('state_label');
    $(this).find(".drop-img").attr("src", "{{url('/assets/customer_dashboard/assets/img/svg/dropdown_dots_light.svg')}}");
    $(this).find(".return-way").attr("src","{{url('/assets/customer_dashboard/assets/img/return_way.svg')}}");
    $(this).find(".one-way").attr("src","{{url('/assets/customer_dashboard/assets/img/one_way.svg')}}");
});
$.ajax({
    url: "{{url('show-booking-info')}}",
    type: "get",
    
    data: {
        'perpage': $(".select-per-page :selected").val()
    },
    success: function(data) {
        $(".show-bookings-info").html(data);
        $(".show-bookings-info").scrollTop(0);
    }
});
$(document).on('click', '.pagination a', function(event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    fetch_data(page);
});
function fetch_data(page) {
    $.ajax({
        url: "{{ url('show-booking-info') }}?page=" + page,
        beforeSend: function() {
            $("#loadingOverlay").removeClass('d-none');
        },
        data: {
            'perpage': $(".select-per-page :selected").val(),
            'search_text': $(".search-text").val(),
            'status': $('.select-status :selected').val(),
            'start_date': $(".start-date").val(),
            'end_date': $(".end-date").val()
        },
        success: function(data) {
            $("#loadingOverlay").addClass('d-none');
            $(".show-bookings-info").html(data);
        },
        error: function (xhr, status, errorThrown) {
          $("#loadingOverlay").addClass('d-none');
        }
    });
}
$(".select-per-page").on("change", function() {
    $.ajax({
        url: "{{ url('show-booking-info') }}?page=" + 1,
        beforeSend: function() {
            $("#loadingOverlay").removeClass('d-none');
        },
        data: {
            'perpage': $(".select-per-page :selected").val(),
            'search_text': $(".search-text").val(),
            'status': $('.select-status :selected').val(),
            'start_date': $(".start-date").val(),
            'end_date': $(".end-date").val()
        },
        success: function(data) {
            $("#loadingOverlay").addClass('d-none');
            $(".show-bookings-info").html(data);
            $(".show-bookings-info").scrollTop(0); 
        },
        error: function (xhr, status, errorThrown) {
          $("#loadingOverlay").addClass('d-none');
        }
    });
});
$(document).ready(function() {
    $('.search-text').on('keypress', function(event) {
        if (event.which === 13) {
            search_booking();
        }
    });
    $(document).on("input", ".search-text", function() {
        var name = $(this).val();
        clearTimeout($(this).data('timeout'));
        if (name !== '') {
            var timeout = setTimeout(function() {
                search_booking();
            }, 1000);
            $(this).data('timeout', timeout);
        } else {
            window.location.href = 'my-bookings';
        }
    });
    function search_booking() {
        $.ajax({
            url: "{{ url('show-booking-info') }}?page=1",
            beforeSend: function() {
                $("#loadingOverlay").removeClass('d-none');
            },
            data: {
                'perpage': $(".select-per-page :selected").val(),
                'search_text': $(".search-text").val(),
                'status': $('.select-status :selected').val(),
                'start_date': $(".start-date").val(),
                'end_date': $(".end-date").val()
            },
            success: function(data) {
                $("#loadingOverlay").addClass('d-none');
                $(".show-bookings-info").html(data);
                $(".show-bookings-info").scrollTop(0); 
            },
            error: function (xhr, status, errorThrown) {
            $("#loadingOverlay").addClass('d-none');
            }
        });
    }
});
$(".select-status").on("change", function() {
    $.ajax({
        url: "{{ url('show-booking-info') }}?page=" + 1,
        beforeSend: function() {
                $("#loadingOverlay").removeClass('d-none');
            },
        data: {
            'perpage': $(".select-per-page :selected").val(),
            'search_text': $(".search-text").val(),
            'status': $('.select-status :selected').val(),
            'start_date': $(".start-date").val(),
            'end_date': $(".end-date").val()
        },
        success: function(data) {
            $("#loadingOverlay").addClass('d-none');
            $(".show-bookings-info").html(data);
            $(".show-bookings-info").scrollTop(0); // Scroll to top after updating content
        },
        error: function (xhr, status, errorThrown) {
        $("#loadingOverlay").addClass('d-none');
        }
    });
});
// $(".fetch-date").on("click", function() {
//     $.ajax({
//         url: "{{ url('show-booking-info') }}?page=" + 1,
//         data: {
//             'perpage': $(".select-per-page :selected").val(),
//             'search_text': $(".search-text").val(),
//             'status': $('.select-status :selected').val(),
//             'start_date': $(".start-date").val(),
//             'end_date': $(".end-date").val()
//         },
//         success: function(data) {
//             $(".show-bookings-info").html(data);
//             $(".show-bookings-info").scrollTop(0);
//         }
//     });
// });
</script>
@endsection
