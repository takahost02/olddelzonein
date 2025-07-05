@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item">@lang('menu.settings')</li>
<li class="breadcrumb-item active">@lang('fleet.frontend_settings')</li>
@endsection
@section('extra_css')
<style type="text/css">
  .nav-link {
    padding: .5rem !important;
  }

  .custom .nav-link.active {

      background-color: #21bc6c !important;
  }

  /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>

@endsection


@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.frontend_settings')
        </h3>
      </div>
      {!! Form::open(['url' => 'admin/frontend-settings', 'method' => 'post', 'files' => true]) !!}

      <div class="card-body">
        <div class="row">
          @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
              </ul>
            </div>
          @endif
        </div>
        <div class="row">
          <div class="col-md-4 col-sm-12">
            <h4>  @lang('fleet.frontend_settings')<span id="change" class="text-muted">
              @if(Hyvikk::frontend('enable')==1)
                (@lang('fleet.enable'))
              @else
                (@lang('fleet.disable'))
              @endif
            </span><a data-toggle="modal" data-target="#myModal1"><i class="fa fa-info-circle fa-lg ml-1" aria-hidden="true"  style="color: #8639dd"></i></a>
          </h4>
          </div>
          <div class="col-md-3 col-sm-12">
            <label class="switch">
              <input type="checkbox" name="enable" value="1" id="enable" @if(Hyvikk::frontend('enable')==1) checked @endif>
              <span class="slider round"></span>
            </label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              {!! Form::label('about', __('fleet.about_breadcrumb'), ['class' => 'form-label']) !!}
              <textarea name="about" class="form-control" rows="3" required>{{ Hyvikk::frontend('about_us') }}</textarea>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('customer_support',__('fleet.customer_support'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                {!! Form::number('customer_support', Hyvikk::frontend('customer_support') ,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('phone',__('fleet.contact_number'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-phone"></i></span>
                </div>
                {!! Form::number('phone', Hyvikk::frontend('contact_phone') ,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('email', __('fleet.contact_email'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                {!! Form::email('email',  Hyvikk::frontend('contact_email') ,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              {!! Form::label('about_description', __('fleet.aboutFleetManagerDescription'), ['class' => 'form-label']) !!}
              <textarea name="about_description" class="form-control" rows="3" required>{{ Hyvikk::frontend('about_description') }}</textarea>
            </div>
          </div>
            <div class="col-md-6">
              <div class="form-group">
              {!! Form::label('about_title',__('fleet.aboutFleetManagerTitle'), ['class' => 'form-label']) !!}
              {!! Form::text('about_title', Hyvikk::frontend('about_title') ,['class' => 'form-control','required']) !!}
            </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
            {!! Form::label('language',__('fleet.language'),['class'=>"form-label"]) !!}
            <select id='language' name='language' class="form-control" required>
              <option value="">-</option>              
              @php($language = Hyvikk::frontend("language"))
              
              @foreach($languages as $lang)
              @if($lang != "vendor")
              @php($l = explode('-',$lang))
              @if($language == $lang)

              <option value="{{$lang}}" selected> {{$l[0]}}</option>
              @else
              <option value="{{$lang}}" > {{$l[0]}} </option>
              
              @endif
              @endif
              @endforeach
            </select>
          </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('faq_link',__('fleet.faq_link'), ['class' => 'form-label']) !!}
              {!! Form::text('faq_link', Hyvikk::frontend('faq_link') ,['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('cities',__('fleet.cities_serving'), ['class' => 'form-label']) !!}
              {!! Form::number('cities', Hyvikk::frontend('cities') ,['class' => 'form-control','required','min'=>0]) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('vehicles',__('fleet.vehicles_serving'), ['class' => 'form-label']) !!}
              {!! Form::number('vehicles', Hyvikk::frontend('vehicles') ,['class' => 'form-control','required','min'=>0]) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('booking_time',__('fleet.booking_time'), ['class' => 'form-label']) !!}
              {!! Form::number('booking_time', Hyvikk::frontend('booking_time') ,['class' => 'form-control','required','min'=>1,'step'=>1]) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('cancellation',__('fleet.cancellation_link'), ['class' => 'form-label']) !!}
              {!! Form::text('cancellation', Hyvikk::frontend('cancellation') ,['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('terms',__('fleet.terms'), ['class' => 'form-label']) !!}
              {!! Form::text('terms', Hyvikk::frontend('terms') ,['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('privacy_policy',__('fleet.privacy_policy'), ['class' => 'form-label']) !!}
              {!! Form::text('privacy_policy', Hyvikk::frontend('privacy_policy') ,['class' => 'form-control']) !!}
            </div>
          </div>
        </div>


        <hr>
        <div class="row" id="footer-links">
          <div class="col-md-12 text-center">
              <h4>@lang('fleet.footer_menu_link')</h4>
          </div>

     
      
          @if(Hyvikk::frontend('footer_link'))


          @foreach(json_decode(Hyvikk::frontend('footer_link')) as $f)

          <div class="link-group col-md-12 d-flex">
              <div class="col-md-3">
                  <label>@lang('fleet.Title')</label>
                  <input type="text" name="title[]" class="form-control" value="{{$f->title}}" required>
              </div>
              <div class="col-md-3">
                  <label>@lang('fleet.URL')</label>
                  <input type="url" name="url[]" class="form-control" value="{{$f->url}}" required>
              </div>
              <div class="col-md-3 d-flex align-items-end">
                  <button type="button" class="btn btn-danger remove-link" disabled>Remove</button>
              </div>
          </div>

          @endforeach

          @endif
      </div>
      
      <div class="mt-3">
          <button type="button" id="add-link" class="btn btn-primary">Add</button>
      </div>

      <hr>

      <div class="row">
        <div class="col-md-12">
            <label>@lang('fleet.Signup_Page_Title')</label>

            <input type="text" name="sign_up_title" value="{{ Hyvikk::frontend('sign_up_title') }}" class="form-control ">

        </div>
      </div>


      <hr>

      <div class="row">
        <div class="col-md-12">
            <label>@lang('fleet.Signup_Page_Sub_Title')</label>

            <input type="text" name="sign_up_sub_title" value="{{ Hyvikk::frontend('sign_up_sub_title') }}" class="form-control ">

        </div>
      </div>


      <hr>

      <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-12 text-center"><h4>@lang('fleet.Sign_Up_Content')</h4></div>
    
            <div class="field-container">

              @if(Hyvikk::frontend('sign_up_content'))


              @foreach(json_decode(Hyvikk::frontend('sign_up_content')) as $s)
    

                <div class="field-group col-md-12 d-flex mt-3">
                  
                    <div class="col-3 ">

                      <div class="d-flex justify-content-between">
                         <label>File(103px * 104px)</label>

                         @if(isset($s->file_path))

                         <a href="javascript:void(0)"  class="btn-modal" data-toggle="modal" data-target="#myModa11111" data-img="{{url('/uploads'.'/'.$s->file_path)}}">View</a>

                        @endif

                      </div>
                      
                      <input type="hidden" name="existing_file_path[]" value="{{$s->file_path}}">


                        <input type="file" name="signup_file[]" class="form-control" @if(!isset($s->file_path)) required @endif value="{{$s->file_path}}">
                    </div>
    
                    <div class="col-3">
                        <label>@lang('fleet.Title')</label>
                        <input type="text" name="signup_title[]" class="form-control" value="{{$s->title}}" @if(!isset($s->title)) required @endif>
                    </div>
    
                    <div class="col-3">
                        <label>@lang('fleet.Subtitle')</label>
                        <input type="text" name="signup_subtitle[]" class="form-control" value="{{$s->subtitle}}" @if(!isset($s->subtitle)) required @endif>
                    </div>
                    
                    <div class="col-3 pt-4">
                                                   
                      <button type="button" class="remove-btn btn btn-danger" disabled>Remove Fields</button>

                    </div>

                </div>


                @endforeach

                @endif

            </div>
    
            <div class="mt-3">
                <button type="button" class="add-btn btn btn-primary">Add Field</button>
            </div>
        </div>
    </div>




        <hr>
        <div class="row">
          <div class="col-md-12 text-center"><h4>@lang('fleet.social_links')</h4></div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('facebook',__('fleet.facebook'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-facebook"></i></span>
                </div>
                {!! Form::text('facebook', Hyvikk::frontend('facebook') ,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('twitter',__('fleet.twitter'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-twitter"></i></span>
                </div>
                {!! Form::text('twitter', Hyvikk::frontend('twitter') ,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('instagram',__('fleet.instagram'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-instagram"></i></span>
                </div>
                {!! Form::text('instagram', Hyvikk::frontend('instagram') ,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              {!! Form::label('linkedin',__('fleet.linkedin'), ['class' => 'form-label']) !!}
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-linkedin"></i></span>
                </div>
                {!! Form::text('linkedin', Hyvikk::frontend('linkedin') ,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="col-md-3 col-sm-12">
              {!! Form::label('approval_required',__('fleet.approval_required'), ['class' => 'form-label']) !!}
              <a data-toggle="modal" data-target="#myModal2"><i class="fa fa-info-circle fa-lg ml-1" aria-hidden="true"  style="color: #8639dd"></i></a>
              <label class="switch">
                <input type="checkbox" name="approval_required" value="1" id="approval_required" @if(Hyvikk::frontend('admin_approval')==1) checked @endif>
                <span class="slider round"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="form-group">
            <input type="submit" class="form-control btn btn-success" value="@lang('fleet.save')"/>
          </div>
        </div>
      </div>
      {!! Form::close()!!}
      </div>
    </div>
  </div>
</div>

<!-- Modal 1-->
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.frontend_settings')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.frontend_settings_info')</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal 1-->

<!-- Modal 2-->
<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.approval_required')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.approval_required_info')</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal 2-->

<div id="myModa11111" class="modal fade" role="dialog">
  <div class="modal-dialog" role="document">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Image</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center">
        

        <img src="" class="show-image" height="150px" width="150px">


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>




@endsection

@section('script')


<script type="text/javascript">
  //Flat green color scheme for iCheck
    // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    //   checkboxClass: 'icheckbox_flat-green',
    //   radioClass   : 'iradio_flat-green'
    // });

    $('#enable').change(function () {
      if($('#enable').is(":checked")){
        // alert("checked");
        $("#change").empty();
        $("#change").append(" (@lang('fleet.enable'))");

      }
      else{
        // alert("unchecked");
        $("#change").empty();
        $("#change").append(" (@lang('fleet.disable'))");
      }
    });



    $(document).ready(function () {
    $("#add-link").click(function () {
        let newRow = `
        <div class="link-group col-md-12 d-flex">
            <div class="col-md-3">
                <label>Title</label>
                <input type="text" name="title[]" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>URL</label>
                <input type="url" name="url[]" class="form-control" required>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-link">Remove</button>
            </div>
        </div>`;
        $("#footer-links").append(newRow);
        updateRemoveButtonState();
    });

    $(document).on("click", ".remove-link", function () {
        $(this).closest(".link-group").remove();
        updateRemoveButtonState();
    });

    function updateRemoveButtonState() {
        if ($(".link-group").length > 2) {
            $(".remove-link").prop("disabled", false);
        } else {
            $(".remove-link").prop("disabled", true);
        }
    }
});



$(document).ready(function () {
    // Function to check if remove buttons should be disabled
    function checkRemoveButtons() {
        if ($(".field-group").length <= 4) {
            $(".remove-btn").prop("disabled", true); // Disable when only 4 groups remain
        } else {
            $(".remove-btn").prop("disabled", false); // Enable when more than 4 groups exist
        }
    }

    // Initial check on page load
    checkRemoveButtons();

    // Add new set of fields
    $(".add-btn").click(function () {
        let newFieldSet = `<div class="field-group col-md-12 d-flex mt-3">
                                <div class="col-3">
                                    <label>File(103px * 104px)</label>
                                    <input type="file" name="signup_file[]" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label>Title</label>
                                    <input type="text" name="signup_title[]" class="form-control">
                                </div>
                                <div class="col-3">
                                    <label>Subtitle</label>
                                    <input type="text" name="signup_subtitle[]" class="form-control">
                                </div>
                                <div class="col-3 pt-4">
                                    <button type="button" class="remove-btn btn btn-danger">Remove Fields</button>
                                </div>
                           </div>`;
        $(".field-container").append(newFieldSet);
        checkRemoveButtons();
    });

    // Remove only the clicked field group
    $(document).on("click", ".remove-btn", function () {
        if ($(".field-group").length > 4) {
            $(this).closest(".field-group").remove();
            checkRemoveButtons();
        }
    });
});


$(document).on("click",".btn-modal",function(){

  $(".show-image").attr("src",$(this).data('img'));

});


</script>
@endsection