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

                    <h3 class="card-title">Verify Vechile Owner Check

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



                    <form method="post" action="{{ route('update_verify_vowner') }}">

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

                                <input type="submit" value="Verify Vowner Check" class="btn btn-success">

                            </div>

                    </form> 

                </div>



            </div>



        </div>

    </div>

</div>

       

    

@endsection

