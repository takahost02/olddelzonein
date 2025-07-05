@extends('frontend.layouts.app')

@section('title')
    <title>@lang('frontend.booking_history') | {{ Hyvikk::get('app_name') }}</title>
@endsection

@section('content')
    @if (request()->is('booking-history/' . \Auth::user()->id))
        <style>
            .img-back-shadow1 {
                background-color: #f2f2f4 !important;
           }

           .set-icon-image:hover {
                transform:translateX(8px); 
           }
        </style>
    @endif
    <section class="booking-history-title background-container">
        <div class="booking-history-round">
            <img src="{{asset('assets/images/booking-history.png')}}" alt="round">
        </div>
        <div class="container">
            <div class="col-12">
                <div class="history-title">
                    <h2>
                        @lang('frontend.booking_history')
                    </h2>
                </div>
            </div>
        </div>
    </section>
    <section class="background-container mb-5">
        <div class="booking-history-details">
            <div class="container">
                <div class="row load-data">
                   @include('frontend.booking_history_tbl')
                </div>
            </div>
        </div>
    </div>
    </section>
@endsection


@section('script')
<script>

$(document).ready(function(){
    let stopLoading = false;
    let page = 2;

    function loadData() {
        if (stopLoading) return; 

        $.ajax({
            url: "{{ url('load_bookinghistory') }}",
            type: "get",
            data: {
                "page": page++
            },
            success: function(response) {
                if (response.status == 100) {
                    stopLoading = true;
                    $(".load-disable").html('');
                } else  {
                    $('.load-data').append(response.data).hide().fadeIn('slow');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error);
            }
        });
    }

    

    
    $(".load-page").on("click", function(){
        loadData();
    });

    
});
</script>


@endsection
