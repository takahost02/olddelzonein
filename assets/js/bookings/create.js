

// $(function() {

//     var from_date = $('#pickup').val();

//     var to_date = $('#dropoff').val();

//     var yes = true;

//     //  alert(from_date);

//     var id = $("input:hidden[name=id]").val();

//     $.ajaxSetup({

//         headers: {

//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

//         }

//     });



//     $.ajax({

//         type: "POST",

//         url: getDriverRoute,

//         data: "req=" + yes + "&from_date=" + from_date + "&to_date=" + to_date,

//         success: function(data2) {

//             $("#driver_id").empty();

//             $("#driver_id").select2({

//                 placeholder:selectDriver,

//                 data: [{

//                     id: '',

//                     text: ''

//                 }].concat(data2.data)

//             });

//             // if(data2.show_error=="yes"){

//             //   // alert("test");

//             // $("#msg_driver").removeClass("hide").fadeIn(1000);

//             // } else {

//             // $("#msg_driver").addClass("hide").fadeIn(1000);

//             // }

//         },

//         error: function(data) {

//             var errors = $.parseJSON(data.responseText);



//             $(".print-error-msg").find("ul").html('');

//             $(".print-error-msg").css('display', 'block');

//             $.each(errors, function(key, value) {

//                 $(".print-error-msg").find("ul").append('<li>' + value + '</li>');

//             });



//         },

//         dataType: "json"

//     });

// });

var today = $('#pickup').val();

// var today=new Date();

// console.log(today);

$('#customer_id').select2({

    placeholder: selectCustomer

});

$('#driver_id').select2({

    placeholder: selectDriver

});

$('#vehicle_id').select2({

    placeholder: selectVehicle

});

$("#create_customer_form").on("submit", function(e) {

    $(".print-error-msg").find("ul").html('');

    $(".print-error-msg").hide();

    var form = $(this);

    $.ajax({

        type: "POST",

        url: form.attr("action"),

        data: form.serialize(),

        success: function(data) {

            var customers = $.parseJSON(data);

            if (customers.error === 'true') {

                $(".print-error-msg").find("ul").html('');

                $(".print-error-msg").css('display', 'block');

                $.each(customers.messages, function(key, value) {

                    $(".print-error-msg").find("ul").append('<li>' + value + '</li>');

                });

                // new PNotify({

                //     title: 'Failed!',

                //     text: fleet_email_already_taken,

                //     type: 'error'

                // });

            } else {

                form.find("input, textarea").val('');

                $('#customer_id').empty();

                $.each(customers, function(key, value) {

                    $('#customer_id').append($('<option>', {

                        value: value.id,

                        text: value.text

                    }));

                });

                $('#exampleModal').modal('hide');



                new PNotify({

                    title: 'Success!',

                    text: addCustomer,

                    type: 'success'

                });

            }

        },

        error: function(data) {

            var errors = $.parseJSON(data.responseText);

            $(".print-error-msg").find("ul").html('');

            $(".print-error-msg").css('display', 'block');

            $.each(errors, function(key, value) {

                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');

            });

        },

        dataType: "html"

    });

    e.preventDefault();

});



function get_driver(from_date, to_date) {

    $.ajax({

        type: "POST",

        // headers: {

        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        // },

        url: getDriverRoute,

        data: "req=new&from_date=" + from_date + "&to_date=" + to_date,

        success: function(data2) {

            $("#driver_id").empty();

            $("#driver_id").select2({

                placeholder: selectDriver,

                data: [{

                    id: '',

                    text: ''

                }].concat(data2.data)

            });

        },

        dataType: "json"

    });

}



function get_vehicle(from_date, to_date) {

    $.ajax({

        type: "POST",

        // headers: {

        //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        // },

        url: getVehicleRoute,

        data: "req=new&from_date=" + from_date + "&to_date=" + to_date,

        success: function(data2) {

            $("#vehicle_id").empty();

            $("#vehicle_id").select2({

                placeholder: selectVehicle,

                data: data2.data

            });

        },

        dataType: "json"

    });

}



