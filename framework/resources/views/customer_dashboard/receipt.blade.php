@extends('customer_dashboard.layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('my-bookings') }}">@lang('menu.bookings')</a></li>
    <li class="breadcrumb-item active">Receipt</li>
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('assets/css/invoice_receipt_style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/customer_dashboard/assets/main_css/receipt.css') }}">

@endsection

@section('contents')
    <div class="container my-5">
        <div id="invoice" class="card p-3">
            <div class="row">
                <div class="col-6">
                    <div class="invoice-logo">
                        <img src="{{ asset('assets/images/' . Hyvikk::get('logo_img')) }}" class="img-fluid" alt="Logo">
                    </div>
                </div>
                <div class="col-6">
                    <div class="receipt">
                        <span class="receipt-title">Receipt</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="invoice-detals mt-3">
                        <div class="row">
                            <div class="col-12">
                                <label class="mb-0">Email ID</label>
                                <p class="ps-1">{{ Hyvikk::get('email') }}</p>
                            </div>
                            <div class="col-12">
                                <label class="mb-0">Date Time</label>
                                <p class="ps-1">
                                    {{ isset($i['created_at']) ? date('M d, Y h:i:s A', strtotime($i['created_at'])) : '-' }}
                                </p>
                            </div>
                            <div class="col-6">
                                <label class="mb-0">Booking ID</label>
                                <p class="ps-1">{{ $booking->id ?? '-' }}</p>
                            </div>
                            <div class="col-6">
                                <label class="mb-0">Invoice No.</label>
                                <p class="ps-1">{{ $i['income_id'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 mt-3">
                    <label class="mb-0">Billed To</label>
                    <p class="mb-0 ps-1">{{ $payment->method ?? '-' }}</p>
                    <p class="mb-0 ps-1">{{ $booking->customer->name }}</p>
                    <address class="ps-1">
                        {!! nl2br(e($booking->customer->address)) !!}
                    </address>
                </div>
            </div>


            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Booking Services</th>
                        <th scope="col">Kms of Ride</th>
                        <th scope="col">Total Amt</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Booking Services">
                            <span class="semibold-title">Book Now/Book Later </span>
                            <p class="mb-0"><span class="semibold-title">PickUp :
                                </span>{{ $booking->pickup_addr ?? '-' }}</p>
                            <p class="mb-0"><span class="semibold-title">Dropoff :
                                </span>{{ $booking->dest_addr ?? '-' }}</p>
                        </td>
                        <td data-label="Kms of Ride">{{ isset($booking->total_kms) ? $booking->total_kms . 'kms' : '-' }}
                        </td>
                        <td data-label="Total Amt" class="total-amount">{{ Hyvikk::get('currency') }}
                            {{ $i->booking_income->amount ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Total</td>
                        <td class="total total-amount">{{ Hyvikk::get('currency') }}
                            {{ $i->booking_income->amount ?? '-' }}</td>
                    </tr>
                    <tr>

                    </tr>
                </tbody>
            </table>



            <footer>
                <div class="row">
                    <div class="col-12">
                        <div class="invoice-help text-center">
                            <p>Get help with bookings. visit <a href="https://hyvikk.com/contact-us/" target="_blank"
                                    style="text-decoration:none;">support</a>.</p>
                            <div class="company-logo mb-4 mt-3">
                                <a href="#" target="_blank">
                                    <img src="{{ asset('assets/images/' . Hyvikk::get('logo_img')) }}" alt="Logo"
                                        height="100px" width="150px">
                                </a>
                            </div>
                            <div class="text-center footer-links">
                                <ul>
                                    <li><a href="https://hyvikk.com/our-services/">Terms of service</a></li>
                                    <li><a href="https://shop.hyvikk.co/privacy-policy/">Privacy policy</a></li>
                                </ul>
                            </div>
                            <div class="copyright">
                                <p>Copyright &copy; {{ date('Y') }} <a href="#"
                                        style="text-decoration: none;color: #5d5d5d;">{{ Hyvikk::get('app_name') }}</a>.<br>
                                    All rights reserved.</p>
                            </div>
                            <div class="business-address">
                                <p>{{ Hyvikk::get('badd1') }} {{ Hyvikk::get('badd2') }} {{ Hyvikk::get('city') }},
                                    {{ Hyvikk::get('state') }} {{ Hyvikk::get('country') }}</p>
                            </div>
                            <div class="authorised-sign mt-4">
                                <span class="small-text">Authorised Signatory:</span><br>
                                <img src="{{ asset('assets/images/signature.jpg') }}" alt="signature"
                                    style="max-width: 100px;">
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <a href="{{ url('receipt-print/' . $id) }}" target="_blank" class="btn btn-primary">
            <i class="fa fa-print" style="color:white;"></i> Print Receipt
        </a>
    </div>
@endsection
