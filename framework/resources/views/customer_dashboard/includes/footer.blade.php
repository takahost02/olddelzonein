  
   @if (!Auth::guest() && (Auth::user()->user_type == 'C' ))
   <footer class="footer pt-3  ">
       <div class="container-fluid">
           <div class="row align-items-center justify-content-lg-between">
               <div class="col-lg-6 mb-lg-0 mb-4">
                   <div class="copyright text-center text-sm text-muted text-lg-start">
                     <p class="mb-0 copyright-link">
                     {!! Hyvikk::get('web_footer') !!}
                   </p>
                   </div>
               </div>
               <div class="col-lg-6">
                   <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                     
               
 
                       @if(Hyvikk::frontend('footer_link'))
 
 
                       @foreach(json_decode(Hyvikk::frontend('footer_link')) as $f)
         
                         
         
                           <li class="nav-item">
                             <a href="{{$f->url}}" class="nav-link pe-0 text-muted" target="_blank"> {{$f->title}}</a>
                           </li>
 
 
                       @endforeach
         
                       @endif
 
                       
                   </ul>
               </div>
           </div>
       </div>
   </footer>
 
 
 @else
 
 <footer class="dark-footer res-footer footer pb-2 ">
       <div class="container">
         <div class="row"> 
           <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mt-1 order-2 order-sm-2 order-md-2 order-lg-1 order-xl-1 d-flex  justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start">
            
             <p class="mb-0 copyright-link">
               {!! Hyvikk::get('web_footer') !!}
              
             </p>
           </div> 
           <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 order-1 order-sm-1 order-md-1 order-lg-2 order-xl-2">
             <div class="footer_link">
          
 
               @if(Hyvikk::frontend('footer_link'))
 
 
               @foreach(json_decode(Hyvikk::frontend('footer_link')) as $f)
 
                   <a href="{{$f->url}}" target="_blank" class="me-0 me-sm-3 me-md-4 me-lg-4 me-xl-5 mb-sm-0 mb-2">{{$f->title}}</a>
                 
               @endforeach
 
               @endif
 
 
 
            
             </div>       
           </div>
          
         </div>
        
       </div>
     </footer>
     @endif