function prev_address(id) {

    $.ajax({

        type: "POST",

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        },



        url: prevAddress,

        data: "id=" + id,

        success: function(data) {

            $("#pickup_addr").val(data.pickup_addr);

            $("#dest_addr").val(data.dest_addr);

            if (data.pickup_addr != "") {

                new PNotify({

                    title: 'Success!',

                    text: prevAddressLang,

                    type: 'success'

                });

            }

        },

        dataType: "json"

    });

}



$(document).ready(function() {

    $("#customer_id").on("change", function() {

        var id = $(this).find(":selected").data("id");

        prev_address(id);

    });



    $("#d_pickup").on("change", function() {

        var address = $(this).find(":selected").data("address");

        $("#pickup_addr").val(address);

    });



    $("#d_dest").on("change", function() {

        var address = $(this).find(":selected").data("address");

        $("#dest_addr").val(address);

    });



    $(function () {
        const format = 'YYYY-MM-DD HH:mm:ss';
        let suppressApiCall = false;
    
        function disablePicker(selector) {
           
            if (!$(selector).data("DateTimePicker")) {
                $(selector).datetimepicker({
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
            }
        }
    
        function enableAndUpdatePicker(selector, minDate) {
            
            if (!$(selector).data("DateTimePicker")) {
                $(selector).datetimepicker({
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
    
            const picker = $(selector).data("DateTimePicker");
    
            if (picker) {
                picker.minDate(minDate);
                if (!picker.date() || !picker.date().isSameOrAfter(minDate)) {
                    picker.date(minDate);
                }
            }
        }
    
        // Disable all inputs except pickup initially
        disablePicker('#dropoff');
        disablePicker('#returnPickup');
        disablePicker('#returnDropoff');
    
        $('#pickup').datetimepicker({
            format,
            sideBySide: true,
            icons: {
                previous: 'fa fa-arrow-left',
                next: 'fa fa-arrow-right',
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down"
            }
        }).on('dp.change', function (e) {
            const bookingType = $('.booking_type').val();
            const pickup = e.date.clone();
            const dropoff = pickup.clone().add(1, 'minutes');
            const returnPickup = dropoff.clone().add(1, 'minutes');
            const returnDropoff = returnPickup.clone().add(1, 'minutes');
    
            enableAndUpdatePicker('#dropoff', dropoff);
            enableAndUpdatePicker('#returnPickup', returnPickup);
            enableAndUpdatePicker('#returnDropoff', returnDropoff);
    
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
        }).on('dp.change', function (e) {
            const bookingType = $('.booking_type').val();
            const dropoff = e.date.clone();
            const pickup = $('#pickup').data("DateTimePicker").date();
    
            if (bookingType === 'one_way') {
                if (pickup && dropoff.isAfter(pickup)) {
                    get_driver(pickup.format(format), dropoff.format(format));
                    get_vehicle(pickup.format(format), dropoff.format(format));
                }
            } else {
                const returnPickup = dropoff.clone().add(1, 'minutes');
                const returnDropoff = returnPickup.clone().add(1, 'minutes');
    
                if ($("#returnPickup").val() == '' && $("#returnDropoff").val() == '') {
                    if (pickup) {
                        get_driver(pickup.format(format), returnDropoff.format(format));
                        get_vehicle(pickup.format(format), returnDropoff.format(format));
                    }
                }
    
                enableAndUpdatePicker('#returnPickup', returnPickup);
                enableAndUpdatePicker('#returnDropoff', returnDropoff);
            }
        });
    
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
    
            enableAndUpdatePicker('#returnDropoff', returnDropoff);
    
            suppressApiCall = false;
        });
    
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
    
    
    
    
    
    



});

$(".add_udf").click(function() {

    // alert($('#udf').val());

    var field = $('#udf1').val();

    if (field == "" || field == null) {

        alert('Enter field name');

    } else {

        $(".blank").append(

            '<div class="row"><div class="col-md-8">  <div class="form-group"> <label class="form-label">' +

            field.toUpperCase() + '</label> <input type="text" name="udf[' + field +

            ']" class="form-control" placeholder="Enter ' + field +

            '" required></div></div><div class="col-md-4"> <div class="form-group" style="margin-top: 30px"><button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.parentElement.remove();">Remove</button> </div></div></div>'

            );

        $('#udf1').val("");

    }

});


