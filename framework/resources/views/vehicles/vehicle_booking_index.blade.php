@extends('layouts.app')

@php($date_format_setting=(Hyvikk::get('date_format'))?Hyvikk::get('date_format'):'d-m-Y')
@section('extra_css')
<link rel="stylesheet" href="{{asset('assets/css/bootstrap-datepicker.min.css')}}">
<style type="text/css">
  .checkbox,
  #chk_all {
    width: 20px;
    height: 20px;
  }

/* The outer label */
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 26px;
}

/* Hide the default checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* Slider background */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  transition: 0.4s;
  border-radius: 34px;
}

/* The circle inside the switch */
.slider::before {
  position: absolute;
  content: "";
  height: 20px;
  width: 20px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.4s;
  border-radius: 50%;
}

/* When checked, move the circle to the right */
.switch input:checked + .slider {
  background-color: #4CAF50;
}

.switch input:checked + .slider::before {
  transform: translateX(24px);
}


</style>
@endsection
@section("breadcrumb")
<li class="breadcrumb-item active">@lang('menu.bookings')</li>
@endsection
@section('content')
@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="row">
  <div class="col-md-12">
    <div class="card card-info">
      <div class="card-header with-border">
        <h3 class="card-title"> @lang('fleet.manage_bookings') &nbsp;
          @can('Bookings add')<a href="{{route('bookings.create')}}" class="btn btn-success"
            title="@lang('fleet.new_booking')"><i class="fa fa-plus"></i></a>@endcan
        </h3>
      </div>

      <div class="card-body">
        <div class="table-responsive">
<table class="table table-responsive display" id="ajax_data_table" style="padding-bottom: 35px; width: 100%">
  <thead class="thead-inverse">
    <tr>
      <th><input type="checkbox" id="chk_all"></th>
      <th>@lang('fleet.pickup_addr')</th>
      <th>@lang('fleet.dropoff_addr')</th>
      <th>@lang('fleet.pickup')</th>
      <th>@lang('fleet.dropoff')</th>
      <th>@lang('fleet.Passengers')</th>
      <th>@lang('fleet.payment_status')</th>
      <th>@lang('fleet.booking_status')</th>
      <th>Booking Type</th>
      <th>@lang('fleet.amount')</th>
      <th>@lang('fleet.action')</th>
    </tr>
  </thead>
  <tbody></tbody>
  <tfoot>
    <tr>
      <th>
        @can('Bookings delete')
        <button class="btn btn-danger" id="bulk_delete" data-toggle="modal" data-target="#bulkModal" disabled title="@lang('fleet.delete')">
          <i class="fa fa-trash"></i>
        </button>
        @endcan
      </th>
      <th>@lang('fleet.pickup_addr')</th>
      <th>@lang('fleet.dropoff_addr')</th>
      <th>@lang('fleet.pickup')</th>
      <th>@lang('fleet.dropoff')</th>
      <th>@lang('fleet.Passengers')</th>
      <th>@lang('fleet.payment_status')</th>
      <th>@lang('fleet.booking_status')</th>
      <th>Booking Type</th>
      <th>@lang('fleet.amount')</th>
      <th>@lang('fleet.action')</th>
    </tr>
  </tfoot>
</table>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- cancel booking Modal -->
<div id="cancelBooking" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.cancel_booking')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_cancel')</p>
        {!! Form::open(['url'=>url('admin/cancel-booking'),'id'=>'cancel_booking']) !!}
        <div class="form-group">
          {!! Form::hidden('cancel_id',null,['id'=>'cancel_id']) !!}
          {!! Form::label('reason',__('fleet.addReason'),['class'=>"form-label"]) !!}
          <select name="reason" class="form-control vehicles" required>
            @foreach($reasons as $reason)
              <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
            @endforeach
          </select>
          {{-- {!! Form::text('reason',null,['class'=>"form-control",'required']) !!} --}}
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">@lang('fleet.submit')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!-- cancel booking Modal -->

