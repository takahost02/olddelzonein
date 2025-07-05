@if ($bookings->count() > 0)
    @foreach ($bookings as $booking)
        @if ($booking->ride_type === 'oneway')
        <div class="col-12" data-id="{{ $booking->id }}" style="margin-bottom: 20px;">
            <div class="booking-history-bg">
                @if($booking && $booking->ride_status)
                    <div class="booking-history-bg-blue-img">Booking No: {{ $booking->id }} {{ $booking->ride_status }}</div>
                @endif

                {{-- Icon image --}}
                <div class="set-icon-image">
                    @php
                        $hasReturn = \App\Model\Bookings::where('parent_booking_id', $booking->id)->exists();
                        $icon = ($booking->ride_type === 'oneway' && $hasReturn)
                            ? url('/assets/customer_dashboard/assets/img/return_way.svg')
                            : url('/assets/customer_dashboard/assets/img/one_way.svg');
                    @endphp
                    <img src="{{ $icon }}" width="30" height="30" class="{{ $hasReturn ? 'return-way' : 'one-way' }}" title="{{ $hasReturn ? 'Return Way' : 'One Way' }}">
                </div>

                <div class="from-to-address">
                    <div class="row">
                        <div class="col-8">
                            <div class="address-detail">
                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-4">
                                        <div class="from-add">
                                            <h5 class="from">@lang('frontend.from')</h5>
                                            <p class="mb-3 mb-sm-3 mb-md-3 mb-lg-0 mb-xl-0 mb-xxl-0">{{ $booking->pickup_addr }}</p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-4">
                                        <div class="from-add">
                                            <h5 class="from">@lang('frontend.to')</h5>
                                            <p class="mb-3 mb-sm-3 mb-md-3 mb-lg-0 mb-xl-0 mb-xxl-0">{{ $booking->dest_addr }}</p>
                                        </div>
                                    </div>
                                    @if (!is_null($booking->tax_total))
                                        <div class="row align-items-end">
                                            {{-- Amount input --}}
                                            <div class="col-12 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    {!! Form::label('tax_total_' . $booking->id, __('fleet.amount'), ['class' => 'fw-bold']) !!}
                                                    {!! Form::number('tax_total_' . $booking->id, $booking->tax_total, [
                                                        'class' => 'form-control tax-total-input',
                                                        'min' => 1,
                                                        'readonly' => 'readonly',
                                                        'id' => 'tax_total_' . $booking->id,
                                                        'data-id' => $booking->id
                                                    ]) !!}
                                                </div>
                                            </div>
                                    
                                            {{-- Accept and Reject Buttons --}}
                                            @if (!in_array($booking->ride_status, ['Upcoming','Rejected']))
                                                {{-- Accept button --}}
                                                <div class="col-6 col-md-4 col-lg-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-success w-100 accept-btn" data-id="{{ $booking->id }}">
                                                            {{ __('Accept') }}
                                                        </button>
                                                    </div>
                                                </div>
                                    
                                                {{-- Reject button --}}
                                                <div class="col-6 col-md-4 col-lg-2">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-danger w-100 reject-btn" data-id="{{ $booking->id }}">
                                                            {{ __('Reject') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                    
                                            {{-- Submit rejection button --}}
                                            <div class="col-12 col-md-4 col-lg-4 reject-submit-wrapper" id="rejectSubmitWrapper_{{ $booking->id }}" style="display: none;">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-primary w-100 submit-reject-btn" data-id="{{ $booking->id }}">
                                                        {{ __('Submit Rejection') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Message if tax_total is NULL --}}
                                        <div class="row">
                                            <div class="col-12">
                                                @if(is_null($booking->tax_total))
                                                    <p class="text-warning fw-bold" data-countdown-booking-id="{{ $booking->id }}">
                                                        Wait for <span id="countdown_{{ $booking->id }}">05:00</span>, our backend team will provide an amount with Accept and Reject options.
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Right side: fare + date --}}
                        <div class="col-4">
                            <div class="rent-hour">
                                <div class="row g-0">
                                    @if ($booking->tax_total)
                                        <div class="col-12 col-md-12 col-lg-6">
                                            <div class="rent">
                                                <h5>{{ Hyvikk::get('currency') }}{{ $booking->tax_total }}</h5>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($booking->total_kms)
                                        <div class="col-12 col-md-12 col-lg-6">
                                            <div class="hour">
                                                <p class="km">{{ $booking->total_kms }} {{ Hyvikk::get('dis_format') }}</p>
                                                @if ($booking->total_time)
                                                    @php $date = explode(":", $booking->total_time); @endphp
                                                    <p class="hour-detail">{{ $date[0] }}h {{ $date[1] }}m</p>
                                                @else
                                                    <p class="hour-detail">---</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-6 booking-history-date">
                            <div class="b-h-date">
                                <p>{{ date('d F Y', strtotime($booking->journey_date)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@else
    <h4 class="text-center">No Record Found.</h4>
@endif
@section('script')
<script>
    const rejectUrl = @json(route('bookings.reject'));
    const csrf = @json(csrf_token());
</script>

@verbatim
<script>
document.addEventListener('DOMContentLoaded', function () {
    console.debug("DOMContentLoaded: Booking approval/rejection script initialized.");

    // Handle countdown-based auto rejection
    document.querySelectorAll('[data-countdown-booking-id]').forEach(function (el) {
        const bookingId = el.getAttribute('data-countdown-booking-id');
        let countdownTime = 5 * 60; // 5 minutes in seconds
        const countdownElement = document.getElementById('countdown_' + bookingId);

        const timer = setInterval(() => {
            const minutes = Math.floor(countdownTime / 60).toString().padStart(2, '0');
            const seconds = (countdownTime % 60).toString().padStart(2, '0');
            countdownElement.textContent = `${minutes}:${seconds}`;

            countdownTime--;

            if (countdownTime < 0) {
                clearInterval(timer);

                // Auto reject after countdown
                $.ajax({
                    url: rejectUrl,
                    method: 'POST',
                    data: {
                        _token: csrf,
                        booking_id: bookingId,
                        action: 'reject',
                        tax_total: 0
                    },
                    success: function (response) {
                        console.debug("Auto-rejection submitted:", response);
                        location.reload();
                    },
                    error: function (xhr) {
                        console.error("Auto-rejection failed:", xhr.responseText);
                    }
                });
            }
        }, 1000);
    });

    // Show rejection input
    document.querySelectorAll('.reject-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            console.debug("Reject button clicked for booking ID:", id);

            const taxInput = document.getElementById('tax_total_' + id);
            const submitWrapper = document.getElementById('rejectSubmitWrapper_' + id);

            if (taxInput && submitWrapper) {
                taxInput.removeAttribute('readonly');
                taxInput.focus();
                submitWrapper.style.display = 'block';
                console.debug("Enabled tax input and showed submit button for booking ID:", id);
            } else {
                console.warn("Elements not found for booking ID:", id);
            }
        });
    });

    // Approve booking
    document.querySelectorAll('.accept-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');

            console.log("AJAX POST Data for Approve:", {
                _token: csrf,
                booking_id: id,
                action: 'approve'
            });

            $.ajax({
                url: rejectUrl,
                method: 'POST',
                data: {
                    _token: csrf,
                    booking_id: id,
                    action: 'approve'
                },
                success: function (response) {
                    console.debug("Approval submitted successfully:", response);
                    location.reload();
                },
                error: function (xhr, status, errorThrown) {
                    console.group("AJAX Approval Error Debug");
                    console.error("Status code:", xhr.status);
                    console.error("Status text:", status);
                    console.error("Error thrown:", errorThrown);
                    console.log("Raw responseText:", xhr.responseText);
                    try {
                        const json = JSON.parse(xhr.responseText);
                    } catch (e) {
                        console.error("JSON parse error:", e.message);
                    }
                    console.groupEnd();
                }
            });
        });
    });

    // Submit rejection
    document.querySelectorAll('.submit-reject-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const taxInput = document.getElementById('tax_total_' + id);
            const taxTotal = taxInput ? taxInput.value : 0;

            console.log("AJAX POST Data for Reject:", {
                _token: csrf,
                booking_id: id,
                tax_total: taxTotal,
                action: 'reject'
            });

            $.ajax({
                url: rejectUrl,
                method: 'POST',
                data: {
                    _token: csrf,
                    booking_id: id,
                    tax_total: taxTotal,
                    action: 'reject'
                },
                success: function (response) {
                    console.debug("Rejection submitted successfully:", response);
                    location.reload();
                },
                error: function (xhr, status, errorThrown) {
                    console.group("AJAX Rejection Error Debug");
                    console.error("Status code:", xhr.status);
                    console.error("Status text:", status);
                    console.error("Error thrown:", errorThrown);
                    console.log("Raw responseText:", xhr.responseText);
                    try {
                        const json = JSON.parse(xhr.responseText);
                    } catch (e) {
                        console.error("JSON parse error:", e.message);
                    }
                    console.groupEnd();
                }
            });
        });
    });
});
</script>
@endverbatim
@endsection
