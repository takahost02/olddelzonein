<div class="btn-group">
  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
    <span class="fa fa-gear"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <div class="dropdown-menu custom" role="menu">
    @if($row->status==0 && $row->ride_status != "Cancelled")

    @if(isset($row->booking_type) && $row->booking_type == "return_way")
      @can('Bookings edit')

      @php

      $p = \App\Model\Bookings::select("bookings.*")->where('id',$row->parent_booking_id)->first();
      @endphp

      @if(isset($p))

      <a class="dropdown-item" href="{{ url('admin/bookings/'.$row->parent_booking_id.'/edit')}}">
          <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')
      </a>
      @else
      <a class="dropdown-item" href="{{ url('admin/bookings/'.$row->id.'/edit')}}">
          <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')
      </a>
      @endif


      @endcan
  @else
      <a class="dropdown-item" href="{{ url('admin/bookings/'.$row->id.'/edit')}}">
          <span aria-hidden="true" class="fa fa-edit" style="color: #f0ad4e;"></span> @lang('fleet.edit')
      </a>
  @endif


    @if($row->receipt != 1)
    <a class="dropdown-item vtype" data-id="{{$row->id}}" data-toggle="modal" data-target="#cancelBooking" > <span class="fa fa-times" aria-hidden="true" style="color: #dd4b39"></span> @lang('fleet.cancel_booking')</a>
    @endif
    @endif
    {{-- @if(Auth::user()->id == 1) --}}
    
    @can('Bookings delete')
    @php
      $trackMessage = '';
      $b = \App\Model\Bookings::where('id', $row->parent_booking_id)->first();
  
      if ($b) {
          $trackMessage = 'This booking is part of a return trip. Do you want to remove the parent booking too?';
      } else {
          $d = \App\Model\Bookings::join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')
              ->where('bookings_meta.key', 'parent_booking_id')
              ->where('bookings_meta.value', $row->id)
              ->first();
  
          if ($d) {
              $trackMessage = 'This booking is part of a return trip. Do you want to remove the child booking too?';
          }
          else {
            $trackMessage ="";
          }
      }
  @endphp

      <a class="dropdown-item vtype" data-id="{{ $row->id }}"
        data-track="{{ $trackMessage }}"
        data-toggle="modal" data-target="#myModal">
          <span class="fa fa-trash" aria-hidden="true" style="color: #dd4b39"></span> 
          @lang('fleet.delete')
      </a>

    @endcan


    {{-- @endif --}}
    @if($row->vehicle_id != null)

   

    @if($row->status==0 && $row->receipt != 1)

    
    @if(Auth::user()->user_type != "C" && !in_array($row->ride_status, ["Cancelled", "Pending"]))
    <a data-toggle="modal" data-target="#receiptModal" class="open-AddBookDialog dropdown-item" data-booking-id="{{$row->id}}" 
      data-user-id="{{$row->user_id}}" 
      data-customer-id="{{$row->customer_id}}" 
      data-vehicle-id= "{{$row->vehicle_id}}" 
      data-vehicle-type="{{strtolower(str_replace(' ','',$row->vehicle->types->vehicletype))}}" 
      data-base-mileage="{{($row->total_kms)?$row->total_kms:Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_base_km')}}" 

      @if(Hyvikk::get('fare_mode')=="type_wise")

    
      @php

        if(isset($row->vehicle_id))
        {
          $vehicle=\App\Model\VehicleModel::where('id',$row->vehicle_id)->first();

          $v_type=\App\Model\VehicleTypeModel::where('id',$vehicle->type_id)->first();

            $type = strtolower(str_replace(" ", "", $v_type->vehicletype));

            $base_fare = Hyvikk::fare($type . '_base_fare');
            $km_base = Hyvikk::fare($type . '_base_km');
            $std_fare = Hyvikk::fare($type . '_std_fare');

            if ($row->total_kms <= $km_base) {
                $total_fare = $base_fare;
            } else {
                $total_fare = $base_fare + (($row->total_kms - $km_base) * $std_fare);
            }
        }

      
      @endphp



      data-base-fare="{{($total_fare) ? $total_fare : Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_base_fare')}}"

      @elseif(Hyvikk::get('fare_mode')=="price_wise")
        
          data-base-fare="{{$row->vehicle->price}}"

      @endif
      

    data-base_km_1="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_base_km')}}"
    data-base_fare_1="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_base_fare')}}"
    data-wait_time_1="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_base_time')}}"
    data-std_fare_1="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_std_fare')}}"

    data-base_km_2="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_weekend_base_km')}}"
    data-base_fare_2="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_weekend_base_fare')}}"
    data-wait_time_2="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_weekend_wait_time')}}"
    data-std_fare_2="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_weekend_std_fare')}}"

    data-base_km_3="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_night_base_km')}}"
    data-base_fare_3="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_night_base_fare')}}"
    data-wait_time_3="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_night_wait_time')}}"
    data-std_fare_3="{{Hyvikk::fare(strtolower(str_replace(' ','',$row->vehicle->types->vehicletype)).'_night_std_fare')}}"
    ><span aria-hidden="true" class="fa fa-file" style="color: #5cb85c;">

    </span> @lang('fleet.invoice')
    </a>
    @endif
    
    @elseif($row->receipt == 1)
    <a class="dropdown-item" href="{{ url('admin/bookings/receipt/'.$row->id)}}"><span aria-hidden="true" class="fa fa-list" style="color: #31b0d5;"></span> @lang('fleet.receipt')
    </a>
    @if($row->receipt == 1 && $row->status == 0 && Auth::user()->user_type != "C")
    <a class="dropdown-item" href="{{ url('admin/bookings/complete/'.$row->id)}}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#journeyModal"><span aria-hidden="true" class="fa fa-check" style="color: #5cb85c;"></span> @lang('fleet.complete')
    </a>
    @endif
    @endif
    @endif

    @if($row->status==1)
    @if($row->payment==0 && Auth::user()->user_type !="C")
    <a class="dropdown-item" href="{{ url('admin/bookings/payment/'.$row->id)}}"><span aria-hidden="true" class="fa fa-credit-card" style="color: #5cb85c;"></span> @lang('fleet.make_payment')
    </a>
    @elseif($row->payment==1)
    <a class="dropdown-item text-muted" class="disabled"><span aria-hidden="true" class="fa fa-credit-card" style="color: #5cb85c;"></span> @lang('fleet.paid')
    </a>
    @endif
    @endif
  </div>
</div>
{!! Form::open(['url' => 'admin/bookings/'.$row->id,'method'=>'DELETE','class'=>'form-horizontal','id'=>'book_'.$row->id]) !!}
{!! Form::hidden("id",$row->id) !!}

<input type="hidden" name="check" class="check">

{!! Form::close() !!}