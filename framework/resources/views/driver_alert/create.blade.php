@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route('vehicle-breakdown.index')}}">@lang('fleet.driver_alert')</a></li>
<li class="breadcrumb-item active">@lang('fleet.add_driver_alert')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.add_driver_alert')</h3>
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
        {!! Form::open(['route' => 'driver-alert.store','method'=>'post','class' => 'form-reset']) !!}
        {!! Form::hidden('user_id',Auth::user()->id)!!}
        <div class="row">
            <div class="col-md-6">
                
                <label>@lang('fleet.name')</label>
                <div class="form-group">
                    <input type="text" name="name" class="form-control">

                </div>
                
            </div>
         </div>
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.add_driver_alert'), ['class' => 'btn btn-success']) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')

<script>
  $(document).ready(function() {
    $(".form-reset").on("submit", function(event) {
        $('input[type="submit"]').prop('disabled', true);
    });
  });
</script>

@endsection
