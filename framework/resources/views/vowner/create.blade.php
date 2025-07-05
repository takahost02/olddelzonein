@extends('layouts.app')
@section('extra_css')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  .select2-selection:not(.select2-selection--multiple) {
    height: 38px !important;
  }

  .input-group-append,
  .input-group-prepend {
    display: flex;
    /* width: calc(100% / 2); */
  }
</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ route('vowner.index')}}">@lang('fleet.vowner')</a></li>
<li class="breadcrumb-item active">@lang('fleet.addVowner')</li>
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-success">
      <div class="card-header with-border">
        <h3 class="card-title">@lang('fleet.addVowner')</h3>
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

        {!! Form::open(['route' => 'vowner.store','files'=>true,'method'=>'post','id'=>'driver-create-form']) !!}
        {!! Form::hidden('is_active',1) !!}
        {!! Form::hidden('is_available',0) !!}
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('first_name', __('fleet.firstname'), ['class' => 'form-label required','autofocus']) !!}
              {!! Form::text('first_name', null,['class' => 'form-control','required','autofocus']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('middle_name', __('fleet.middlename'), ['class' => 'form-label']) !!}
              {!! Form::text('middle_name', null,['class' => 'form-control']) !!}
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('last_name', __('fleet.lastname'), ['class' => 'form-label required']) !!}
              {!! Form::text('last_name', null,['class' => 'form-control','required']) !!}
            </div>
          </div>
        </div>
        <div class="row">
          
         

<div class="col-md-3">
  <div class="form-group">
    {!! Form::label('bank_name', 'Bank Name', ['class' => 'form-label required']) !!}
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-university"></i></span>
      </div>
      {!! Form::text('bank_name', null, ['class' => 'form-control', 'required']) !!}
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    {!! Form::label('account_number', 'Account Number', ['class' => 'form-label required']) !!}
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
      </div>
      {!! Form::text('account_number', null, ['class' => 'form-control', 'required']) !!}
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    {!! Form::label('ifsc_code', 'IFSC Code', ['class' => 'form-label required']) !!}
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-code"></i></span>
      </div>
      {!! Form::text('ifsc_code', null, ['class' => 'form-control', 'required']) !!}
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    {!! Form::label('branch_name', 'Branch Name', ['class' => 'form-label required']) !!}
    <div class="input-group">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-bank"></i></span>
      </div>
      {!! Form::text('branch_name', null, ['class' => 'form-control', 'required']) !!}
    </div>
  </div>
</div>


<div class="col-md-3">
  <div class="form-group">
    {!! Form::label('pin_code', 'Pin Code', ['class' => 'form-label required']) !!}
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
      </div>
      {!! Form::text('pin_code', null, ['class' => 'form-control', 'required']) !!}
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    {!! Form::label('city', 'City', ['class' => 'form-label required']) !!}
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-building"></i></span>
      </div>
      {!! Form::text('city', null, ['class' => 'form-control', 'required']) !!}
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    {!! Form::label('route_from', 'Route From', ['class' => 'form-label required']) !!}
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-location-arrow"></i></span>
      </div>
      {!! Form::text('route_from', null, ['class' => 'form-control', 'required']) !!}
    </div>
  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    {!! Form::label('route_to', 'Route To', ['class' => 'form-label required']) !!}
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text"><i class="fa fa-flag-checkered"></i></span>
      </div>
      {!! Form::text('route_to', null, ['class' => 'form-control', 'required']) !!}
    </div>
  </div>
