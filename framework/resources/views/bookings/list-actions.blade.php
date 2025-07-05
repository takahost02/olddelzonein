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