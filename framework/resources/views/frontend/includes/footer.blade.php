<div>
   <footer>
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="footer_logo">
                  <a href="{{ route('frontend.home') }}">
                     <img src="{{ asset('assets/images/footer_logo_back.png') }}">
                     <div class="footer_logo_img" style="position: absolute; ">
                        <img src="{{ asset('assets/images/'. Hyvikk::get('fotter_logo_img') ) }}">
                     </div>
                  </a>
               </div>
            </div>
            <div class="col-md-12">
               <div class="footer-menu">

                  <ul>
                     <li><a href="{{ route('frontend.contact') }}" title="Contact">@lang('frontend.contact')</a></li>
                     @if (!Auth::guest() && (Auth::user()->user_type == 'C' || Auth::user()->user_type == 'D'))
                     <li><a href="{{ route('frontend.booking_history', Auth::user()->id) }}" title="Booking history">@lang('frontend.booking_history')</a></li>
                     @endif
                     <li><a href="{{ route('frontend.about') }}" title="About us">@lang('frontend.about')</a></li>





                  </ul>

               </div>
            </div>
            <div class="col-md-12">
               <div class="social-icon">
                  <div class="social-icon-background">
                     <div class="facebook">
                        <a href="{{ Hyvikk::frontend('facebook') }}"><i class="fa-brands fa-facebook-f"></i></a>
                     </div>
                  </div>
                  <div class="social-icon-background">
                     <div class="twitter">
                        <a href="{{ Hyvikk::frontend('twitter') }}"><i class="fa-brands fa-twitter"></i></a>
                     </div>
                  </div>
                  <div class="social-icon-background">
                     <div class="instagram">
                        <a href="{{ Hyvikk::frontend('instagram') }}"><i class="fa-brands fa-instagram"></i></a>
                     </div>
                  </div>
                  <div class="social-icon-background">
                     <div class="instagram">
                        <a href="{{ Hyvikk::frontend('linkedin') }}"><i class="fa-brands fa-linkedin"></i></a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="copyright">
                  <p>{!! Hyvikk::get('web_footer') !!}</p>

               </div>
            </div>
         </div>
      </div>
   </footer>
</div>