</div>



          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('email', __('fleet.email'), ['class' => 'form-label required']) !!}
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                </div>
                {!! Form::email('email', null,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('phone', __('fleet.phone'), ['class' => 'form-label required']) !!}
              <div class="input-group">
                <div class="input-group-prepend">
                  {!! Form::select('phone_code',$phone_code,null,['class' => 'form-control
                  code','required','style'=>'width:80px']) !!}
                </div>
                {!! Form::number('phone', null,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('emp_id', __('fleet.employee_id'), ['class' => 'form-label']) !!}
                {!! Form::text('emp_id', $emp_id ?? '', ['class' => 'form-control', 'readonly']) !!}
              </div>
          </div>

          <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('contract_number', __('fleet.contract'), ['class' => 'form-label']) !!}
                {!! Form::text('contract_number', null, ['class' => 'form-control','required']) !!}
                <small id="contract_number_error" class="text-danger"></small>
                @error('contract_number')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('license_number', __('fleet.aadharNumber'), ['class' => 'form-label required']) !!}
                {!! Form::text('license_number', null, ['class' => 'form-control', 'required', 'id' => 'license_number']) !!}
                <small id="license_number_error" class="text-danger"></small>
                @error('license_number')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>
            </div>
            
            



          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('issue_date', __('fleet.issueDate'), ['class' => 'form-label']) !!}
              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
                {!! Form::text('issue_date', null,['class' => 'form-control','required','id' => 'issue_date']) !!}
              </div>
            </div>
          </div>



          <div class="col-md-4">
          <div class="form-group">
            {!! Form::label('exp_date', __('fleet.expirationDate'), ['class' => 'form-label required']) !!}
            <div class="input-group date">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
              </div>
              {!! Form::text('exp_date', null, ['class' => 'form-control' . ($errors->has('exp_date') ? ' is-invalid' : ''), 'required']) !!}
            </div>
            @error('exp_date')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>

        </div>
        
        
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('start_date', __('fleet.join_date'), ['class' => 'form-label']) !!}
              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
                {!! Form::text('start_date', null,['class' => 'form-control','required']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('end_date', __('fleet.avlidatedate'), ['class' => 'form-label']) !!}
              <div class="input-group date">
                <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-calendar"></i></span>
                </div>
                {!! Form::text('end_date', null,['class' => 'form-control']) !!}
              </div>
            </div>
          </div>
          <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('password', __('fleet.password'), ['class' => 'form-label']) !!}
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                  </div>
                  {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'required']) !!}
                </div>
              </div>
            </div>
            
            <div class="col-md-4">
              <div class="form-group">
                {!! Form::label('confirm_password', 'Confirm Password', ['class' => 'form-label']) !!}
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                  </div>
                  {!! Form::password('confirm_password', ['class' => 'form-control', 'id' => 'confirm_password', 'required']) !!}
                </div>
                <small id="password_error" class="text-danger d-none">Passwords do not match.</small>
              </div>
            </div>
            
            <script>
            document.addEventListener('DOMContentLoaded', function () {
              const form = document.querySelector('form');
              const password = document.getElementById('password');
              const confirmPassword = document.getElementById('confirm_password');
              const errorText = document.getElementById('password_error');
            
              form.addEventListener('submit', function (e) {
                if (password.value !== confirmPassword.value) {
                  e.preventDefault();
                  errorText.classList.remove('d-none');
                } else {
                  errorText.classList.add('d-none');
                }
              });
            });
            </script>

        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              {!! Form::label('driver_commision_type', __('fleet.vowner_commision_type'), ['class' => 'form-label']) !!}
              
                {!! Form::select('driver_commision_type',['amount'=>__('fleet.amount'), 'percent'=> __('fleet.percent')],null,['class' => 'form-control', 'placeholder' =>__('fleet.select'), 'required']) !!}            
            </div>
          </div>
          <div class="col-md-4" id="driver_commision_container" style="display: none;">
            <div class="form-group">
              {!! Form::label('driver_commision', __('fleet.vowner_commision'), ['class' => 'form-label']) !!}              
                {!! Form::number('driver_commision',null,['class' => 'form-control']) !!}            
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('gender', __('fleet.gender') , ['class' => 'form-label']) !!}<br>
              <input type="radio" name="gender" class="flat-red gender" value="1" checked> @lang('fleet.male')<br>

              <input type="radio" name="gender" class="flat-red gender" value="0"> @lang('fleet.female')
            </div>

            <div class="form-group">
              {!! Form::label('driver_image', __('fleet.vownerImage'), ['class' => 'form-label']) !!}

              {!! Form::file('driver_image',null,['class' => 'form-control','required']) !!}
            </div>
            <div class="form-group">
              {!! Form::label('documents', __('fleet.documents'), ['class' => 'form-label']) !!}
              {!! Form::file('documents',null,['class' => 'form-control','required']) !!}
            </div>


            <div class="form-group">
              {!! Form::label('license_image', __('fleet.aadharImage'), ['class' => 'form-label']) !!}
              {!! Form::file('license_image',null,['class' => 'form-control','required']) !!}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              {!! Form::label('econtact', __('fleet.emergency_details'), ['class' => 'form-label']) !!}
              {!! Form::textarea('econtact',null,['class' => 'form-control']) !!}
            </div>
          </div>
        </div>
        <div class="col-md-12">
          {!! Form::submit(__('fleet.saveDriver'), ['class' => 'btn btn-success']) !!}
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endsection

