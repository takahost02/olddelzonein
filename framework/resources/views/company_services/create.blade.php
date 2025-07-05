@extends('layouts.app')
@section("breadcrumb")
<li class="breadcrumb-item">{{ link_to_route('company-services.index', __('fleet.companyServices'))}}</li>
<li class="breadcrumb-item active">@lang('fleet.addCompanyService')</li>
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">@lang('fleet.addCompanyService')</h3>
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

        {!! Form::open(['route' => 'company-services.store','method'=>'post','files'=>true,'id'=>'service-form','class' => 'form-reset']) !!}
        <div class="row">
          <div class="col-md-6">
           
            <div class="form-group">
              {!! Form::label('title', __('fleet.title'), ['class' => 'form-label']) !!}
              {!! Form::text('title', null,['class' => 'form-control','required']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('image', __('fleet.icon_img'), ['class' => 'form-label']) !!} (880px * 555px)
              <br>
              {!! Form::file('image',null,['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              {!! Form::label('description', __('fleet.description'), ['class' => 'form-label']) !!}
              {!! Form::textarea('description', null,['class' => 'form-control','required','size'=>'30x3']) !!}
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="form-group col-md-4">
            {!! Form::submit(__('fleet.submit'), ['class' => 'btn btn-success']) !!}
          </div>
        </div>
      </div>
      {!! Form::close() !!}
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




