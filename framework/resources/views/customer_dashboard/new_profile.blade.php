

@extends('customer_dashboard.layouts.app')

@section('title')

      <title>@lang('frontend.Profile') | {{ Hyvikk::get('app_name') }}</title>

  @endsection

@section('css')

<link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/new_profile.css') }}">

<style>
    
    .set-pofile-div{
        display: flex;
        flex-direction: column;
        align-items: center;
    }

   
</style>

@endsection

     @section('breadcrumb')

{{--  <li class="breadcrumb-item text-sm"></li>  --}}

<li class="breadcrumb-item text-sm text-dark active" ><a href="{{url('/new_profile')}}"aria-current="page">@lang('frontend.Profile')</a></li>

{{--  <li class="active">@lang('equicare.edit')</li>  --}}

@endsection

@section('contents')

{{--  @include('customer_dashboard.includes.header2')   --}}



<div class="custom-alert-msg" style="color:white;"></div>

<div class="page-header min-height-300 border-radius-xl mt-3" style="background-color: rgba(239, 239, 239, 1);border-radius: 0;">

    <!-- <span class="mask opacity-6"></span> -->

    

    <div class="container">

        <div class="row">

            <div class="col-12">

                <div class="user_profile pt-7 pb-4 pb-sm-0 pb-md-0 pb-lg-0 pb-xl-0 mt-sm-5 mt-md-5 mt-lg-5 mt-xl-5">

                    <div class="user_details mb-3 set-pofile-div">

                        <div class="user_profile_img">

                          @if(isset(Auth::user()->profile_pic))

                              <img src="{{ asset('uploads/'.Auth::user()->profile_pic) }}">

                          @else

                              <img src="{{ asset('uploads/no-user.jpg') }}" >

                          @endif

                        </div>

                        <div class="user_info sign_title">

                            <p class="sign_up mt-2">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>



                            @if(isset(Auth::user()->gender) && Auth::user()->gender == 1)

                                   <p class="mb-0 g_male">Male <img src="{{ asset('assets/customer_dashboard/assets/img/svg/g_male.svg') }}" class="ms-2"> </p>

                           @elseif(isset(Auth::user()->gender) && Auth::user()->gender == 0)

                                  <p class="mb-0 g_female">Female <img src="{{ asset('assets/customer_dashboard/assets/img/svg/g_female.svg') }}" class="ms-2"> </p> 

                             @endif

                          

                         </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



@endsection

@section('contented')

