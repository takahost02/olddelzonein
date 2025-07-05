@extends('layouts.app')

@php
    $date_format_setting = Hyvikk::get('date_format') ?? 'd-m-Y';
@endphp

@section('extra_css')
<style type="text/css">
.nav-tabs-custom>.nav-tabs>li.active {
  border-top-color: #3c8dbc !important;
}
.custom_color.active {
  color: #fff;
  background-color: #02bcd1 !important;
}
</style>
@endsection

@section("breadcrumb")
<li class="breadcrumb-item"><a href="{{ url('admin/') }}">@lang('fleet.home')</a></li>
<li class="breadcrumb-item active">@lang('fleet.myProfile')</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header p-2">
        <ul class="nav nav-pills">
          <li class="nav-item"><a class="nav-link custom_color active" href="#activity" data-toggle="tab">@lang('fleet.activity')</a></li>
          <li class="nav-item"><a class="nav-link custom_color" href="#upcoming" data-toggle="tab">@lang('fleet.upcoming')</a></li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content">
          {{-- Activity Tab --}}
          <div class="active tab-pane" id="activity">
            <h4>@lang('menu.my_bookings')</h4>
            <div class="table-responsive">
              <table class="table driver_table">
                <thead class="thead-inverse">
                  <tr>
                    <th>@lang('fleet.customer')</th>
                    <th>@lang('fleet.vehicle')</th>
                    <th>@lang('fleet.pickup')</th>
                    <th>@lang('fleet.dropoff')</th>
                    <th>@lang('fleet.pickup_addr')</th>
                    <th>@lang('fleet.dropoff_addr')</th>
                    <th>@lang('fleet.passengers')</th>
                    <th>@lang('fleet.booking_status')</th>
                    <th>Booking Type</th>
                    <th>@lang('fleet.action')</th>
                  </tr>
                </thead>
                <tbody>
                    @php
                        $statusMap = [
                            1 => 'Trip Given to Driver',
                            2 => 'Driver on the Way',
                            3 => 'Driver Reached Warehouse',
                            4 => 'Loading Started',
                            5 => 'Loading Done',
                            6 => 'Unloading Started',
                            7 => 'Trip Finished',
                            8 => 'Driver Coming Back',
                        ];
                    @endphp

                  @foreach($bookings as $row)
                    @if($row->getMeta('ride_status') == "Completed" || $row->status == 1)
                      <tr>
                        <td>{{ $row->customer->name }}</td>
                        <td>{{ $row->vehicle->make_name }} - {{ $row->vehicle->model_name }} - {{ $row->vehicle['license_plate'] }}</td>
                        <td>
                          @if($row->pickup)
                            {{ date($date_format_setting.' g:i A', strtotime($row->pickup)) }}
                          @endif
                        </td>
                        <td>
                          @if($row->dropoff)
                            {{ date($date_format_setting.' g:i A', strtotime($row->dropoff)) }}
                          @endif
                        </td>
                        <td>{{ $row->pickup_addr }}</td>
                        <td>{{ $row->dest_addr }}</td>
                        <td>{{ $row->travellers }}</td>
                        <td>{{ $statusMap[$row->status] ?? 'Unknown' }}</td>
                        <td>
                          @php
                            $bookingType = $row->getMeta('booking_type');
                            $parentBookingId = $row->getMeta('parent_booking_id');
                            $parentBooking = $parentBookingId ? App\Model\Bookings::find($parentBookingId) : null;
                          @endphp
                          @if($bookingType === 'return_way' && $parentBooking)
                            <img src="{{ asset('assets/customer_dashboard/assets/img/return_way.svg') }}" alt="Return Way" height="30" width="30">
                          @else
                            <img src="{{ asset('assets/customer_dashboard/assets/img/one_way.svg') }}" alt="One Way" height="30" width="30">
                          @endif
                        </td>
                        <td>
                          <a href="{{ url('admin/'.$row->id.'/modify')}}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="fa fa-pencil"></i>
                          </a>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          {{-- Upcoming Tab --}}
          <div class="tab-pane" id="upcoming">
            <h4>@lang('menu.my_bookings')</h4>
            <div class="table-responsive">
              <table class="table driver_table">
                <thead class="thead-inverse">
                  <tr>
                    <th>@lang('fleet.customer')</th>
                    <th>@lang('fleet.vehicle')</th>
                    <th>@lang('fleet.pickup')</th>
                    <th>@lang('fleet.dropoff')</th>
                    <th>@lang('fleet.pickup_addr')</th>
                    <th>@lang('fleet.dropoff_addr')</th>
                    <th>@lang('fleet.passengers')</th>
                    <th>@lang('fleet.booking_status')</th>
                    <th>Booking Type</th>
                    
                    <th>@lang('fleet.action')</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($bookings as $row)
                    @if($row->getMeta('ride_status') == "Upcoming")
                      <tr>
                        <td>{{ $row->customer->name }}</td>
                        <td>{{ $row->vehicle->make_name }} - {{ $row->vehicle->model_name }} - {{ $row->vehicle['license_plate'] }}</td>
                        <td>
                          @if($row->pickup)
                            {{ date($date_format_setting.' g:i A', strtotime($row->pickup)) }}
                          @endif
                        </td>
                        <td>
                          @if($row->dropoff)
                            {{ date($date_format_setting.' g:i A', strtotime($row->dropoff)) }}
                          @endif
                        </td>
                        <td>{{ $row->pickup_addr }}</td>
                        <td>{{ $row->dest_addr }}</td>
                        <td>{{ $row->travellers }}</td>
                        <td>{{ $row->status }}</td>
                        <td>
                          @php
                            $bookingType = $row->getMeta('booking_type');
                            $parentBookingId = $row->getMeta('parent_booking_id');
                            $parentBooking = $parentBookingId ? App\Model\Bookings::find($parentBookingId) : null;
                          @endphp
                          @if($bookingType === 'return_way' && $parentBooking)
                            <img src="{{ asset('assets/customer_dashboard/assets/img/return_way.svg') }}" alt="Return Way" height="30" width="30">
                          @else
                            <img src="{{ asset('assets/customer_dashboard/assets/img/one_way.svg') }}" alt="One Way" height="30" width="30">
                          @endif
                        </td>
                        <td>
                          <a href="{{ url('admin/'.$row->id.'/modify')}}" class="btn btn-sm btn-warning" title="Edit">
                            <i class="fa fa-pencil"></i>
                          </a>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div> <!-- tab-content -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {
  $('.driver_table').DataTable({
    "language": {
        "url": '{{ asset("assets/datatables/") . "/" . __("fleet.datatable_lang") }}',
    }
  });
});
</script>
@endsection
