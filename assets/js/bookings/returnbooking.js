





$(document).ready(function() {
  var dropoffVal = $('#dropoff').val();

  var options = {
      format: 'YYYY-MM-DD HH:mm:ss',
      sideBySide: true,
      icons: {
          previous: 'fa fa-arrow-left',
          next: 'fa fa-arrow-right',
          up: "fa fa-arrow-up",
          down: "fa fa-arrow-down"
      },
      useCurrent: false,
  };
  
  // Initialize the pickup datetimepicker
  $('#pickup1').datetimepicker(options);
  
  // If dropoff value exists, set minDate to 1 minute after dropoff
  if (dropoffVal) {
      var minPickupTime = moment(dropoffVal).add(1, 'minutes');
      $('#pickup1').data('DateTimePicker').minDate(minPickupTime);
  }


  
  // getting pickup value so minimum date should be pickup date in dropoff.
  var today4 =$('#pickup1').val();
 // Initialize #dropoff1 with a 1-minute gap after pickup1
  $('#dropoff1').datetimepicker({
    format: 'YYYY-MM-DD HH:mm:ss',
    sideBySide: true,
    icons: {
        previous: 'fa fa-arrow-left',
        next: 'fa fa-arrow-right',
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down"
    },
    minDate: today4 ? moment(today4).add(1, 'minutes') : false,
    useCurrent: false,
  });

  $("#pickup1").on("dp.change", function (e) {
    if($('#dropoff1').val() == null || $('#dropoff1').val() == ""){
      var to_date=e.date.format("YYYY-MM-DD HH:mm:ss");
    }
    else{
      var to_date=$('#dropoff1').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
    }
    var from_date=e.date.format("YYYY-MM-DD HH:mm:ss");

    $('#dropoff1').data("DateTimePicker").minDate(e.date);
  });

  $("#dropoff1").on("dp.change", function (e) {
    $('#pickup1').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss")
    var from_date=$('#pickup1').data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
    var to_date=e.date.format("YYYY-MM-DD HH:mm:ss");

  });
});
