@extends('frontend.layouts.app')

@section('title')
    <title>@lang('frontend.about') | {{ Hyvikk::get('app_name') }}</title>
@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('assets/frontend/about.css') }}">
@endsection
@section('content')
@if(request()->is('about'))
  <style>
 
.main-section-background {
   position: relative;  
   width: 100%;
   height: auto;
   background-image: url('assets/images/header-back.png');
   background-repeat: no-repeat;
   background-position: right 0px;
  
}
  </style>
@endif
  


<div class="about-hero-section background-container">

   <div class="row me-0">

      <div class="col-12 col-sm-12 col-md-12 col-lg-6">

         <div class="about-hero-section-round">

            <img src="{{ asset('assets/images/round2.png') }}" class="img-fluid" alt="round">

         </div>

      </div>

      <div class="col-12 col-sm-12 col-md-12 col-lg-12">

         <div class="about-hero-section-round-fleet">

            <img src="{{ asset('assets/images/FLEET1.png') }}" class="img-fluid" alt="fleet">

         </div>

      </div>

   </div>

   <div class="about-hero-section-content">

      <div class="container">

         <div class="row d-flex">

            <div class="col-md-6 col-lg-6 order-md-first">

               <div class="about-section">

                  <h1 class="display-4">@lang('frontend.about') </br>{{ Hyvikk::get('app_name') }}</h1>

                  <div class="about-fleet-manager-content1 ps-2">

                     <p>{{ Hyvikk::frontend('about_us') }}</p>

                  </div>

               </div>

            </div>

            <div class="col-md-6 col-lg-6 order-md-last">

               <div class="about-hero-img">

                  <img src="{{ asset('assets/images/about-hero-img.png') }}" alt="about-hero-img">

               </div>

            </div>

         </div>

      </div>

   </div>

</div>

<section class="about-fleet-manager ">

   <div class="container-fluid px-0">

      <div class="row me-0 ms-0">

         <div class="col-12 col-md-5 col-lg-4 mt-5">

            <div class="about-fleet-manager-image">

               <img src="{{ asset('assets/images/Mask.png') }}" class="image-fluid" alt="mask"

                  style="box-shadow: rgba(0 0 0 / 25%) 0px 11px 41px 3px, rgb(0 0 0 / 10%) 0px 0px 0px 1px;">

            </div>

         </div>

         <div class="col-12 col-md-7 col-lg-7">

            <div class="about-fleet-manager-content mt-5">

               <h2>{{ Hyvikk::frontend('about_title') }}</h2>

               <div class="line"></div>

               <p class="d-flex">{{ Hyvikk::frontend('about_description') }}</p>

            </div>

         </div>



      </div>

   </div>

</section>

<section class="servey">

   <div class="container">

      <div class="row " style="display: flex;justify-content: space-evenly;">

         <div class="col-md-5">

            <div class="city-servey">

               <div class="city-servey-img">

                  <img src="{{ asset('assets/images/Mask_Group_7.png') }}" alt="mask">

               </div>

               <div class="count-servey">

                  <h3>{{ Hyvikk::frontend('cities') }} +</h3>

               </div>

               <div class="city-servey-content">

                  <h2>@lang('frontend.city_desc')</h2>

               </div>

            </div>

         </div>

         <div class="col-md-5 mt-4 mt-sm-4 mt-md-0 mt-lg-0 mt-xl-0">

            <div class="vehicle-servey">

               <div class="vehicle-servey-img">

                  <img src="{{ asset('assets/images/Mask_Group_9.png') }}" alt="">

               </div>

               <div class="vehicle-count-servey">

                  <h3>{{ Hyvikk::frontend('vehicles') }}+</h3>

               </div>

               <div class="vehicle-servey-content">

                  <h2>@lang('frontend.vehicle_desc') </h2>

               </div>

            </div>

         </div>

      </div>

   </div>

</section>

