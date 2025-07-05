@extends('layouts.app')
@section('extra_css')
<style type="text/css">
  /* The switch - the box around the slider */
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
  }
  /* Hide default HTML checkbox */
  .switch input {
    display: none;
  }
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
  input:checked+.slider {
    background-color: #2196F3;
  }
  input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
  }
  input:checked+.slider:before {
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
  .custom .nav-link.active {
    background-color: #f4bc4b !important;
    color: inherit;
  }
  /* .select2-selection:not(.select2-selection--multiple) {
    height: 38px !important;
  }
  span.select2-selection.select2-selection--multiple {
    width: 100%;
  }
  input.select2-search__field {
    width: auto !important;
  }
  span.select2.select2-container {
    width: 100% !important;
  } */
</style>
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route('vehicles.index')}}">@lang('fleet.vehicles')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_vehicle')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_vehicle')</h3>
      </div>
      <div class="card-body">
        <div class="tab-content">
          <div class="tab-pane active" id="info-tab">
            {!! Form::open(['route' =>['vehicles.change',$vehicle->id],'files'=>true,
            'method'=>'PATCH','class'=>'form-horizontal','id'=>'accountForm1']) !!}
            {!! Form::hidden('user_id',Auth::user()->id) !!}
            {!! Form::hidden('id',$vehicle->id) !!}
            <div class="row card-body">
              <div class="col-md-4">
                <div class="form-group">
                  {{-- @dd($makes) --}}
                  {!! Form::label('make_name', __('fleet.SelectVehicleMake'), ['class' => 'col-xs-5 control-label']) !!}
                  <a data-toggle="modal" data-target="#myModal"><i class="fa fa-info-circle fa-lg" aria-hidden="true"  style="color: #8639dd"></i></a>
                  <div class="col-xs-6">
                    <select name="make_name" class="form-control" required id="make_name">
                      <option></option>
                      @foreach($makes as $make)
                      <option value="{{$make}}" @if($make == $vehicle->make_name) selected @endif>{{$make}}
                      </option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('model_name', __('fleet.SelectVehicleModel'), ['class' => 'col-xs-5 control-label']) !!}
                  <a data-toggle="modal" data-target="#myModal2"><i class="fa fa-info-circle fa-lg" aria-hidden="true"  style="color: #8639dd"></i></a>
                  <div class="col-xs-6">
                    <select name="model_name" class="form-control" required id="model_name">
                      @foreach($models as $model)
                      <option value="{{ $model }}" @if($model == $vehicle->model_name) selected @endif>{{
                        $model }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('type', __('fleet.type'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    <select name="type_id" class="form-control" required id="type_id">
                      <option></option>
                      @foreach($types as $type)
                      <option value="{{$type->id}}" @if($vehicle->type_id == $type->id) selected
                        @endif>{{$type->displayname}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group">
              <div class="col-xs-6">
              </div>
              </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  {!! Form::label('engine_type', __('fleet.engine'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    {!!
                    Form::select('engine_type',["Petrol"=>"Petrol","Diesel"=>"Diesel"],$vehicle->engine_type,['class' =>
                    'form-control','required']) !!}
                  </div>
                </div>
                <div class="form-group">
                  {!! Form::label('license_plate', __('fleet.licensePlate'), ['class' => 'col-xs-5 control-label']) !!}
                  <div class="col-xs-6">
                    {!! Form::text('license_plate', $vehicle->license_plate,['class' => 'form-control','required']) !!}
                  </div>
                </div>
                <div class="form-group">
              <div class="col-xs-6">
              </div>
              </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('driver_id', __('fleet.selectDriver'), ['class' => 'form-label']) !!}
                    <select id="driver_id" name="driver_id" class="form-control" required>
                      {{-- <option value="">@lang('fleet.selectDriver')</option> --}}
                      @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" @if($vehicle->getMeta('assign_driver_id') == $driver->id) selected @endif>
                          {{ $driver->name }} @if($driver->getMeta('is_active') != 1) ({{ __('fleet.in_active') }}) @endif
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    {!! Form::label('vowner_id', __('fleet.selectVowner'), ['class' => 'form-label']) !!}
                    <select id="vowner_id" name="vowner_id" class="form-control" required>
                      {{-- <option value="">@lang('fleet.selectVowner')</option> --}}
                      @foreach($vowners as $vowner)
                        <option value="{{ $vowner->id }}" @if($vehicle->getMeta('assign_vowner_id') == $vowner->id) selected @endif>
                          {{ $vowner->name }} @if($vowner->getMeta('is_active') != 1) ({{ __('fleet.in_active') }}) @endif
                        </option>
                      @endforeach
                    </select>
                  </div>
                <div class="form-group">
              </div>
            </div>
            <hr class="mt-0">
            <div class="blank"></div>
         

            <div style=" margin-bottom: 20px;">
              <div class="form-group" style="margin-top: 15px;">
                <div class="col-xs-6 col-xs-offset-3">
                  {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-warning']) !!}
                </div>
              </div>
            </div>
            {!! Form::close() !!}
          </div>
          
          <div class="tab-pane" id="driver">
      
    </div>

          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>


@endsection
@section("script")
<script type="text/javascript">
  $(".add_udf").click(function () {
    // alert($('#udf').val());
    var udf_validation = "@lang('fleet.Enter_field_name')";
    var field = $('#udf1').val();
    if(field == "" || field == null){
      alert(udf_validation);
    }
    else{
      $(".blank").append('<div class="row"><div class="col-md-4">  <div class="form-group"> <label class="form-label">'+ field.toUpperCase() +'</label> <input type="text" name="udf['+ field +']" class="form-control" placeholder="Enter '+ field +'" required></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('#udf1').val("");
    }
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
  $('#driver_id').select2({placeholder: "@lang('fleet.selectDriver')"});
  $('.select2_driver').select2({placeholder: "@lang('fleet.selectDriver')"});
  $('#group_id').select2({placeholder: "@lang('fleet.selectGroup')"});
  $('#type_id').select2({placeholder:"@lang('fleet.type')"});
  $('#make_name').select2({placeholder:"@lang('fleet.SelectVehicleMake')",tags:true});
  $('#color_name').select2({placeholder:"@lang('fleet.SelectVehicleColor')",tags:true});
  $('#model_name').select2({placeholder:"@lang('fleet.SelectVehicleModel')",tags:true});
  $('#make_name').on('change',function(){
        // alert($(this).val());
        $.ajax({
          type: "GET",
          url: "{{url('admin/get-models')}}/"+$(this).val(),
          success: function(data){
            var models =  $.parseJSON(data);
              $('#model_name').empty();
              $.each( models, function( key, value ) {
                $('#model_name').append($('<option>', {
                  value: value.id,
                  text: value.text
                }));
                $('#model_name').select2({placeholder:"@lang('fleet.SelectVehicleModel')",tags:true});
              });
          },
          dataType: "html"
        });
      });
  @if(isset($_GET['tab']) && $_GET['tab']!="")
    $('.nav-pills a[href="#{{$_GET['tab']}}"]').tab('show')
  @endif
  $('#start_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#end_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#exp_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#lic_exp_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#reg_exp_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $('#issue_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  $(document).on("click",".del_info",function(e){
    var hvk=confirm("Are you sure?");
    if(hvk==true){
      var vid=$(this).data("vehicle");
      var key = $(this).data('key');
      var action="{{ route('acquisition.index')}}/"+vid;
      $.ajax({
        type: "POST",
        url: action,
        data: "_method=DELETE&_token="+window.Laravel.csrfToken+"&key="+key+"&vehicle_id="+vid,
        success: function(data){
          $("#acq_table").empty();
          $("#acq_table").html(data);
          new PNotify({
            title: 'Deleted!',
            text:'@lang("fleet.deleted")',
            type: 'wanring'
          })
        }
        ,
        dataType: "HTML",
      });
    }
  });
  $("#add_form").on("submit",function(e){
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      success: function(data){
        $("#acq_table").empty();
        $("#acq_table").html(data);
        new PNotify({
          title: 'Success!',
          text: '@lang("fleet.exp_add")',
          type: 'success'
        });
        $('#exp_name').val("");
        $('#exp_amount').val("");
      },
      dataType: "HTML"
    });
    e.preventDefault();
  });
  // $("#accountForm").on("submit",function(e){
  //   $.ajax({
  //     type: "POST",
  //     url: $("#accountForm").attr("action"),
  //     data: new FormData(this),
  //     mimeType: 'multipart/form-data',
  //     contentType: false,
  //               cache: false,
  //               processData:false,
  //     success: new PNotify({
  //           title: 'Success!',
  //           text: '@lang("fleet.ins_add")',
  //           type: 'success'
  //       }),
  //   dataType: "json",
  //   });
  //   e.preventDefault();
  // });
  // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
  //   checkboxClass: 'icheckbox_flat-green',
  //   radioClass   : 'iradio_flat-green'
  // });
});
  // Initialize Select2 on your select boxes
// Listen for the select2:select event on the first select box
$('#make_name').on('select2:select', function(e) {
  // Clear the contents of the second select box
  $('#model_name').val(null).trigger('change');
  $('#color_name').val(null).trigger('change');
});

$('#exp_amount').on('input', function() {
    var inputValue = $(this).val();
    var decimalIndex = inputValue.indexOf('.');
    if (decimalIndex !== -1 && inputValue.length - decimalIndex > 3) {
        // Only allow up to 2 digits after the decimal point
        $(this).val(inputValue.substr(0, decimalIndex + 3));
    }
});

</script>
@endsection