@section("script")
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#driver_commision_type').on('change', function(){
      var val = $(this).val();
      if(val==''){
        $('#driver_commision_container').hide();
      }else{
        if(val =='amount'){
          $('#driver_commision').attr('placeholder',"@lang('fleet.enter_amount')");
        }else{
          $('#driver_commision').attr('placeholder',"@lang('fleet.enter_percent')")
        }
        $('#driver_commision_container').show();
      }
    });


    if($('#driver_commision').val()  != "")
    {
      $('#driver_commision_container').show();
    }

    
    $('.code').select2();
    $('#vehicle_id').select2({
      placeholder:"@lang('fleet.selectVehicle')"
    });
    
    $("#first_name").focus();
    $('#end_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    }).on('show', function() {
    var pickupdate = $( "#start_date" ).datepicker('getDate');
    if (pickupdate) {
      // $("#end_date").datepicker('setStartDate', pickupdate);
    }
  
  });
  //   $('#exp_date').datepicker({
  //     autoclose: true,
  //     format: 'yyyy-mm-dd'
  //   }).on('show', function() {
  //   var pickupdate = $( "#issue_date" ).datepicker('getDate');
  //   if (pickupdate) {
  //     $("#exp_date").datepicker('setStartDate', pickupdate);
  //   }
  // });
  //   $('#issue_date').datepicker({
  //     autoclose: true,
  //     format: 'yyyy-mm-dd',
  //     endDate: new Date() 
  //   });



            $('#issue_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                startView: 2,
                minViewMode: 0
            }).on('changeDate', function (e) {
                var startDate = e.date;
                $('#exp_date').datepicker('setStartDate', startDate);
                $('#exp_date').val(''); // Reset end_date if it's before new start_date
            });

            $('#exp_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                startView: 2,
                minViewMode: 0
            });

      


    $('#start_date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });

    $("#driver-create-form").validate({
      // in 'rules' user have to specify all the constraints for respective fields
      rules: {        
        password: {
          required:true,
          minlength: 6
        }
      },
      // in 'messages' user have to specify message as per rules
      messages: {
        vehicle_id: "Assign Vehicle field is required.",           
      },
      errorPlacement: function (error, element) {
        if(element.hasClass('select2-hidden-accessible') && element.next('.select2-container').length) {
            error.insertAfter(element.next('.select2-container'));
        }else if (element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        }
        else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
            error.insertAfter(element.parent().parent());
        }
        else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
            error.appendTo(element.parent().parent());
        }
        else {
            error.insertAfter(element);
        }
      },
      highlight: function (element, errorClass, validClass) {
        if($(element).hasClass('select2-hidden-accessible') && $(element).next('.select2-container').length) {
          
          $(element).next('.select2-container').find('.select2-selection').addClass('border-danger');
        }else{

        $(element).addClass('is-invalid');     
        }
        // return false;
      },
      unhighlight: function (element, errorClass, validClass) {
        if($(element).hasClass('select2-hidden-accessible') && $(element).next('.select2-container').length) {
        console.log(element, errorClass, validClass)

          $(element).next('.select2-container').find('.select2-selection').removeClass('border-danger');
        }else{
          $(element).removeClass('is-invalid');
        }
      }
    });
    
    //Flat red color scheme for iCheck
    // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
    //   checkboxClass: 'icheckbox_flat-green',
    //   radioClass   : 'iradio_flat-green'
    // })
  });
