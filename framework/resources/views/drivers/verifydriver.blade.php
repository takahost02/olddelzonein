    @extends('layouts.app')

@section('extra_css')

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">

@endsection

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('bookings.index') }}">@lang('menu.bookings')</a></li>

    <li class="breadcrumb-item active">@lang('fleet.edit_booking')</li>

@endsection

@section('content')

    <div class="row">

        <div class="col-md-12">

            <div class="card card-warning">

                <div class="card-header">

                    <h3 class="card-title">Verify Driver Check

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



                    <form method="post" action="{{ route('update_verify_driver') }}">

                        @csrf

                        <input type="hidden" name="d_id" value="{{ $driver->id }}">



                        <div class="row">

                            <div class="col-4">

                                <label>Status</label>

                                <select name="status" class="form-control">

                                    <option value=''>Select Status</option>

                                    <option value="1" @if($driver->is_verified == "1") selected @endif>Verified</option>

                                    <option value="2" @if($driver->is_verified == "2") selected @endif>Rejected</option>

                                    <option value="0" @if($driver->is_verified == "0") selected @endif>Not Verified</option>

                                </select>

                            </div>



                            <div class="col-4 pt-4">

                                <input type="submit" value="Verify Driver Check" class="btn btn-success">

                            </div>

                    </form> 

                </div>



            </div>



        </div>

    </div>

</div>

        <!-- <div class="row">

                <div class="col-md-5">

                    <table class="table" style="background: white;

                    border-radius: 12px;">

                        <tr>

                            <th style="text-align:center;">Driver Info.</th>

                        </tr>

                        <tr>

                            <th>@lang('frontend.name')</th>

                            <td>{{($driver->getMeta('first_name')??'-')}} {{($driver->getMeta('last_name')??'-')}}</td>

                        </tr>

                        <tr>

                            <th>@lang('frontend.identification_number')</th>

                            <td>{{($driver->getMeta('identification_number')??'-')}}</td>

                        </tr>

                        <tr>

                            <th>@lang('frontend.mobile_no')</th>

                            <td>{{($driver->getMeta('phone')??'-')}}</td>

                        </tr>

                        <tr>

                            <th>@lang('fleet.official_profile')</th>



                            @if($driver && $driver->getMeta('driver_image'))

                             <td><a href="{{asset('/uploads'.'/'.$driver->getMeta('driver_image'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                           

                        </tr>

                       

                        <tr>

                            <th>@lang('fleet.driver_license') @lang('fleet.front_side')</th>

                            @if($driver && $driver->getMeta('identify_front_face'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('identify_front_face'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.driver_license') @lang('fleet.back_side')</th>

                            @if($driver && $driver->getMeta('driving_license_back_side'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('driving_license_back_side'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.identify_face') @lang('fleet.front_face')</th>

                            @if($driver && $driver->getMeta('identify_front_face'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('identify_front_face'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.identify_face') @lang('fleet.back_face')</th>

                            @if($driver && $driver->getMeta('identify_backside'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('identify_backside'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.criminal_record')</th>

                            @if($driver && $driver->getMeta('criminal_record'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('criminal_record'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.drivers_card')</th>

                            @if($driver && $driver->getMeta('drivers_card'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('drivers_card'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.drivers_license_gbt')</th>

                            @if($driver && $driver->getMeta('drivers_license_gbt'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('drivers_license_gbt'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.src_certificate')</th>

                            @if($driver && $driver->getMeta('src_certificate'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('src_certificate'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.psychotechnics')</th>

                            @if($driver && $driver->getMeta('psychotechnics'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('psychotechnics'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr>

                        <tr>

                            <th>@lang('fleet.tax_registration')</th>

                            @if($driver && $driver->getMeta('tax_registration'))

                            <td><a href="{{asset('/assets/document_driver/'.$driver->getMeta('tax_registration'))}}" target="_blank">Click Here</a></td>

                            @else

                            <td>-</td>

                            @endif

                        </tr> 



                        

                    </table>

                </div>



                <div class="col-md-6">

                    <table class="table" style="background: white;

                    border-radius: 12px;">

                        <tr>

                            <th style="text-align:center;">Vehicle Info.</th>

                        </tr>

                        <tr>

                            <th>@lang('fleet.vehicle')</th>

                            <td>{{($vehicle->make_name??'-')}} </td>

                        </tr>

                        <tr>

                            <th>@lang('fleet.model')</th>

                            <td>{{($vehicle->model_name??'-')}}</td>

                        </tr>

                        <tr>

                            <th>@lang('fleet.licensePlate')</th>

                            <td>{{($vehicle->license_plate??'-')}}</td>

                        </tr>

                        <tr>

                            <th>@lang('fleet.year')</th>

                            <td>{{($vehicle->year??'-')}}</td>

                        </tr>

                      

                        <tr>

                            <th>@lang('fleet.vehicle_images')</th>



                          @if($vehicle && $vehicle->vehicle_image)

                          <td><a href="{{asset('/uploads'.'/'.$vehicle->vehicle_image)}}" target="_blank">Click Here</a></td>

                          @else

                          <td>-</td>

                          @endif

                         

                      </tr>



                      

                     

                      <tr>

                          <th>@lang('fleet.d2_vehicle_card')</th>

                          @if($vehicle && $vehicle->getMeta('d2_vehicle_card'))

                          <td><a href="{{asset('/assets/document_driver/'.$vehicle->getMeta('d2_vehicle_card'))}}" target="_blank">Click Here</a></td>

                          @else

                          <td>-</td>

                          @endif

                      </tr>

                      <tr>

                          <th>@lang('fleet.ibb_road_route_certificate')</th>

                          @if($vehicle && $vehicle->getMeta('ibb_road_route_certificate'))

                          <td><a href="{{asset('/assets/document_driver/'.$vehicle->getMeta('ibb_road_route_certificate'))}}" target="_blank">Click Here</a></td>

                          @else

                          <td>-</td>

                          @endif

                      </tr>

                      <tr>

                          <th>@lang('fleet.compulsory_seat_personal_accident_insurance')</th>

                          @if($vehicle && $vehicle->getMeta('compulsory_seat_personal_accident_insurance'))

                          <td><a href="{{asset('/assets/document_driver/'.$vehicle->getMeta('compulsory_seat_personal_accident_insurance'))}}" target="_blank">Click Here</a></td>

                          @else

                          <td>-</td>

                          @endif

                      </tr>

                      <tr>

                          <th>@lang('fleet.sample_d2_carriage_contract')</th>

                          @if($vehicle && $vehicle->getMeta('sample_d2_carriage_contract'))

                          <td><a href="{{asset('/assets/document_driver/'.$vehicle->getMeta('sample_d2_carriage_contract'))}}" target="_blank">Click Here</a></td>

                          @else

                          <td>-</td>

                          @endif

                      </tr>

                      <tr>

                          <th>@lang('fleet.sample_passanger_list')</th>

                          @if($vehicle && $vehicle->getMeta('sample_passanger_list'))

                          <td><a href="{{asset('/assets/document_driver/'.$vehicle->getMeta('sample_passanger_list'))}}" target="_blank">Click Here</a></td>

                          @else

                          <td>-</td>

                          @endif

                      </tr>

                       

                        

                    </table>

                </div>

            

         </div>  -->

    

@endsection

