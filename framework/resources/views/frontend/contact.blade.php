  @extends('frontend.layouts.app')

  @section('title')
  <title> @lang('frontend.contact') | {{ Hyvikk::get('app_name') }}</title>
@endsection

  @section('css')
      <style>
          .footer-menu {
              width: 100%;
          }

          .footer-link {
              display: flex;
              justify-content: center;
          }

          .contact-hero-section {
              width: 100%;
              display: flex;
              position: relative;
          }

          .contact-hero-section-round {
              width: 100%;
              height: auto;
          }

          .contact-hero-section-round-fleet {
              width: 100%;
              height: auto;
              position: relative;
              top: -234px;
              right: -135px;
          }

          .contact-hero-section-round img {
              width: auto;
              max-height: 100%;
              position: relative;
              left: -280px;
          }

          .contact-hero-section-content {
              width: 100%;
              position: absolute;
          }

          .contact-hero-section-round-fleet img {
              width: 55%;
              height: auto;
              float: right;
          }

          .contact-hero-img img {
              width: auto;
              max-height: 450px;
              position: absolute;
              top: 25px;
              right: 0px;
              right: -35px;
          }

          .contact-section {
              width: 100%;
              height: auto;
          }

          .contact-section .display-4 {
              font-family: 'antipasto pro';
              font-weight: 600;
              margin-top: 130px;
              margin-left: 8px;
              font-size: 3.8rem;
              line-height: 1.3;
              color: #130F41;
          }

          @media only screen and (max-width:1200px) {
              .contact-hero-img img {
                  height: 350px;
              }
          }

          @media only screen and (max-width:1070px) {
              .contact-hero-img img {
                  width: 480px;
              }
          }

          @media only screen and (max-width:992px) {
              .contact-hero-section-round-fleet {
                  width: 80%;
                  top: -180px;
              }

              .contact-hero-section-round-fleet img {
                  width: 75%;
              }

              .about-hero-img img {}

              .contact-hero-img img {
                  top: 144px;
                  width: 60%;
                  height: auto;
                  right: 0;

              }

              .contact-hero-section-round-fleet {

                  display: flex;
                  justify-content: end;
              }
          }

         
          @media only screen and (max-width:767px) {
              .contact-hero-section-round img {
                  width: 66%;
                  left: -237px;
              }

              .contact-hero-section-round-fleet {
                  justify-content: unset;
                  top: -100px;
                  right: -265px;
              }

              .contact-section .display-4 {
                  margin-top: 100px;
                  font-size: 3rem;
              }

              .contact-hero-img img {
                  top: 133px;
              }

          }

          @media only screen and (max-width:575px) {
              .contact-hero-section-round img {
                  width: 66%;
                  left: -237px;
              }

              .contact-section .display-4 {
                  margin-top: 90px;
                  margin-left: 20px;
              }

              .contact-hero-section-round-fleet {
                  top: -26px;
                  right: -207px;
              }
          }

       

          @media only screen and (max-width:480px) {
              .contact-hero-section-round-fleet {
                  top: -35px;
                  right: -160px;
              }

              .contact-hero-section-round img {
                  width: 66% !important;
                  left: -173px !important;
              }

              .contact-section .display-4 {
                  margin-left: 30px;
                  margin-top: 88px;
                  font-size: 2rem;
              }

              .contact-hero-img img {
                  top: 85px;
              }
          }

          @media only screen and (max-width:450px) {
              .contact-hero-section-round-fleet {
                  right: -75px;
              }

              .contact-hero-section-round-fleet img {
                  width: 100%;
              }
          }

          @media only screen and (max-width:360px) {
              .main-section-background {
                  background-size: 19% 400px;
                  height: 400px;
              }

              .contact-hero-section-round-fleet {
                  top: 0px !important;
                  right: -130pxs;
              }

              .contact-hero-section-round img {
                  width: 66% !important;
                  left: -135px !important;
              }

              .contact-section .display-4 {
                  margin-left: 13px;
                  margin-top: 67px;
                  font-size: 1.6rem;
              }

              .contact-hero-img img {
                  top: 110px;
                  right: -25px;
              }
          }

          .close.btn-check:focus+.close.btn,
          .close.btn:focus {
              border-color: #fff !important;
          }

          .close.btn-check:active+.btn,
          .close.btn-check:checked+.btn,
          .close.btn.active,
          .close.btn.show,
          .close.btn:active {
              border-color: #fff !important;
          }
      </style>
  @endsection



  @section('content')
      @if (request()->is('contact'))
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
      <section>
          <div class="contact-hero-section background-container" style="overflow-x:hidden">
              <div class="row">
                  <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                      <div class="contact-hero-section-round">
                          <img src="{{ asset('assets/images/round2.png') }}" class="img-fluid" alt="round">
                      </div>
                  </div>
                  <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                      <div class="contact-hero-section-round-fleet">
                          <img src="{{ asset('assets/images/FLEET1.png') }}" class="img-fluid" alt="fleet">
                      </div>
                  </div>
              </div>
              <div class="contact-hero-section-content">
                  <div class="container">
                      <div class="row d-flex">
                          <div class="col-12 col-md-12 col-lg-6 order-md-first">
                              <div class="contact-section">
                                  <h1 class="display-4">@lang('frontend.contact_us')</h1>
                              </div>
                          </div>
                          <div class="col-12 col-md-12 col-lg-6 order-md-last">
                              <div class="contact-hero-img">
                                  <img src="{{ asset('assets/images/phone.png') }}" alt="contact-hero-img">
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <section>
          <div class="contact-us-bg">
              <div class="contact-us-round">
                  <img src="{{ asset('assets/images/Subtraction 10.png') }}" alt="">
              </div>
              <div class="container">
                  <div class="contact-us-form">
                      <div class="contact-form">
                          <div class="row">
                              <div class="col-lg-7 col-md-12 ">
                                  <div class="get-in-touch-sec">
                                      <div class="row">
                                          <div class="col-12">
                                              <div class="get-in-touch-title">
                                                  <div class="d-flex">
                                                      <h2>@lang('frontend.get_in_touch')</h2>
                                                  </div>
                                                  <div class="line-booking-form">
                                                      <div class="line"><span>@lang('frontend.leave_us')</span></div>
                                                  </div>
                                              </div>
                                          </div>
                                        
                                          <div class="col-12">

                                           

                                        @if (\Session::has('success1'))
                                         
                                        <div class="hide-alert">
                                            <div class="alert alert-success custom-alert" role="alert">
                                                <span>{!! \Session::get('success1') !!}</span>
                                            </div>
                                        </div>
                                        @elseif(\Session::has('error1'))
                                        <div class="hide-alert">
                                            <div class="alert alert-danger custom-alert" role="alert">
                                                <span>{!! \Session::get('error1') !!}</span>
                                            </div>
                                        </div>
                                         @endif


                                             
                                              <form class="row mt-2" action="{{ route('user.enquiry') }}" method="POST">
                                                 {{ csrf_field() }}
                                                  <div class="col-md-12  input-effect contact-name">
                                                      <input class="effect-22 form-control" type="text" name="name"
                                                          placeholder="" required>
                                                      <label class="form-label"
                                                          style="position:absolute;">@lang('frontend.your_name')</label>
                                                      <span class="focus-bg"></span>
                                                  </div>

                                                  <div class="col-md-12 input-effect contact-name">
                                                      <input class="effect-22 form-control" type="email" name="email"
                                                          placeholder="" required>
                                                      <label class="form-label"
                                                          style="position:absolute;">@lang('frontend.email')</label>
                                                      <span class="focus-bg"></span>
                                                  </div>
                                                  <div class="col-md-12 input-effect contact-name">
                                                      <!-- <input class="effect-22 form-control" type="textarea" row="4" column="4" placeholder="" > -->

                                                      <textarea class="effect-22 form-control" cols="30" rows="3" name="message"
                                                          style="resize:none;" required></textarea>
                                                      <label class="form-label"
                                                          style="position:absolute;"> @lang('frontend.Message')</label>
                                                      <span class="focus-bg"></span>
                                                  </div>
                                                  <div class="col-12 contact-send">
                                                      {{--  <a href="#" class="btn" type="submit"> @lang('frontend.send') </a>  --}}
                                                      <button type="submit"
                                                          class="btn mx-auto form-submit-button--square ">@lang('frontend.send')</button>
                                                  </div>
                                              </form>
                                             
                                                
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-lg-5 col-md-12  mt-2 mt-sm-2 mt-md-2 mt-lg-0 mt-xl-0">
                                  <div class="p-e-a-sec">
                                      <div class="row">
                                          <div class="col-12">
                                              <div class="contact-phone">
                                                  <span>@lang('frontend.Phone')</span>
                                                  <p>{{ Hyvikk::frontend('contact_phone') }}</p>
                                              </div>
                                          </div>
                                          <div class="col-12">
                                              <div class="email-enquiry">
                                                  <span>@lang('frontend.e_mail')</span>
                                                  <p>{{ Hyvikk::frontend('contact_email') }}</p>
                                              </div>
                                          </div>
                                          <div class="map w-100 h-100">
                                            <!-- Google map starts -->
                                            <div id="map" class="w-100 h-100"></div>
                                            <script>
                                                function initMap() {

                                                    var address =
                                                        "{{ Hyvikk::get('badd1') . ', ' . Hyvikk::get('badd2') . ', ' . Hyvikk::get('city') . ', ' . Hyvikk::get('state') . ', ' . Hyvikk::get('country') . '.' }}";
                                                    var geocoder = new google.maps.Geocoder();
                                                    geocoder.geocode({
                                                        'address': address
                                                    }, function(results, status) {

                                                        if (status == google.maps.GeocoderStatus.OK) {
                                                            var latitude = results[0].geometry.location.lat();
                                                            var longitude = results[0].geometry.location.lng();
                                                        }

                                                        var uluru = {
                                                            lat: latitude,
                                                            lng: longitude
                                                        };
                                                        // Styles a map in night mode.
                                                        var map = new google.maps.Map(document.getElementById('map'), {
                                                            center: {
                                                                lat: latitude,
                                                                lng: longitude
                                                            },
                                                            zoom: 15,
                                                            disableDefaultUI: true,
                                                            styles: [{
                                                                    "elementType": "geometry",
                                                                    "stylers": [{
                                                                        "color": "#033f21"
                                                                    }]
                                                                },
                                                                {
                                                                    "elementType": "labels.icon",
                                                                    "stylers": [{
                                                                        "visibility": "off"
                                                                    }]
                                                                },
                                                                {
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#848484"
                                                                    }]
                                                                },
                                                                {
                                                                    "elementType": "labels.text.stroke",
                                                                    "stylers": [{
                                                                        "color": "#033f21"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "administrative",
                                                                    "elementType": "geometry",
                                                                    "stylers": [{
                                                                        "color": "#757575"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "administrative.country",
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#9e9e9e"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "administrative.land_parcel",
                                                                    "stylers": [{
                                                                        "visibility": "off"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "administrative.locality",
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#bdbdbd"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "poi",
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#757575"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "poi.park",
                                                                    "elementType": "geometry",
                                                                    "stylers": [{
                                                                        "color": "#033f21"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "poi.park",
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#616161"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "poi.park",
                                                                    "elementType": "labels.text.stroke",
                                                                    "stylers": [{
                                                                        "color": "#1b1b1b"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "road",
                                                                    "elementType": "geometry.fill",
                                                                    "stylers": [{
                                                                        "color": "#022613"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "road",
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#8a8a8a"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "road.arterial",
                                                                    "elementType": "geometry",
                                                                    "stylers": [{
                                                                        "color": "#022613"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "road.highway",
                                                                    "elementType": "geometry",
                                                                    "stylers": [{
                                                                        "color": "#022613"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "road.highway.controlled_access",
                                                                    "elementType": "geometry",
                                                                    "stylers": [{
                                                                        "color": "#022613"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "road.local",
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#616161"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "transit",
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#757575"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "water",
                                                                    "elementType": "geometry",
                                                                    "stylers": [{
                                                                        "color": "#006838"
                                                                    }]
                                                                },
                                                                {
                                                                    "featureType": "water",
                                                                    "elementType": "labels.text.fill",
                                                                    "stylers": [{
                                                                        "color": "#3d3d3d"
                                                                    }]
                                                                }
                                                            ],
                                                        });
                                                        var marker = new google.maps.Marker({
                                                            position: uluru,
                                                            map: map
                                                        });
                                                    });
                                                }
                                            </script>
                                            <script src="https://maps.googleapis.com/maps/api/js?key={{ Hyvikk::api('api_key') }}&callback=initMap" async defer>
                                            </script>
                                            <!-- Google map ends -->
                                        </div>
                                      </div>
                                      <div class="col-12">

                                          <div class="address-enquiry">
                                              <span>@lang('frontend.Address')</span>
                                              <p class="mb-0">{{ Hyvikk::get('badd1') . ', ' . Hyvikk::get('badd2') . ', ' . Hyvikk::get('city') . ', ' . Hyvikk::get('state') . ', ' . Hyvikk::get('country') . '.' }}
                                              </p>
                                              {{--  <p>309, Swara Parklane, Atabhai Chowk, Bhavnagar</p>  --}}
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
         
      </section>
  @endsection

  @section('script')
  <script>
      @if (\Session::has('success1'))
            setTimeout(function() {
                $(".hide-alert").html('');
            }, 4000);
     @elseif (\Session::has('error1'))
            setTimeout(function() {
                $(".hide-alert").html('');
            }, 4000);
    @endif
  </script>
@endsection