</script>

<script>
$(document).ready(function () {
    $('#license_number').on('keyup change', function () {
        let license_number = $(this).val();
        let user_id = '{{ isset($user) ? $user->id : '' }}'; // for update cases

        if (license_number.length > 4) { // optional length check
            $.ajax({
                url: '{{ route('vowner.checkLicenseNumber') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    license_number: license_number,
                    id: user_id
                },
                success: function (response) {
                    if (response.exists) {
                        $('#license_number_error').text('This Aadhar number already exists.');
                    } else {
                        $('#license_number_error').text('');
                    }
                }
            });
        } else {
            $('#license_number_error').text('');
        }
    });
});
</script>



<script>
let metaValidationState = {
    license_number: true,
    contract_number: true,
};

function checkUnique(key, value, fieldId) {
    console.log('Checking uniqueness for:', key, 'with value:', value);

    $.post("{{ route('check.meta.unique') }}", {
        key: key,
        value: value,
        _token: '{{ csrf_token() }}'
    }, function (data) {
        console.log('Response from server for key:', key, data);

        let message = '';
        if (!data.unique) {
            if (key === 'license_number') {
                message = 'Aadhar number already exists.';
            } else if (key === 'contract_number') {
                message = 'Contract number already exists.';
            } else {
                message = key.replace('_', ' ') + ' already exists.';
            }
            $('#' + fieldId + '_error').text(message);
            metaValidationState[key] = false;
        } else {
            $('#' + fieldId + '_error').text('');
            metaValidationState[key] = true;
        }

        toggleSubmitButton();
    }).fail(function(xhr, status, error) {
        console.error('AJAX request failed:', status, error);
        console.log(xhr.responseText);
    });
}

function toggleSubmitButton() {
    const allValid = Object.values(metaValidationState).every(Boolean);
    $('#submit_btn').prop('disabled', !allValid);
}

// Usage on blur
$('#license_number').on('blur', function () {
    console.log('License number blur event triggered.');
    checkUnique('license_number', $(this).val(), 'license_number');
});

$('#contract_number').on('blur', function () {
    console.log('Contract number blur event triggered.');
    checkUnique('contract_number', $(this).val(), 'contract_number');
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
  const expDateInput = document.getElementById('exp_date');
  
  function validateExpDate() {
    const errorElemId = 'exp_date_error';
    let errorElem = document.getElementById(errorElemId);
    if (!errorElem) {
      // Create error element if doesn't exist
      errorElem = document.createElement('small');
      errorElem.id = errorElemId;
      errorElem.classList.add('text-danger');
      expDateInput.parentNode.parentNode.appendChild(errorElem);
    }
    
    const inputDate = new Date(expDateInput.value);
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    if (isNaN(inputDate.getTime())) {
      errorElem.textContent = 'Please enter a valid date.';
      expDateInput.classList.add('is-invalid');
      return false;
    }
    
    if (inputDate <= tomorrow) {
      errorElem.textContent = 'Expiration date must be after tomorrow.';
      expDateInput.classList.add('is-invalid');
      return false;
    }
    
    // No error
    errorElem.textContent = '';
    expDateInput.classList.remove('is-invalid');
    return true;
  }
  
  // Validate on blur and input
  expDateInput.addEventListener('blur', validateExpDate);
  expDateInput.addEventListener('input', validateExpDate);
});

</script>



@endsection


