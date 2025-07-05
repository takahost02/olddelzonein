@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/bootstrap-datetimepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route('bookings.index')}}">@lang('menu.bookings')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_booking')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_booking')
        </h3>
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
        <div class="alert alert-info hide fade in alert-dismissable" id="msg_driver" style="display: none;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
          Your current driver is not available in the chosen times. Available driver has been selected.
        </div>
        <div class="alert alert-info hide fade in alert-dismissable" id="msg_vehicle" style="display: none;">
          <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
          Your current vehicle is not available in the chosen times. Available vehicle has been selected.
        </div>
        {!! Form::open(['url' => url('admin/update/' . $data->id), 'method' => 'PATCH']) !!}
        {!! Form::hidden('user_id', Auth::user()->id) !!}
        {!! Form::hidden('id', $data->id) !!}
    
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('status', __('fleet.booking_status'), ['class' => 'form-label']) !!}
                    {!! Form::select('status', [
                        '' => '-- Select Status --',
                        '1' => 'Trip Given to Driver',
                        '2' => 'Driver on the Way',
                        '3' => 'Driver Reached Warehouse',
                        '4' => 'Loading Started',
                        '5' => 'Loading Done',
                        '6' => 'Unloading Started',
                        '7' => 'Trip Finished',
                        '8' => 'Driver Coming Back'
                    ], $data->status, ['class' => 'form-control', 'required']) !!}
                </div>
            </div>
        </div>

    
        <div class="col-md-12 mt-2">
            {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-warning']) !!}
        </div>
    {!! Form::close() !!}



      </div>
    </div>
  </div>
</div>
@endsection
