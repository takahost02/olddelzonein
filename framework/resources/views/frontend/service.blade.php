@extends('frontend.layouts.app')

@section('title')
    <title>@lang('frontend.service') | {{ Hyvikk::get('app_name') }}</title>
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
  





<section class="minds d-none d-sm-none d-md-none d-lg-flex d-xl-flex  ">

   <div class="minds-behind-fleet-manager px-5">

      <div class="container">

         <div class="row">

            <div class="col-12">

               <div class="minds-behind-title">

                  <h1>@lang('frontend.provide') </h1>

               </div>

            </div>

         </div>

         @foreach($service as $key=>$services)

         @if ($key % 2 == 0)

         <div class="row">

            <div class="col-md-5">

               <div class="client-1">

                  <div class="client-1-img">

                     {{-- <img src="images/client1.png" alt="">  --}}

                     @if ($services->image != null)

                     <img src="{{ url('uploads/' . $services->image) }}" alt="Image">

                     @else

                     <img src="{{ url('assets/images/client1.png') }}" alt="no-user">

                     @endif

                  </div>

               </div>

            </div>

            <div class="col-md-6">

               <div class="client-1-content">

                  <div>

                     <h2>{{ $services->name }}</h2>

                  </div>

                  <div class="line-owner">

                     <div class="line"><span>{{ $services->designation }}</span></div>

                  </div>

                  <div class="client-1-description">

                     <p>{{$services->details}}</p>

                  </div>



               </div>

            </div>



            @else



            <div class="row">

               <div class="col-md-7">

                  <div class="client-2-content">

                     <div class="d-flex justify-content-end">

                        <h2>{{ $services->name }}</h2>

                     </div>

                     <div class="line-owner2">

                        <div class="line"><span>{{ $services->designation }}</span></div>

                     </div>

                     <div class="client-2-description">

                        <p>{{$services->details}}!</p>

                     </div>



                  </div>

               </div>

               <div class="col-md-5">

                  <div class="client-2">

                     <div class="client-2-img">

                        @if ($services->image != null)

                        <img src="{{ url('uploads/' . $services->image) }}" alt="Image">

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

         @foreach($service as $key=>$services)

         @if ($key % 2 == 0)

         <div class="col-12">

            <div class="client-1-responsive">

               <div class="row">

                  <div class="col-12">

                     <div class="client-1-responsive-img">

                        <div class="client-1-responsive-img1">





                           @if ($services->image != null)

                           <img src="{{ url('uploads/' . $services->image) }}" alt="Image">

                           @else

                           <img src="{{ asset('assets/images/client1.png') }}" alt="">

                           @endif

                        </div>

                        <div class="client-1-reponsive-name">

                           <h2>{{ $services->name }}</h2>

                           <div class="line-owner">

                              <div class="line"><span>{{ $services->designation }}</span></div>

                           </div>

                        </div>

                     </div>

                  </div>

                  <div class="col-12">

                     <div class="client-1-responsive-content">

                        <p>{{$services->details}}</p>

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

                           <h2>{{ $services->name }}</h2>

                           <div class="line-owner2">

                              <div class="line"><span>{{ $services->designation }}</span></div>

                           </div>

                        </div>

                        <div class="client-2-responsive-img1">

                           @if ($services->image != null)

                           <img src="{{ url('uploads/' . $services->image) }}" alt="Image">

                           @else

                           <img src="{{ asset('assets/images/client2.png') }}" alt="">

                           @endif

                        </div>

                     </div>

                  </div>

                  <div class="col-12">

                     <div class="client-2-responsive-content">

                        <p>{{$services->details}}</p>

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
