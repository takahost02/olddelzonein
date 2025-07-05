@extends('layouts.app')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route('vehicle-breakdown.index')}}">@lang('fleet.vehicle_breakdown')</a></li>
<li class="breadcrumb-item active">@lang('fleet.edit_vehicle_breakdown')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.edit_vehicle_breakdown')</h3>
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
        {!! Form::open(['route' => ['vehicle-breakdown.update',$data->id],'method'=>'PATCH']) !!}
        <input type="hidden" name="id" value="{{$data->id}}">
        <div class="row">
            <div class="col-md-6">
                
                <label>@lang('fleet.name')</label>
                <div class="form-group">
                    <input type="text" name="name" class="form-control" value="{{$data->name}}">

                </div>
                
            </div>
         </div>
        <div class="row">
          <div class="col-md-12">
            {!! Form::submit(__('fleet.update'), ['class' => 'btn btn-success']) !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