<div class="px-0 px-sm-0 px-md-4 px-lg-4 px-xl-4 py-3">

    <div class="container-fluid py-4">

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card shadow-sm booking_Detail_card res-grid">

                    <div class="card-header">

                        <p class="booking_details_title">@lang('frontend.Basic_Information')</p>

                    </div>

                    <div class="card-body  px-0 px-sm-0 px-md-3 px-lg-3 px-xl-3 py-2">

                        <div class="booking_tab_content">

                            <div class="container px-0">

                                <div class="row">

                                    <div class="col-12">

                                        <div class="sign-up-form p-3 pb-0">

                                            <form method="post" id="update-profile">

                                                <div class="container px-0">

                                                    <div class="row">

                                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 ">

                                                            <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.First_Name')</label>

                                                            <input type="text" name="first_name" class="form-control profile-first-name" value="{{ (Auth::user()->first_name??'-') }}" aria-describedby="emailHelp" placeholder="Enter your First Name">

                                                              <span class="error_first_name text-danger"></span>  

                                                       </div>

                                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">

                                                            <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Last_Name')</label>

                                                            <input type="text" name="last_name" class="form-control profile-last-name" value="{{ (Auth::user()->last_name??'-') }}" aria-describedby="emailHelp" placeholder="Enter your Last Name">

                                                            <span class="error_last_name text-danger"></span> 

                                                        </div>

                                                        <div class="col-12">

                                                            <div class="radio-btn-groups d-flex align-items-center mt-4 mb-3">

                                                                <lable class="form-label custom-form-label mb-0">@lang('frontend.sele_gender')</lable>

                                                                <div class="radio-btn d-flex align-items-center ms-0 ms-sm-4 ms-md-4 ms-lg-4 ms-xl-4">

                                                                    <div class="form-check d-flex align-items-center">

                                                                        <!-- <input class="form-check-input" type="radio" name="flexRadioDefault"

                                    id="customRadio1" checked> -->

                                                                        <input type="radio" class="black gender-value profile-gender" name="gender" value="1" id="male" @if(isset(Auth::user()->gender) && Auth::user()->gender== 1) checked @endif>

                                                                        <label class="custom-control-label custom-form-label mb-0 " for="male">@lang('frontend.male')</label>

                                                                    </div>

                                                                    <div class="form-check d-flex align-items-center">

                                                                        <!-- <input class="form-check-input" type="radio" name="flexRadioDefault"

                                    id="customRadio2"> -->

                                                                        <input type="radio" class="black gender-value profile-gender" name="gender" value="0" id="female" @if(isset(Auth::user()->gender) && Auth::user()->gender == 0) checked @endif>

                                                                        <label class="custom-control-label custom-form-label mb-0 " for="female">@lang('frontend.female')</label>

                                                                    </div>



                                                                    <span class="error_gender text-danger"></span> 

                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">

                                                            <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Email_Id')</label>

                                                            <input type="email" name="email" class="form-control profile-email" value="{{ (Auth::user()->email??'-') }}"  aria-describedby="emailHelp" placeholder="Enter your Email Address">

                                                            <span class="error_email text-danger"></span> 

                                                        </div>

                                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">

                                                            <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Phone_Number')</label>

                                                            <input type="text" name="phone" class="form-control profile-phone" value="{{ (Auth::user()->mobno??'-') }}"  aria-describedby="emailHelp" placeholder="Enter your Phone Number">

                                                            <span class="error_phone text-danger"></span> 

                                                        </div>

                                                        <div class="col-12 mb-3 mt-0 mt-sm-3 mt-md-3 mt-lg-3 mt-xl-3">

                                                           

                                                                <label for="exampleFormControlTextarea1">@lang('frontend.Address_Optional')</label>

                                                                <textarea name="address" class="form-control profile-address" placeholder="Enter your Address" id="floatingTextarea" style="resize:none;">{{ (Auth::user()->address??'-') }}</textarea>

                                                         

                                                        </div>



                                                        <div class="col-12 d-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start">

                                                            <button type="button" class="btn btn-square-blue mt-0 update-profile">
                                                                
                                                                <div class="spinner-border spin-1 d-none" role="status">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                  </div>
                                                                
                                                                  <div class="spin-2">
                                                                    Update Profile

                                                                  </div>

                                                                </button>

                                                        </div>



                                                    </div>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <div class="col-12 col-lg-4  ms-0 ms-sm-0 ms-md-0 ms-lg-5 ms-xl-5 mt-3 mt-sm-3 mt-md-3 mt-lg-0 mt-xl-0">

                <div class="card shadow-sm small_card  booking_Detail_card res-grid">

                    <div class="card-header">

                        <p class="booking_details_title">@lang('frontend.Update_Password')</p>

                    </div>

                    <div class="card-body   px-0 px-sm-0 px-md-3 px-lg-3 px-xl-3 py-2">

                        <div class="booking_tab_content">

                            <div class="container px-0">

                                <div class="row">

                                    <div class="col-12">

                                        <div class="sign-up-form p-3">

                                            <form type="post" id="update-password">

                                                <div class="container px-0">

                                                    <div class="row">

                                                        <div class="col-12">

                                                            <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Current_Password')</label>

                                                            <input type="password" name="password" class="form-control"  aria-describedby="emailHelp" placeholder="Current Password">

                                                          <span class="error_password text-danger"></span>

                                                        </div>

                                                        <div class="col-12 mt-0 mt-sm-3 mt-md-2 mt-lg-2 mt-xl-2">

                                                            <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.New_password')</label>

                                                            <input type="password" name="new_password" class="form-control"  aria-describedby="emailHelp" placeholder="New Password">

                                                            <span class="error_new_password text-danger"></span>

                                                        </div>

                                                        <div class="col-12 mt-0 mt-sm-3 mt-md-2 mt-lg-2 mt-xl-2">

                                                            <label for="exampleInputEmail1" class="form-label mb-0">@lang('frontend.Confirm_New_Password')</label>

                                                            <input type="password" name="confirm_password"class="form-control" aria-describedby="emailHelp" placeholder="Confirm New Password">

                                                            <span class="error_confirm_password text-danger"></span>

                                                        </div>

                                                        <div class="col-12 mt-3">

                                                            <label for="exampleInputEmail1" class="form-label mb-0 d-flex" style="color: rgba(33, 82, 255, 1);">@lang('frontend.Password_guide')

                                                                <div class="tooltip-container ms-2">

                                                                    <span class="tooltip-trigger "><img src="{{asset('assets/customer_dashboard/assets/img/svg/info-hexagon.svg')}}" class="ms-1"></span>

                                                                    <div class="tooltip-content">@lang('frontend.Enter_Valid_Password')</div>

                                                                </div>

                                                            </label>

                                                            <ul class="pass_guide">

                                                                <li>

                                                                    <p>Min 1 Special Character (@ # $ % & ! * ?)</p>

                                                                </li>

                                                                <li>

                                                                    <p>Min Length 6 Characters & Upto 18 Characters</p>

                                                                </li>

                                                                <li>

                                                                    <p>Min 2 Numerical Characters</p>

                                                                </li>

                                                            </ul>

                                                        </div>

                                                        <div class="col-12 d-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-start justify-content-xl-start">

                                                            <button type="button" class="btn btn-square-blue mt-3 mb-0 update-password"> 

                                                                <div class="spinner-border spin-3 d-none" role="status">
                                                                    <span class="visually-hidden">Loading...</span>
                                                                  </div>
                                                                
                                                                  <div class="spin-4">
                                                                    Update Profile

                                                                  </div>

                                                            </button>

                                                        </div>

                                                    </div>

                                                </div>

                                            </form>

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

@endsection



@section('script')

<script>

  $(document).ready(function(){



          $(".update-password").on("click",function(){



          $(".error_password").text('');

          $(".error_new_password").text('');

          $(".error_confirm_password").text('');



          $.ajax({

              headers: {

                  'X-CSRF-TOKEN': "{{ csrf_token() }}"

              },

              url:"{{route('update.password')}}",

              type:"post",

              data:$("#update-password").serialize(),

              beforeSend: function() {

             
                $(".spin-3").removeClass('d-none');

                $(".spin-4").addClass('d-none');

                },

              success:function(data)

              {


                $(".spin-3").addClass('d-none');

                $(".spin-4").removeClass('d-none');


                  var tblprint='';

                  if(data.error)

                  {   

                      if(data.error.password)

                      {

                          $(".error_password").text(data.error.password);

                      }

                      else

                      {

                          $(".error_password").text('');

                      }



                      if(data.error.new_password)

                      {

                          $(".error_new_password").text(data.error.new_password);

                      }

                      else

                      {

                          $(".error_new_password").text('');

                      }



                      if(data.error.confirm_password)

                      {

                          $(".error_confirm_password").text(data.error.confirm_password);

                      }

                      else

                      {

                          $(".error_confirm_password").text('');

                      }

                  }

                  else if(data.status == 100)

                  {

                      tblprint+=` <div class="alert alert-success custom-alert" role="alert">

                             <strong style="color:white;">Password Update Sucessfully</strong>

                      </div>`;

                      $(".custom-alert-msg").html(tblprint); 



                      setTimeout(function() {

                          $(".custom-alert-msg").html('');

                      }, 6000); 

                  }

                  else

                  {

                      tblprint+=` <div class="alert alert-danger custom-alert" role="alert">

                             <strong style="color:white;">Password Update Not Sucessfully</strong>

                      </div>`;

                      $(".custom-alert-msg").html(tblprint); 



                      setTimeout(function() {

                          $(".custom-alert-msg").html('');

                      }, 6000); 

                  }



              },

              error: function (jqXHR, exception) {

                $(".spin-3").addClass('d-none');

                $(".spin-4").removeClass('d-none');

                  var tblprint='';

                  tblprint+=` <div class="alert alert-danger custom-alert" role="alert">

                             <strong style="color:white;">Password Update Not Sucessfully</strong>

                      </div>`;

                      $(".custom-alert-msg").html(tblprint); 



                      setTimeout(function() {

                          $(".custom-alert-msg").html('');

                      }, 6000); 

              }

          });

      });







      $(".update-profile").on("click",function(){



          $(".error_first_name").text('');

          $(".error_last_name").text('');

          $(".error_gender").text('');

          $(".error_email").text('');

          $(".error_phone").text('');





          var files = $("#myFile").prop("files");

          var image = files.length > 0 ? files[0] : '';

         

          var formData = new FormData($("#update-profile")[0]); 

          formData.append('image',image);

          formData.append("_token", "{{ csrf_token() }}"); 



   

          $.ajax({

              url:"{{route('update.profile')}}",

              type:"post",

              data:formData,

              cache: false,

              contentType: false,

              processData: false,

              beforeSend: function() {

             
                $(".spin-1").removeClass('d-none');

                $(".spin-2").addClass('d-none');

                },

              success:function(data)

              {

                $(".spin-1").addClass('d-none');

                $(".spin-2").removeClass('d-none');


                  var tblprint='';

                  if(data.error)

                  {   

                      if(data.error.first_name)

                      {

                          $(".error_first_name").text(data.error.first_name);

                      }

                      else

                      {

                          $(".error_first_name").text('');

                      }



                      if(data.error.last_name)

                      {

                          $(".error_last_name").text(data.error.last_name);

                      }

                      else

                      {

                          $(".error_last_name").text('');

                      }



                      if(data.error.gender)

                      {

                          $(".error_gender").text(data.error.gender);

                      }

                      else

                      {

                          $(".error_gender").text('');

                      }

                      if(data.error.email)

                      {

                          $(".error_email").text(data.error.email);

                      }

                      else

                      {

                          $(".error_email").text('');

                      }

                      if(data.error.phone)

                      {

                          $(".error_phone").text(data.error.phone);

                      }

                      else

                      {

                          $(".error_phone").text('');

                      }

                   }

                  else if(data.status == 100)

                  {

                      tblprint+=` <div class="alert alert-success custom-alert" role="alert">

                             <strong style="color:white;">Your Profile Update Successfully.</strong>

                      </div>`;

                   $(".custom-alert-msg").html(tblprint); 



                   setTimeout(function() {

                      $(".custom-alert-msg").html('');

                  }, 6000); 

                      

                  }   

                  else

                  {

                      tblprint+=` <div class="alert alert-danger custom-alert" role="alert">

                             <strong style="color:white;">Your Profile Update Not Successfully.</strong>

                      </div>`;

                      $(".custom-alert-msg").html(tblprint); 



                      setTimeout(function() {

                          $(".custom-alert-msg").html('');

                      }, 6000); 

                  } 

              },

              error: function (jqXHR, exception) {


                $(".spin-1").addClass('d-none');

                $(".spin-2").removeClass('d-none');

                  var tblprint='';

                  tblprint+=` <div class="alert alert-danger custom-alert" role="alert">

                             <strong style="color:white;">Your Profile Update Not Successfully.</strong>

                      </div>`;

                      $(".custom-alert-msg").html(tblprint); 



                      setTimeout(function() {

                          $(".custom-alert-msg").html('');

                      }, 6000); 

              }

          });





      });

  });

  

</script>

@endsection