<!-- complete journey Modal -->
<div id="journeyModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.complete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_journey')</p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-success" href="" id="journey_btn">@lang('fleet.submit')</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- complete journey Modal -->

<!-- bulk delete Modal -->
<div id="bulkModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        {!! Form::open(['url'=>'admin/delete-bookings','method'=>'POST','id'=>'form_delete']) !!}
        <div id="bulk_hidden"></div>
        <p>@lang('fleet.confirm_bulk_delete')</p>
      </div>
      <div class="modal-footer">
        <button id="bulk_action" class="btn btn-danger" type="submit" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!-- bulk delete Modal -->

<!-- single delete Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">@lang('fleet.delete')</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>@lang('fleet.confirm_delete')</p>

        <div class="show-content">

        </div>

      </div>
      <div class="modal-footer">
        <button id="del_btn" class="btn btn-danger" type="button" data-submit="">@lang('fleet.delete')</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('fleet.close')</button>
      </div>
    </div>
  </div>
</div>
<!-- single delete Modal -->


<!-- generate invoic Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="card card-info">
        <div class="modal-header">
          <h3 class="modal-title">@lang('fleet.add_payment')</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
        </div>

        <div class="fleet card-body">
          {!! Form::open(['route' => 'bookings.complete','method'=>'post']) !!}
          <input type="hidden" name="status" id="status" value="1" />
          <input type="hidden" name="booking_id" id="bookingId" value="" />
          <input type="hidden" name="userId" id="userId" value="" />
          <input type="hidden" name="customerId" id="customerId" value="" />
          <input type="hidden" name="vehicleId" id="vehicleId" value="" />
          <input type="hidden" name="type" id="type" value="" />
          <input type="hidden" name="base_km_1" value="" id="base_km_1">
          <input type="hidden" name="base_fare_1" value="" id="base_fare_1">
          <input type="hidden" name="wait_time_1" value="" id="wait_time_1">
          <input type="hidden" name="std_fare_1" value="" id="std_fare_1">
          <input type="hidden" name="base_km_2" value="" id="base_km_2">
          <input type="hidden" name="base_fare_2" value="" id="base_fare_2">
          <input type="hidden" name="wait_time_2" value="" id="wait_time_2">
          <input type="hidden" name="std_fare_2" value="" id="std_fare_2">
          <input type="hidden" name="base_km_3" value="" id="base_km_3">
          <input type="hidden" name="base_fare_3" value="" id="base_fare_3">
          <input type="hidden" name="wait_time_3" value="" id="wait_time_3">
          <input type="hidden" name="std_fare_3" value="" id="std_fare_3">
          @php($no_of_tax = 0)
          @if(Hyvikk::get('tax_charge') != "null")
          @php($no_of_tax = sizeof(json_decode(Hyvikk::get('tax_charge'), true)))
          @php($taxes = json_decode(Hyvikk::get('tax_charge'), true))
          @php($i=0)
          @foreach($taxes as $key => $val)
          <input type="hidden" name="{{ 'tax_'.$i }}" value="{{ $val }}" class="{{ 'tax_'.$i }}">
          @php($i++)
          @endforeach
          @endif
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.incomeType')</label>
                <select id="income_type" name="income_type" class="form-control vehicles" required>
                  <option value="">@lang('fleet.incomeType')</option>
                  @foreach($types as $type)
                  <option value="{{ $type->id }}">{{$type->name}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.daytype')</label>
                <select id="day" name="day" class="form-control vehicles" required>
                  <option value="1" selected>Weekdays</option>
                  <option value="2">Weekend</option>
                  <option value="3">Night</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.trip_mileage') ({{Hyvikk::get('dis_format')}})</label>
                {!! Form::number('mileage',null,['class'=>'form-control sum','min'=>1,'step' => '0.01','id'=>'mileage']) !!}
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.waitingtime')</label>
                {!! Form::number('waiting_time',0,['class'=>'form-control sum','min'=>0,'id'=>'waiting_time']) !!}
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.total_tax') (%) </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text fa fa-percent"></span>
                  </div>
                  {!! Form::number('total_tax_charge',0,['class'=>'form-control
                  sum','readonly','id'=>'total_tax_charge','min'=>0,'step'=>'0.01']) !!}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">@lang('fleet.total') @lang('fleet.tax_charge')</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">{{ Hyvikk::get('currency') }}</span>
                  </div>
                  {!! Form::number('total_tax_charge_rs',0,['class'=>'form-control
                  sum','readonly','id'=>'total_tax_charge_rs','min'=>0,'step'=>'0.01']) !!}
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">@lang('fleet.amount') </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">{{Hyvikk::get('currency')}}</span>
                  </div>
                  {!! Form::number('total',null,['class'=>'form-control','id'=>'total','required','min'=>1,
                  'step'=>'0.01']) !!}
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">@lang('fleet.total') @lang('fleet.amount') </label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">{{Hyvikk::get('currency')}}</span>
                  </div>
                  {!! Form::number('tax_total',null,['class'=>'form-control','id'=>'tax_total','readonly','min'=>0,
                  'step'=>'0.01']) !!}
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">@lang('fleet.date')</label>
                <div class='input-group'>
                  <div class="input-group-prepend">
                    <span class="input-group-text"> <span class="fa fa-calendar"></span></span>
                  </div>
                  {!! Form::text('date',date('Y-m-d'),['class'=>'form-control','id'=>'date']) !!}
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              {!! Form::submit(__('fleet.invoice'), ['class' => 'btn btn-info']) !!}
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
<!-- generate invoice modal -->
@endsection

@section("script")
<script type="text/javascript">
  @if(Session::get('msg'))
    new PNotify({
        title: 'Success!',
        text: '{{ Session::get('msg') }}',
        type: 'success'
      });
  @endif

  $(document).ready(function() {
    $('#date').datepicker({
      autoclose: true,
      format: 'yyyy-mm-dd'
    });
  });
</script>
<script type="text/javascript">
  $(document).on("click", ".open-AddBookDialog", function () {
    // alert($(this).data('base_km_1'));
    // window.open("route('bookings.index')/?type="+$(this).data('vehicle-type'));

    // const query = new URLSearchParams(window.location.search);
    // query.append("type", "true");

    // window.location.search = 'type='+$(".fleet #type").val( type );

     var booking_id = $(this).data('booking-id');

     $(".fleet #bookingId").val( booking_id );

     var user_id = $(this).data('user-id');
     $(".fleet #userId").val( user_id );

     var customer_id = $(this).data('customer-id');
     $(".fleet #customerId").val( customer_id );

     var vehicle_id = $(this).data('vehicle-id');
     $(".fleet #vehicleId").val( vehicle_id );

     var type = $(this).data('vehicle-type');
     $(".fleet #type").val( type );

     $(".fleet #mileage").val($(this).data('base-mileage'));

     
     $(".fleet #total").val($(this).data('base-fare'));

     $(".fleet #base_km_1").val($(this).data('base_km_1'));
     $(".fleet #base_fare_1").val($(this).data('base_fare_1'));
     $(".fleet #wait_time_1").val($(this).data('wait_time_1'));
     $(".fleet #std_fare_1").val($(this).data('std_fare_1'));
     $(".fleet #base_km_2").val($(this).data('base_km_2'));
     $(".fleet #base_fare_2").val($(this).data('base_fare_2'));
     $(".fleet #wait_time_2").val($(this).data('wait_time_2'));
     $(".fleet #std_fare_2").val($(this).data('std_fare_2'));
     $(".fleet #base_km_3").val($(this).data('base_km_3'));
     $(".fleet #base_fare_3").val($(this).data('base_fare_3'));
     $(".fleet #wait_time_3").val($(this).data('wait_time_3'));
     $(".fleet #std_fare_3").val($(this).data('std_fare_3'));

    var total = $("#total").val();

    var i;
    var tax_size = '{{ $no_of_tax }}';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge').val(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);

  });

  $("#del_btn").on("click",function(){
    var id=$(this).data("submit");

        if ($('.chk').is(':checked')) {
            $(".check").val(1);
        } else {
            $(".check").val(0);
        }

      $("#book_"+id).submit();
  });

  $('#myModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;

    var msg=e.relatedTarget.dataset.track;

    $(".show-content").html('');

    if (msg !== '') {
        $(".show-content").html(msg + `<br><br><label class="switch">
            <input type="checkbox" class="chk">
            <span class="slider"></span>
        </label>`);
    }

    $("#del_btn").attr("data-submit",id);
  });

  $('#journeyModal').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#journey_btn").attr("href","{{ url('admin/bookings/complete/') }}/"+id);
  });

    $('#cancelBooking').on('show.bs.modal', function(e) {
    var id = e.relatedTarget.dataset.id;
    $("#cancel_id").val(id);
  });
</script>

<!-- testing total-->
<script type="text/javascript" language="javascript">
  $(".sum").change(function(){
  // alert($("#base_km_1").val());
  // alert($('.vtype').data('base_km_1'));
  // console.log($("#type").val());

    var day = $("#day").find(":selected").val();
    if(day == 1){
      var base_km = $("#base_km_1").val();
      var base_fare = $("#base_fare_1").val();
      var wait_time = $("#wait_time_1").val();
      var std_fare = $("#std_fare_1").val();
        if(Number($("#mileage").val()) <= Number(base_km)){
          var total = Number(base_fare) + (Number($("#waiting_time").val()) * Number(wait_time));
        }
        else{
          var sum = Number($("#mileage").val() - base_km) * Number(std_fare);
      var total = Number(base_fare) + Number(sum) + (Number($("#waiting_time").val()) * Number(wait_time));
      }
    }

    if(day == 2){
      var base_km = $("#base_km_2").val();
      var base_fare = $("#base_fare_2").val();
      var wait_time = $("#wait_time_2").val();
      var std_fare = $("#std_fare_2").val();
        if(Number($("#mileage").val()) <= Number(base_km)){
          var total = Number(base_fare) + (Number($("#waiting_time").val()) * Number(wait_time));
        }
        else{
          var sum = Number($("#mileage").val() - base_km) * Number(std_fare);
      var total = Number(base_fare) + Number(sum) + (Number($("#waiting_time").val()) * Number(wait_time));
      }
    }

    if(day == 3){
      var base_km = $("#base_km_3").val();
      var base_fare = $("#base_fare_3").val();
      var wait_time =$("#wait_time_3").val();
      var std_fare = $("#std_fare_3").val();
        if(Number($("#mileage").val()) <= Number(base_km)){
          var total = Number(base_fare) + (Number($("#waiting_time").val()) * Number(wait_time));
        }
        else{
          var sum = Number($("#mileage").val() - base_km) * Number(std_fare);
      var total = Number(base_fare) + Number(sum) + (Number($("#waiting_time").val()) * Number(wait_time));
      }
    }
    $("#total").val(total);
    var i;
    var tax_size = '{{ $no_of_tax }}';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge').val(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);
});

  $("#total").change(function(){
    var total = $("#total").val();
    var i;
    var tax_size = '{{ $no_of_tax }}';
    var total_tax_val = 0;
    for (i = 0; i < tax_size; i++) {
      total_tax_val = Number(total_tax_val) + Number($('.tax_'+i).val());
      // console.log($('.tax_'+i).val());
    }
    // console.log(total_tax_val);
    $('#total_tax_charge_rs').val((Number(total)*Number(total_tax_val))/100);
    $('#tax_total').val(Number(total) + (Number(total)*Number(total_tax_val))/100);

  });
  
  $(document).on('click','input[type="checkbox"]',function(){
    if(this.checked){
      $('#bulk_delete').prop('disabled',false);

    }else { 
      if($("input[name='ids[]']:checked").length == 0){
        $('#bulk_delete').prop('disabled',true);
      } 
    } 
    
  });

$(function () {
  const tableElement = $('#ajax_data_table');

  if (!tableElement.length) {
    console.warn("Table element '#ajax_data_table' not found!");
    return;
  }

  const table = tableElement.DataTable({
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'print',
        text: '<i class="fa fa-print"></i> {{__("fleet.print")}}',
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
        },
        customize: function (win) {
          console.log("Print triggered");
          $(win.document.body).find('table').addClass('table-bordered');
        }
      },
      {
        extend: 'excel',
        text: '<i class="fa fa-file-excel-o"></i> Excel',
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
        }
      }
    ],
    language: {
      url: '{{ asset("assets/datatables/") . "/" . __("fleet.datatable_lang") }}',
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: "{{ url('admin/vehicles-bookings-fetch') }}",
      type: 'POST',
      data: function (d) {
        console.log("Sending AJAX request with data:", d);
        return d;
      },
      dataSrc: function (json) {
        console.log("Received AJAX response:", json);
        return json.data || [];
      },
      error: function (xhr, error, thrown) {
        console.error("AJAX Error:", error);
        console.error("XHR:", xhr);
        console.error("Thrown:", thrown);
      }
    },
    columns: [
  { data: 'check', name: 'check', searchable: false, orderable: false },
  { data: 'pickup_addr', name: 'pickup_addr' },
  { data: 'dest_addr', name: 'dest_addr' },
  { name: 'pickup', data: { _: 'pickup.display', sort: 'pickup.timestamp' } },
  { name: 'dropoff', data: { _: 'dropoff.display', sort: 'dropoff.timestamp' } },
  { data: 'travellers', name: 'travellers' },
  { data: 'payment', name: 'payment' },
  { data: 'ride_status', name: 'ride_status' },
  { data: 'tax_total', name: 'tax_total' },
  { data: 'booking_type', name: 'booking_type' },
  { data: 'action', name: 'action', searchable: false, orderable: false }
],

    order: [[1, 'desc']]
  });
});



  $('#bulk_delete').on('click',function(){
    // console.log($( "input[name='ids[]']:checked" ).length);
    if($( "input[name='ids[]']:checked" ).length == 0){
      $('#bulk_delete').prop('type','button');
        new PNotify({
            title: 'Failed!',
            text: "@lang('fleet.delete_error')",
            type: 'error'
          });
        $('#bulk_delete').attr('disabled',true);
    }
    if($("input[name='ids[]']:checked").length > 0){
      // var favorite = [];
      $.each($("input[name='ids[]']:checked"), function(){
          // favorite.push($(this).val());
          $("#bulk_hidden").append('<input type=hidden name=ids[] value='+$(this).val()+'>');
      });
      // console.log(favorite);
    }
  });


  $('#chk_all').on('click',function(){
    if(this.checked){
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",true);
      });
    }else{
      $('.checkbox').each(function(){
        $('.checkbox').prop("checked",false);
      });
      $('#bulk_delete').prop('disabled',true);
    }
  });

  // Checkbox checked
  function checkcheckbox(){
    // Total checkboxes
    var length = $('.checkbox').length;
    // Total checked checkboxes
    var totalchecked = 0;
    $('.checkbox').each(function(){
        if($(this).is(':checked')){
            totalchecked+=1;
        }
    });
    // console.log(length+" "+totalchecked);
    // Checked unchecked checkbox
    if(totalchecked == length){
        $("#chk_all").prop('checked', true);
    }else{
        $('#chk_all').prop('checked', false);
    }
  }
</script>
@endsection