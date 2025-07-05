@extends('layouts.app')
@section('extra_css')
<style>
  .layouthack {
    margin-top: 0px;
  }

  @media only screen and (min-width: 768px) {
    .layouthack {
      margin-top: 76px;
    }
  }
</style>
@endsection

@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route('parts.index')}}">@lang('menu.manageParts')</a></li>
<li class="breadcrumb-item active">@lang('fleet.addParts')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.addParts')</h3>
      </div>
      <div class="card-body">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        {!! Form::open(['route' => 'parts.store','method'=>'post','files'=>true,'class' => 'form-reset']) !!}
        {!! Form::hidden("user_id",Auth::user()->id) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('image', __('fleet.picture'), ['class' => 'form-label']) !!}
              <br>
              {!! Form::file('image',null,['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('barcode', __('fleet.barcode'), ['class' => 'form-label']) !!}
              {!! Form::text('barcode', null,['class' => 'form-control','required']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
              {!! Form::text('title', null,['class' => 'form-control','required']) !!}
            </div>
            <!-- <div class="form-group">
              {!! Form::label('status',__('fleet.status'), ['class' => 'form-label']) !!}
              {!! Form::select('status',["Active"=>__('fleet.active'),"Pending"=>__('fleet.pending'), "Processing"=>__('fleet.Processing'),
              "Completed"=>__('fleet.Completed'),"Hold"=>__('fleet.Hold')],null,['class' => 'form-control','required']) !!}
            </div> -->
            <div class="form-group">
              {!! Form::label('stock', __('fleet.qty_on_hand'), ['class' => 'form-label']) !!}
              {!! Form::number('stock', null,['class' => 'form-control','required']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('note',__('fleet.note'), ['class' => 'form-label']) !!}
              {!! Form::textarea('note',null,['class'=>'form-control','size'=>'30x1']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group pb-1">
              {!! Form::label('availability', __('fleet.availability') , ['class' => 'form-label']) !!}<br>
              <input type="radio" name="availability" class="flat-red gender" value="1" checked>
              @lang('fleet.available') &nbsp; &nbsp;
              <input type="radio" name="availability" class="flat-red gender" value="0"> @lang('fleet.not_available')
            </div>
            <div class="form-group">
              {!! Form::label('vendor_id',__('fleet.vendor'), ['class' => 'form-label']) !!}
              <select id="vendor_id" name="vendor_id" class="form-control" required>
                <option value="">-</option>
                @foreach($vendors as $vendor)
                <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              {!! Form::label('category_id',__('fleet.parts_category'), ['class' => 'form-label']) !!}
              <select id="category_id" name="category_id" class="form-control" required>
                <option value="">-</option>
                @foreach($categories as $cat)
                <option value="{{$cat->id}}">{{$cat->name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              {!! Form::label('unit_cost', __('fleet.unit_cost'), ['class' => 'form-label']) !!}
              <div class="input-group date">
                <div class="input-group-prepend">
                  <span class="input-group-text">{{Hyvikk::get('currency')}}</span>
                </div>
                {!! Form::number('unit_cost', null,['class' => 'form-control','required']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('manufacturer', __('fleet.manufacturer'), ['class' => 'form-label']) !!}
              {!! Form::text('manufacturer', null,['class' => 'form-control', 'id' => 'manufacturer-input']) !!}
            </div>
          </div>

          <div class="col-md-4">
            <div class="layouthack"></div>
            <div class="form-group">
              {!! Form::label('number', __('fleet.number'), ['class' => 'form-label']) !!}
              {!! Form::text('number', null,['class' => 'form-control','id' => 'number-input','required']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('description',__('fleet.description'), ['class' => 'form-label']) !!}
              {!! Form::text('description',null,['class'=>'form-control','required']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('year', __('fleet.year1'), ['class' => 'form-label']) !!}
              {!! Form::text('year', null,['class' => 'form-control','required']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('model', __('fleet.part_model'), ['class' => 'form-label']) !!}
              {!! Form::text('model', null,['class' => 'form-control','required']) !!}
            </div>
          </div>

        </div>

        <hr>
        <div class="form-group col-md-6">
          {!! Form::label('udf1',__('fleet.add_udf'), ['class' => 'col-xs-5 control-label']) !!}
          <div class="row">
            <div class="col-md-8">
              {!! Form::text('udf1', null,['class' => 'form-control']) !!}
            </div>
            <div class="col-md-4">
              <button type="button" class="btn btn-info add_udf"> @lang('fleet.add')</button>
            </div>
          </div>
        </div>
        <div class="blank"></div>

        <div class="col-md-12">
          {!! Form::submit(__('fleet.savePart'), ['class' => 'btn btn-success']) !!}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function() {
    $('#manufacturer-input').on('input', function() {
        var inputVal = $(this).val();
        var validInput = /^[A-Za-z]+$/.test(inputVal);
        if (!validInput) {
            // If the input is not a valid string, clear the input
            $(this).val('');
        }
    });
    $('#number-input').on('input', function() {
        var inputVal = $(this).val();
        var validInput = /^\d+$/.test(inputVal);
        if (!validInput) {
            // If the input is not a valid number, clear the input
            $(this).val('');
        }
    });
});
  $("#vendor_id").select2({placeholder:"@lang('fleet.select_vendor')"});
  $("#category_id").select2({placeholder:"@lang('fleet.parts_category')"});
  $(".add_udf").click(function () {
    // alert($('#udf').val());
    var field = $('#udf1').val();
    if(field == "" || field == null){
      alert('Enter field name');
    }
    else{
      $(".blank").append('<div class="row"><div class="col-md-4">  <div class="form-group"> <label class="form-label">'+ field.toUpperCase() +'</label> <input type="text" name="udf['+ field +']" class="form-control" placeholder="Enter '+ field +'" required></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
      $('#udf1').val("");
    }
  });
  $('#year').on('input', function(evt) {
    var inputVal = $(this).val();
    var cleanedVal = inputVal.replace(/[^0-9.]/g, '').replace(/^0+/, '');
    if (cleanedVal.length > 4) {
      cleanedVal = cleanedVal.slice(0, 4);
    }
    $(this).val(cleanedVal);
  });
</script>


<script>
  $(document).ready(function() {
      $(".form-reset").on("submit", function(event) {
          $('input[type="submit"]').prop('disabled', true);
      });
    });
  </script>
@endsection