<section class="minds d-none d-sm-none d-md-none d-lg-flex d-xl-flex  ">

   <div class="minds-behind-fleet-manager px-5">

      <div class="container">

         <div class="row">

            <div class="col-12">

               <div class="minds-behind-title">

                  <h1>@lang('frontend.minds_behind') {{ Hyvikk::get('app_name') }}</h1>

               </div>

            </div>

         </div>

         @foreach($team as $key=>$teams)

         @if ($key % 2 == 0)

         <div class="row">

            <div class="col-md-5">

               <div class="client-1">

                  <div class="client-1-img">

                     {{-- <img src="images/client1.png" alt="">  --}}

                     @if ($teams->image != null)

                     <img src="{{ url('uploads/' . $teams->image) }}" alt="Image">

                     @else

                     <img src="{{ url('assets/images/client1.png') }}" alt="no-user">

                     @endif

                  </div>

               </div>

            </div>

            <div class="col-md-6">

               <div class="client-1-content">

                  <div>

                     <h2>{{ $teams->name }}</h2>

                  </div>

                  <div class="line-owner">

                     <div class="line"><span>{{ $teams->designation }}</span></div>

                  </div>

                  <div class="client-1-description">

                     <p>{{$teams->details}}</p>

                  </div>



               </div>

            </div>



            @else



            <div class="row">

               <div class="col-md-7">

                  <div class="client-2-content">

                     <div class="d-flex justify-content-end">

                        <h2>{{ $teams->name }}</h2>

                     </div>

                     <div class="line-owner2">

                        <div class="line"><span>{{ $teams->designation }}</span></div>

                     </div>

                     <div class="client-2-description">

                        <p>{{$teams->details}}!</p>

                     </div>



                  </div>

               </div>

               <div class="col-md-5">

                  <div class="client-2">

                     <div class="client-2-img">

                        @if ($teams->image != null)

                        <img src="{{ url('uploads/' . $teams->image) }}" alt="Image">

                        @else

                        <img src="{{ url('assets/images/client2.png') }}" alt="no-user">

                        @endif





                     </div>

                  </div>

               </div>





               @endif

               @endforeach



            </div>

         </div>

</section>

<section class="responsive-client-section d-flex d-sm-flex d-md-flex d-lg-none d-xl-none">

   <div class="container">

      <div class="row">

         <div class="col-12">

            <div class="responsive-mind-behind-title">

               <h1>@lang('frontend.minds_behind') {{ Hyvikk::get('app_name') }}</h1>

            </div>

         </div>

         @foreach($team as $key=>$teams)

         @if ($key % 2 == 0)

         <div class="col-12">

            <div class="client-1-responsive">

               <div class="row">

                  <div class="col-12">

                     <div class="client-1-responsive-img">

                        <div class="client-1-responsive-img1">





                           @if ($teams->image != null)

                           <img src="{{ url('uploads/' . $teams->image) }}" alt="Image">

                           @else

                           <img src="{{ asset('assets/images/client1.png') }}" alt="">

                           @endif

                        </div>

                        <div class="client-1-reponsive-name">

                           <h2>{{ $teams->name }}</h2>

                           <div class="line-owner">

                              <div class="line"><span>{{ $teams->designation }}</span></div>

                           </div>

                        </div>

                     </div>

                  </div>

                  <div class="col-12">

                     <div class="client-1-responsive-content">

                        <p>{{$teams->details}}</p>

                     </div>

                  </div>

               </div>

            </div>

         </div>

         @else

         <div class="col-12">

            <div class="client-2-responsive">

               <div class="row">

                  <div class="col-12">

                     <div class="client-2-responsive-img">

                        <div class="client-2-reponsive-name">

                           <h2>{{ $teams->name }}</h2>

                           <div class="line-owner2">

                              <div class="line"><span>{{ $teams->designation }}</span></div>

                           </div>

                        </div>

                        <div class="client-2-responsive-img1">

                           @if ($teams->image != null)

                           <img src="{{ url('uploads/' . $teams->image) }}" alt="Image">

                           @else

                           <img src="{{ asset('assets/images/client2.png') }}" alt="">

                           @endif

                        </div>

                     </div>

                  </div>

                  <div class="col-12">

                     <div class="client-2-responsive-content">

                        <p>{{$teams->details}}</p>

                     </div>

                  </div>

               </div>

            </div>

         </div>

         @endif

         @endforeach

      </div>

   </div>

</section>
 
 


@endsection
