

$(".add_udf").click(function () {

    // alert($('#udf').val());
  
    var field = $('#udf1').val();
  
    if(field == "" || field == null){
  
      alert('Enter field name');
  
    }
  
  
  
    else{
  
      $(".blank").append('<div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label">'+ field.toUpperCase() +'</label> <input type="text" name="udf['+ field +']" class="form-control" placeholder="Enter '+ field +'" required></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>');
  
      $('#udf1').val("");
  
    }
  
  });
  
  
  
  // {{-- it is for page load because driver should be change accoriding to time --}}
  
  
  
  // $(function(){
  
  //  var from_date= $('#pickup').val();
  
  //  var to_date= $('#dropoff').val();
  
  // //  alert(from_date);
  
  //   var id=$("input:hidden[name=id]").val();
  
  //   $.ajaxSetup({
  
  //     headers: {
  
  //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  
  //     }
  
  //   });
  
  
  
  //   $.ajax({
  
  //     type: "POST",
  
  //     url: getDriverRoute,
  
  //     data: "req=edit&id="+id+"&from_date="+from_date+"&to_date="+to_date,
  
  //     success: function(data2){
  
  //       $("#driver_id").empty();
  
  //       $("#driver_id").select2({placeholder: selectDriver,data:data2.data});
  
  //       // if(data2.show_error=="yes"){
  
  //       //   // alert("test");
  
  //       // $("#msg_driver").removeClass("hide").fadeIn(1000);
  
  //       // } else {
  
  //       // $("#msg_driver").addClass("hide").fadeIn(1000);
  
  //       // }
  
  //     },
  
  //     error: function(data){
  
  //     var errors = $.parseJSON(data.responseText);
  
  
  
  //       $(".print-error-msg").find("ul").html('');
  
  //     $(".print-error-msg").css('display','block');
  
  //     $.each( errors, function( key, value ) {
  
  //       $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
  
  //     });
  
  
  
  //     },
  
  //     dataType: "json"
  
  //   });
  
  // });
  
  
  
  
  
  $('#customer_id').select2({placeholder: selectCustomer});
  
  $('#driver_id').select2({placeholder: selectDriver});
  
  $('#vehicle_id').select2({placeholder:selectVehicle});
  
  
  
  function get_driver(from_date,to_date){
  
  
  
    var id=$("input:hidden[name=id]").val();
  
    $.ajax({
  
      type: "POST",
  
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
  
      url: getDriverRoute,
  
      data: "req=edit&id="+id+"&from_date="+from_date+"&to_date="+to_date,
  
      success: function(data2){
  
        $("#driver_id").empty();
  
        $("#driver_id").select2({placeholder: selectDriver,data:data2.data});
  
        // if(data2.show_error=="yes"){
  
        //   // alert("test");
  
        // $("#msg_driver").removeClass("hide").fadeIn(1000);
  
        // } else {
  
        // $("#msg_driver").addClass("hide").fadeIn(1000);
  
        // }
  
      },
  
      error: function(data){
  
      var errors = $.parseJSON(data.responseText);
  
  
  
      $(".print-error-msg").find("ul").html('');
  
      $(".print-error-msg").css('display','block');
  
      $.each( errors, function( key, value ) {
  
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
  
      });
  
  
  
      },
  
      dataType: "json"
  
    });
  
  }
  
  
  
  function get_vehicle(from_date,to_date){
  
    var id=$("input:hidden[name=id]").val();
  
  
  
    $.ajax({
  
      type: "POST",
  
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
  
      url:getVehicleRoute,
  
      data: "req=edit&id="+id+"&from_date="+from_date+"&to_date="+to_date,
  
      success: function(data2){
  
        $("#vehicle_id").empty();
  
        $("#vehicle_id").select2({placeholder: selectVehicle,data:data2.data});
  
        // if(data2.show_error=="yes"){
  
  
  
        // $("#msg_vehicle").removeClass("hide").fadeIn(1000);
  
        // } else {
  
        // $("#msg_vehicle").addClass("hide").fadeIn(1000);
  
        // }
  
      },
  
      error: function(data){
  
        var errors = $.parseJSON(data.responseText);
  
        $(".print-error-msg").find("ul").html('');
  
        $(".print-error-msg").css('display','block');
  
        $.each( errors, function( key, value ) {
  
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
  
        });
  
      },
  
      dataType: "json"
  
    });
  
  }
  
  
  $(document).ready(function () {
    const format = 'YYYY-MM-DD HH:mm:ss';
    let suppressApiCall = false;

    function updatePicker(selector, minDate) {
        const $picker = $(selector);

        if (!$picker.data("DateTimePicker")) {
            $picker.datetimepicker({
                format,
                sideBySide: true,
                useCurrent: false,
                minDate,
                icons: {
                    previous: 'fa fa-arrow-left',
                    next: 'fa fa-arrow-right',
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                }
            });
        }

        const picker = $picker.data("DateTimePicker");
        if (picker) {
            picker.minDate(minDate);

            const currentDate = picker.date();
            if (!currentDate || currentDate.isBefore(minDate)) {
                picker.date(minDate);
            }
        }
    }

    // Initialize Pickup
    $('#pickup').datetimepicker({
        format,
        sideBySide: true,
        icons: {
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
    });

    // Initialize Dropoff
    $('#dropoff').datetimepicker({
        format,
        sideBySide: true,
        useCurrent: false,
        icons: {
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
    });

    // On Edit: if pickup has value, set minDate on dropoff
    const pickupVal = $('#pickup').val();
    if (pickupVal) {
        const pickupMoment = moment(pickupVal, format);
        $('#dropoff').data("DateTimePicker").minDate(pickupMoment);
    }

    // Pickup Change
    $('#pickup').on('dp.change', function (e) {
        const bookingType = $('.booking_type').val();
        const pickup = e.date.clone();

        const dropoff = pickup.clone().add(1, 'minutes');
        const returnPickup = dropoff.clone().add(1, 'minutes');
        const returnDropoff = returnPickup.clone().add(1, 'minutes');

        updatePicker('#dropoff', dropoff);
        updatePicker('#returnPickup', returnPickup);
        updatePicker('#returnDropoff', returnDropoff);

        if (bookingType === 'return_way') {
            const to_date = $('#returnDropoff').data("DateTimePicker").date();
            if (to_date && to_date.isAfter(pickup)) {
                get_driver(pickup.format(format), to_date.format(format));
                get_vehicle(pickup.format(format), to_date.format(format));
            }
        } else {
            const to_date = $('#dropoff').data("DateTimePicker").date();
            if (to_date && to_date.isAfter(pickup)) {
                get_driver(pickup.format(format), to_date.format(format));
                get_vehicle(pickup.format(format), to_date.format(format));
            }
        }
    });

    // Dropoff Change
    $('#dropoff').on('dp.change', function (e) {
        const bookingType = $('.booking_type').val();
        const dropoff = e.date.clone();
        const pickup = $('#pickup').data("DateTimePicker").date();

        if (pickup) {
            $('#dropoff').data("DateTimePicker").minDate(pickup);
        }

        if (bookingType === 'one_way') {
            if (pickup && dropoff.isAfter(pickup)) {
                get_driver(pickup.format(format), dropoff.format(format));
                get_vehicle(pickup.format(format), dropoff.format(format));
            }
        } else {
            const returnPickup = dropoff.clone().add(1, 'minutes');
            const returnDropoff = returnPickup.clone().add(1, 'minutes');

            if ($("#returnDropoff").val() === '') {
                if (pickup) {
                    get_driver(pickup.format(format), returnDropoff.format(format));
                    get_vehicle(pickup.format(format), returnDropoff.format(format));
                }
            }

            updatePicker('#returnPickup', returnPickup);
            updatePicker('#returnDropoff', returnDropoff);
        }
    });

    // Initialize Return Pickup
    $('#returnPickup').datetimepicker({
        format,
        sideBySide: true,
        useCurrent: false,
        icons: {
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
    }).on('dp.change', function (e) {
        suppressApiCall = true;

        const returnPickup = e.date.clone();
        const returnDropoff = returnPickup.clone().add(1, 'minutes');

        updatePicker('#returnDropoff', returnDropoff);

        suppressApiCall = false;
    });

    // Initialize Return Dropoff
    $('#returnDropoff').datetimepicker({
        format,
        sideBySide: true,
        useCurrent: false,
        icons: {
            previous: 'fa fa-arrow-left',
            next: 'fa fa-arrow-right',
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        }
    }).on('dp.change', function (e) {
        if (suppressApiCall) return;

        const bookingType = $('.booking_type').val();
        if (bookingType === 'return_way') {
            const pickup = $('#pickup').data("DateTimePicker").date();
            const returnDropoff = e.date;
            if (pickup && returnDropoff.isAfter(pickup)) {
                get_driver(pickup.format(format), returnDropoff.format(format));
                get_vehicle(pickup.format(format), returnDropoff.format(format));
            }
        }
    });
});

  
  
  
  