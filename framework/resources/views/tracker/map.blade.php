@extends('layouts.app')

@section('breadcrumb')
    <li class="breadcrumb-item active">@lang('fleet.vehicle_track')</li>
@endsection

@section('content')
    {{-- @dd($error) --}}
    {{-- <div class="text-center js-alert alert-danger alert d-none">
        
    </div> --}}

    <div class="text-center alert-danger alert  @if (!(isset($error) && $error)) d-none @endif">
        {{ $error ?? '' }}
    </div>

    <div class="row d-flex justify-content-center align-items-center flex-column mb-5">
        <div class="col-lg-6 col-md-8">
            <p class="text-center"><b>Select Vehicle For Track</b></p>
            <div style="width: 100%;">
                <select name="vehicle" id="vehicle_id" class="form-control mb-2 select">
                    <option value="">-- Select --</option>
                    @foreach ($vehicles as $v)
                        <option value="{{ $v->id }}" {{ $v->id == $selected_vehicle_id ? 'selected' : '' }}>
                            {{ $v->make_name }} {{ $v->model_name }} {{ $v->license_plate }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    {{-- @if (!(isset($error) && $error)) --}}
    <div id="map" style="width:100%;height:400px;" class="@if (isset($error)) d-none @endif"></div>
    <div class="text-center">
        <div class="card">
            <div class="card-header d-flex justify-content-center align-items-center" style="padding-top:15px">
                <h3><strong>Car Information</strong></h3>
            </div>
            <div class="card-body">
                <table class='table table-striped'>
                    <thead>
                        <tr>
                            <th>Vehicle Name</th>
                            <th>Vehicle Speed</th>
                            <th>Booking PickUp</th>
                            <th>Booking Dropoff</th>
                            <th>Driver Name</th>
                        </tr>
                    </thead>
                    <tbody id="vehicle_data_body">
                        @if (isset($error) && $error)
                            <tr>
                                <td colspan="5" class="text-center">No Data Found.</td>
                            </tr>
                        @endif
                        {{-- Data will be populated dynamically --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- @endif --}}
@endsection

@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ Hyvikk::get('traccar_map_key') }} &loading=async" defer>
    </script>

    <script>
        $('.select').select2();
        var map;
        var markers = [];
        let positionsData;
        @if (isset($positionsData))
            positionsData = @json($positionsData);
        @endif
        let timoutForPollServer;
        let carIcon;
        const mapContainer = $('#map');

        $('#vehicle_id').on('change', function() {
            // var vehicle_id = $('#vehicle_id').val();
            // var url = vehicle_id ? '{{ url('admin/vehicles-track') }}/' + vehicle_id :
            //     '{{ url('admin/vehicles-track') }}';
            // location.href = url;
            destroyMap();
            // $('tbody').empty();
            // $('tbody').append('<tr><td colspan="5">No Data Found.</td></tr>');
            clearTimeout(timoutForPollServer);
            pollServer();
        });

        function initializeMap() {
            mapContainer.html('');
            mapContainer.removeClass('d-none');
            // Set the initial center of the map
            const initialPosition = positionsData.length > 0 ? positionsData[0].position : {
                latitude: 20.593683,
                longitude: 78.962883
            };
            var myLatlng = new google.maps.LatLng(initialPosition.latitude, initialPosition.longitude);
            carIcon = {
                url: '{{ asset('assets/images/small-car.png') }}',
                scaledSize: new google.maps.Size(50, 50),
            };
            // Map options
            var mapOptions = {
                zoom: 20,
                center: myLatlng
            };
            map = new google.maps.Map(document.getElementById("map"), mapOptions);

            if (positionsData && positionsData.length > 0) {
                $('tbody').empty();
                positionsData.forEach(data => {
                    var position = data.position;
                    var vehicle = data.vehicle;
                    var booking = data.booking;
                    // console.log(data);
                    var myLatlng = new google.maps.LatLng(position.latitude, position.longitude);
                    var marker = new google.maps.Marker({
                        position: myLatlng,
                        map: map,
                        icon: carIcon,
                        title: `${vehicle.make_name} ${vehicle.model_name} ${vehicle.license_plate}`,
                        id: vehicle.id
                    });
                    markers.push(marker);

                    var rowHtml = "<tr data-vehicle-id='" + vehicle.id + "'>";
                    rowHtml += "<td>" + vehicle.make_name + ' ' + vehicle.model_name + ' ' + vehicle.license_plate +
                        "</td>";
                    rowHtml += "<td>" + (position.speed * 1.852).toFixed(2) + " km/h</td>";
                    rowHtml += "<td>" + (booking && booking.pickup ? booking.pickup : "-") +
                        "</td>";
                    rowHtml += "<td>" + (booking && booking.dropoff ? booking.dropoff :
                        "-") + "</td>";
                    rowHtml += "<td>" + (booking && booking.driver ? booking.driver.name :
                        "-") + "</td>";
                    rowHtml += "</tr>";
                    $('table tbody').append(rowHtml);
                });
            } else {
                // Handle case when positionsData is empty or undefined
                // console.log("No positions data available.");
            }
        }

        function pollServer() {
            var vehicle_id = $('#vehicle_id').val();
            var url = vehicle_id ? '{{ url('admin/vehicles-track/') }}/' + vehicle_id :
                '{{ url('admin/vehicles-track') }}';
            // Make an AJAX request to the server to get the current locations of all vehicles
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response && Array.isArray(response.positionsData) && Array.isArray(response.vehicles)) {
                        // console.log(response);
                        // debugger;
                        $('.alert').addClass('d-none');
                        $('tbody').empty();
                        if (!map) {
                            positionsData = response.positionsData;
                            initializeMap();
                            return;
                        }
                        response.positionsData.forEach(function(element) {
                            var position = element.position;
                            var vehicle = element.vehicle;
                            var booking = element.booking || {};
                            var driverName = booking.driver ? booking.driver.name : "-";
                            updateMarker(vehicle.id, position.latitude, position.longitude, position
                                .speed, vehicle.make_name, vehicle.model_name, vehicle
                                .license_plate, booking.pickup, booking.dropoff,
                                driverName);
                        });
                    } else {
                        if (response.error) {
                            $('.alert').text(response.error).removeClass('d-none');
                            destroyMap();
                        }
                    }
                },
                error: function(xhr, status, error) {},
                complete: function() {
                    timoutForPollServer = setTimeout(pollServer, 10000); // Fetch data again after 10 seconds
                }
            });
        }

        function updateMarker(vehicleId, lat, lng, speed, make_name, model_name, license_plate, pickup, dropoff,
            driverName) {
            var marker = markers.find(marker => marker.id === vehicleId);
            if (marker) {
                var existingRow = $('table tbody tr[data-vehicle-id="' + vehicleId + '"]');
                var rowHtml = "<td>" + make_name + ' ' + model_name + ' ' + license_plate + "</td>";
                rowHtml += "<td>" + (speed * 1.852).toFixed(2) + ' km/h' + "</td>";
                rowHtml += "<td>" + (pickup || "-") + "</td>";
                rowHtml += "<td>" + (dropoff || "-") + "</td>";
                rowHtml += "<td>" + (driverName || "-") + "</td>";
                // var rowHtml = "<tr data-vehicle-id='" + vehicleId + "'>";
                // rowHtml += "<td>" + make_name + ' ' + model_name + ' ' + license_plate + "</td>";
                // rowHtml += "<td>" + (speed * 1.852).toFixed(2) + ' km/h' + "</td>";
                // rowHtml += "<td>" + (pickup || "-") + "</td>";
                // rowHtml += "<td>" + (dropoff || "-") + "</td>";
                // rowHtml += "<td>" + (driverName || "-") + "</td>";
                // rowHtml += "</tr>";
                if (existingRow.length > 0) {
                    // Update existing row
                    existingRow.html(rowHtml);
                } else {
                    // Append new row
                    var newRow = "<tr data-vehicle-id='" + vehicleId + "'>" + rowHtml + "</tr>";
                    $('table tbody').append(newRow);
                }
                var myLatlng = new google.maps.LatLng(lat, lng);
                marker.setPosition(myLatlng);
            } else {
                // Create a new marker if it doesn't exist
                var newMarker = new google.maps.Marker({
                    position: new google.maps.LatLng(lat, lng),
                    map: map, // Assuming 'map' is your Google Map object
                    icon: carIcon,
                    title: `${model_name} ${make_name} ${license_plate}`,
                    id: vehicleId
                });

                // Store the new marker in your markers array
                markers.push(newMarker);
                // Create and append the row for the new marker
                var newRowHtml = "<tr data-vehicle-id='" + vehicleId + "'>";
                newRowHtml += "<td>" + make_name + ' ' + model_name + ' ' + license_plate + "</td>";
                newRowHtml += "<td>" + (speed * 1.852).toFixed(2) + ' km/h' + "</td>";
                newRowHtml += "<td>" + (pickup || "-") + "</td>";
                newRowHtml += "<td>" + (dropoff || "-") + "</td>";
                newRowHtml += "<td>" + (driverName || "-") + "</td>";
                newRowHtml += "</tr>";
                $('table tbody').append(newRowHtml);
            }
        }

        function destroyMap() {
            if (map) {
                $('tbody').empty();
                $('tbody').append('<tr><td colspan="5">No Data Found.</td></tr>');
                // Remove event listeners (if any)
                google.maps.event.clearInstanceListeners(map);
                // Remove the map's DOM element
                mapContainer.html('');
                mapContainer.addClass('d-none');
                // Nullify the map instance to release memory
                map = null;
            }
        }


        // Initialize the map and start polling the server
        $(document).ready(function() {
            if (typeof positionsData != 'undefined') {
                initializeMap();
            }
            timoutForPollServer = setTimeout(function() {
                pollServer();
            }, 10000);
        });
    </script>
@endsection
