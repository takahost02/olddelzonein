<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <link rel="stylesheet" href="{{ asset('assets/css/invoice_receipt_style.css') }}">


    <link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/invoice_print.css') }}">


</head>

<body class="g-sidenav-show bg-gray-100" onload="window.print();">
    <div class="container container-fluid-print my-5">
        <div id="invoice" class="card p-4">
            <div class="row">
                <div class="col-6 mb-5">
                    <div class="invoice-logo  mb-3">
                        <img src="{{ asset('assets/images/' . Hyvikk::get('logo_img')) }}" class="img-fluid"
                            alt="Logo">
                    </div>
                    <h1 class="mb-0 set-invoice">Tax Invoice</h1>
                    <p>Invoice number: {{ $i['income_id'] ?? '-' }}</p>

                </div>
                <div class="col-6 text-end mt-4">
                    <h6 class="mb-0">@lang('fleet.From')</h6>
                    <p>
                        {{ Hyvikk::get('app_name') }}<br>
                        {{ Hyvikk::get('badd1') }},<br>
                        {{ Hyvikk::get('badd2') }},<br>
                        {{ Hyvikk::get('city') }}, {{ Hyvikk::get('state') }},{{ Hyvikk::get('country') }}<br>
                        @lang('fleet.tax_no'):
                        {{ Hyvikk::get('tax_no') }}
                    </p>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-6">
                    <h6 class="mb-0">
                        @if ($booking->customer->getMeta('address') != null)
                            Bill @lang('fleet.To')
                        @endif
                    </h6>
                    <p>
                        {!! nl2br(e($booking->customer->getMeta('address'))) !!}

                    </p>
                </div>

            </div>
            <div class="row">
                <div class="col-12 col-md-6 set-print">
                    <h6 class="mb-0">Details</h6>
                    <div class="details">
                        <table class="table details-table table">
                            <tbody>
                                <tr>
                                    <td class="details-title">
                                        <p class="semibold-title mb-0">Invoice number <span class="dots"></span></p>
                                    </td>
                                    <td class="small-text">{{ $i['income_id'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="details-title">
                                        <p class="semibold-title mb-0">Invoice date<span class="dots"></span></p>
                                    </td>
                                    <td class="small-text">
                                        {{ isset($i['created_at']) ? date('M,d,Y', strtotime($i['created_at'])) : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="details-title">
                                        <p class="semibold-title mb-0">Booking ID<span class="dots"></span></p>
                                    </td>
                                    <td class="small-text">{{ $booking->id ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td class="details-title">
                                        <p class="semibold-title mb-0">Pickup Address<span class="dots"></span></p>
                                    </td>
                                    <td class="small-text">{{ $booking->pickup_addr ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="details-title">
                                        <p class="semibold-title mb-0">Pickup Date Time<span class="dots"></span></p>
                                    </td>
                                    <td class="small-text">{{ $booking->pickup ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="details-title">
                                        <p class="semibold-title mb-0 mt-2">Dropoff Address<span class="dots"></span>
                                        </p>
                                    </td>
                                    <td class="small-text ">
                                        <p class="mt-2 mb-0" style="color:#000">{{ $booking->dest_addr ?? '-' }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="details-title">
                                        <p class="semibold-title mb-0">Dropoff Date Time<span class="dots"></span></p>
                                    </td>
                                    <td class="small-text">{{ $booking->dropoff ?? '-' }}</td>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="col-12 col-md-6 set-print">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="pb-2" style="border-bottom: 2px solid #5d5d5d;">Booking
                                Services</h6>
                        </div>
                        <div class="col-12 ">
                            <div class="row">
                                <div class="col-6">
                                    <p>Total (in {{ Hyvikk::get('currency') }})</p>
                                </div>
                                <div class="col-6   text-end">
                                    <h4>{{ Hyvikk::get('currency') }} {{ $i->booking_income->amount ?? '-' }}</h4>
                                </div>
                                <div class="col-12">
                                    <p style="border-bottom: 2px solid #5d5d5d;" class="mb-0"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">

                            <table class="table">
                                <tbody>
                                    @if ($booking->vehicle_id != null)
                                        <tr>
                                            <th style="width:50%">@lang('fleet.vehicle')</th>
                                            <td>{{ $booking->vehicle->make_name }} -
                                                {{ $booking->vehicle->model_name }} -
                                                {{ $booking->vehicle['license_plate'] }}</td>
                                        </tr>
                                    @endif
                                    @if ($booking->driver_id != null)
                                        <tr>
                                            <th>@lang('fleet.driver'):</th>
                                            <td>{{ $booking->driver->name }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('fleet.mileage')</th>
                                        <td>{{ $i->booking_income->mileage ?? '-' }} {{ Hyvikk::get('dis_format') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('fleet.waitingtime')</th>
                                        <td>{{ $booking->getMeta('waiting_time') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('fleet.amount')</th>
                                        <td>{{ Hyvikk::get('currency') }} {{ $booking->total }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('fleet.total_tax') (%) </th>
                                        <td>{{ $booking->total_tax_percent ? $booking->total_tax_percent : 0 }} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('fleet.total') @lang('fleet.tax_charge') </th>
                                        <td>{{ Hyvikk::get('currency') }}
                                            {{ $booking->total_tax_charge_rs ? $booking->total_tax_charge_rs : 0 }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('fleet.total')</th>
                                        <td>{{ Hyvikk::get('currency') }} {{ $i->booking_income->amount ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            {{-- <div class="text-end">
                      <p><strong>Total: ₹ 1150</strong></p>
                    </div> --}}
                            <div class="text-end">
                                <p><strong>@lang('fleet.total'):
                                        {{ Hyvikk::get('currency') }}{{ $i->booking_income->amount ?? '-' }}</strong>
                                </p>
                            </div>
                        </div>


                    </div>
                </div>


            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <p><b>NOTE:</b> {{ Hyvikk::get('invoice_text') }}</p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
