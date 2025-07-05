let pickupTimeInstance, returnTimeInstance;

$(document).ready(function () {
    // Flatpickr for time inputs
    pickupTimeInstance = flatpickr(".pickup_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: false,
        minuteIncrement: 1,
        disableMobile: true,
        onChange: function () {
            enforceReturnTime(true);
        }
    });

    returnTimeInstance = flatpickr(".return_pickup_time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: false,
        minuteIncrement: 1,
        disableMobile: true
    });

    

    // Bootstrap datepickers
    $('#datepicker2, #datepicker4').datepicker({
        format: 'dd-mm-yyyy',
        todayBtn: "linked",
        clearBtn: true,
        autoclose: true,
        // startDate: new Date(),
        // endDate: new Date(new Date().getFullYear() + 1, 11, 31)
    });

    // Set initial dates
    $('#datepicker2').datepicker('setDate', new Date());
    $('#datepicker4').datepicker('setDate', new Date());


    // Datepicker change event syncing
    $('#datepicker2').on('changeDate', function () {
        const pickupDate = $('#datepicker2').datepicker('getDate');
        if (pickupDate) {
            // Sync return pickup date
            $('#datepicker4').datepicker('setDate', pickupDate);
            $('#datepicker4').datepicker('setStartDate', pickupDate);


            $('#vehicle_type').prop('selectedIndex',0);
            $('.show_vehicle').prop('selectedIndex',0);
        }
        enforceReturnTime();
    });

    $('#datepicker4').on('changeDate', function () {
        const returnPickupDate = $('#datepicker4').datepicker('getDate');
        if (returnPickupDate) {
          
            $('#vehicle_type').prop('selectedIndex',0);
            $('.show_vehicle').prop('selectedIndex',0);
        }
        enforceReturnTime();
    });

    // When return pickup time changes, sync dropoff time
    $('.return_pickup_time').on('change', function () {
        const returnTime = $(this).val();
        if (returnTime) {
              $('#vehicle_type').prop('selectedIndex',0);
            $('.show_vehicle').prop('selectedIndex',0);
        }
    });

 

    // Utility functions
    function timeToMinutes(time) {
        const [h, m] = time.split(':').map(Number);
        return h * 60 + m;
    }

    function minutesToTime(minutes) {
        const h = Math.floor(minutes / 60);
        const m = minutes % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    }

    function offsetTime(time, offsetMinutes) {
        const [h, m] = time.split(':').map(Number);
        let total = h * 60 + m + offsetMinutes;

        // Wrap around 24h if needed
        if (total >= 24 * 60) total = total % (24 * 60);

        const newH = Math.floor(total / 60);
        const newM = total % 60;
        return `${String(newH).padStart(2, '0')}:${String(newM).padStart(2, '0')}`;
    }

    function syncReturnDate() {
        const pickupDate = $('#datepicker2').datepicker('getDate');
        const returnDate = $('#datepicker4').datepicker('getDate');

        if (returnDate < pickupDate) {
            $('#datepicker4').datepicker('setDate', pickupDate);
        }

        $('#datepicker4').datepicker('setStartDate', pickupDate);
    }

    function enforceReturnTime(forceUpdate = false) {
        const pickupDate = $('#datepicker2').datepicker('getDate');
        const returnDate = $('#datepicker4').datepicker('getDate');
        const pickupTime = $('.pickup_time').val();
        const returnTime = $('.return_pickup_time').val();

        if (!pickupDate || !returnDate || !pickupTime) return;

        const isSameDay = pickupDate.toDateString() === returnDate.toDateString();
        const pickupMins = timeToMinutes(pickupTime);
        const minReturnTime = minutesToTime(pickupMins + 1);

        if (isSameDay) {
            returnTimeInstance.set({ minTime: minReturnTime });

            if (
                forceUpdate ||
                !returnTime ||
                timeToMinutes(returnTime) <= pickupMins
            ) {
                returnTimeInstance.setDate(minReturnTime, true);
            }
        } else {
            returnTimeInstance.set({ minTime: null });
        }
    }



});





$(document).ready(function() {
    // Function to toggle form visibility based on screen size
    function toggleForm() {
        var largeScreenForm = document.getElementById("large-screen-form");
        var smallScreenForm = document.getElementById("small-screen-form");
        
        var menu=$(".main-menubar");
        // Check screen width
        if (window.innerWidth > 992) { 
            
            $(largeScreenForm).css('display','block');
            $(smallScreenForm).css('display','none');
        } else {
            $(largeScreenForm).css('display','none');
            $(smallScreenForm).css('display','block');
        }

       
    }

    // Call toggleForm initially and add event listener for window resize
    toggleForm();
    window.addEventListener("resize", toggleForm);
});



$(document).ready(function(){
    const inputElement = $('.number-input-container input');
    const incrementButton = $('.increment-icon');
    const decrementButton = $('.decrement-icon');

    incrementButton.click(function() {
        let currentValue = parseInt(inputElement.val()) || 0; // Ensure it's a number or default to 0
        inputElement.val(currentValue + 1);
    });

    decrementButton.click(function() {
        let currentValue = parseInt(inputElement.val()) || 0; // Ensure it's a number or default to 0
        // Ensure the value doesn't go below minimum
        if(currentValue > parseInt(inputElement.attr('min'))) {
            inputElement.val(currentValue - 1);
        }
    });
});


$(function() {

    var owl = $('.slideshow .owl-carousel');
    owl.owlCarousel({
        autoplay: false,
        items: 1,
        loop: true,
        nav: false,
        freeDrag: true,
        mouseDrag: true,
        autoplayHoverPause: true,
        // animateOut: true,
        // animateIn:true,
        onInitialized: counter, //When the plugin has initialized.
        onTranslated: counter //When the translation of the stage has finished.
    });

    $('.our-services .owl-dot').on('click', function() {
        var totalItems = 0;
        //   owl.trigger('to.owl.carousel', [$(this).index(), 300]);
        //   currentIndex = $('.owl-dot.active').index() + 1;
        $('#counter').html("0" + currentIndex + "")
        //   $( '.owl-dot' ).removeClass( 'active' );
        //   $(this).addClass( 'active' );

    });
    $('.slideshow .owl-carousel').bind('slid', function() {
        currentIndex = $('.slideshow owl-dot.active').index() + 1;
        // $('.num').html(''+currentIndex+'/'+totalItems+'');
        //console.log(totalItems,currentIndex);
    });

    function counter(event) {

        var items = event.item.count; // Number of items
        // Position of the current item
        currentIndex = $('.slideshow .owl-dot.active').index() + 1;

        $('#counter').html("0" + currentIndex + " ")
    }

});


$(function() {

    var owl = $('.our-services .owl-carousel');
    owl.owlCarousel({
        animateOut: 'fadeOut',
        autoplay: 1000,
        items: 1,
        loop: true,
        nav: false,
        autoplayHoverPause: false,
        onInitialized: counter, //When the plugin has initialized.
        onTranslated: counter //When the translation of the stage has finished.
    });

    $('.our-services .owl-dot').on('click', function() {
        var totalItems = 0;
        //   owl.trigger('to.owl.carousel', [$(this).index(), 300]);
        //   currentIndex = $('.owl-dot.active').index() + 1;
        $('#counter1').html("0" + currentIndex + "")
        //   $( '.owl-dot' ).removeClass( 'active' );
        //   $(this).addClass( 'active' );

    });
    $('.our-services .owl-carousel').bind('slid', function() {
        currentIndex = $('.our-services  owl-dot.active').index() + 1;
        // $('.num').html(''+currentIndex+'/'+totalItems+'');
        //console.log(totalItems,currentIndex);
    });

    function counter(event) {

        var items = event.item.count; // Number of items
        // Position of the current item
        currentIndex = $('.our-services  .owl-dot.active').index() + 1;

        $('#counter1').html("0" + currentIndex + " ")
    }

});


$(function() {
    var owl = $('.testimonial .owl-carousel');
    owl.owlCarousel({
        autoplay: false,
        items: 1,
        loop: false,
        nav: false,

        autoplayHoverPause: true,


        onInitialized: counter, //When the plugin has initialized.
        onTranslated: counter //When the translation of the stage has finished.
    });

    $('.testimonial .owl-dot').on('click', function() {
        var totalItems = 0;
        //   owl.trigger('to.owl.carousel', [$(this).index(), 300]);
        //   currentIndex = $('.owl-dot.active').index() + 1;
        $('#counter1').html("0" + currentIndex + "")
        //   $( '.owl-dot' ).removeClass( 'active' );
        //   $(this).addClass( 'active' );

    });
    $('.testimonial .owl-carousel').bind('slid', function() {
        currentIndex = $('.testimonial  owl-dot.active').index() + 1;
        // $('.num').html(''+currentIndex+'/'+totalItems+'');
        //console.log(totalItems,currentIndex);
    });

    function counter(event) {

        var items = event.item.count; // Number of items
        // Position of the current item
        currentIndex = $('.testimonial  .owl-dot.active').index() + 1;

        $('#counter1').html("0" + currentIndex + " ")
    }

});


load_carousel();
function load_carousel()
{

    $(".hero-slider .owl-carousel").owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        singleItem: true,

    });
    $("input").focusout(function() {
        if ($(this).val() != "") {
            $(this).addClass("has-content");
        } else {
            $(this).removeClass("has-content");
        }
    });
     
}

$('.btn-now').on('click', '.now', function() {
    $(this).addClass('active').siblings().removeClass('active');
});

if($(".book-later").data('radiotext')=='later')
{

    $('#vehicle_type').prop('selectedIndex',0);
    $('.show_vehicle').prop('selectedIndex',0);

    $(".load_radio_button").html('<input type="hidden" name="radio1" class="select-radio" value="book_later">');
}

function mouseover() {
    document.getElementById("img1").style.display = "none";
    document.getElementById("img2").style.display = "block";
}

function mouseout() {
    document.getElementById("img1").style.display = "block";
    document.getElementById("img2").style.display = "none";
}


$(document).ready(function() {
    $(".book-now").click(function() {

        $('#vehicle_type').prop('selectedIndex',0);
        $('.show_vehicle').prop('selectedIndex',0);

        $(".date-time").css("display", "none", " transition", "all .3s ease");
        $(".load_radio_button").html('<input type="hidden" name="radio1" class="select-radio" value="book_now">');

        $('#datepicker2').datepicker('setStartDate', null); // Allow all dates again

        // Optional: re-apply today or default date
        const today = new Date();
        $('#datepicker2').datepicker('setDate', today);
        $('#datepicker4').datepicker('setDate', today);
    
    
        // Disable return date until pickup is selected again
        $('#datepicker4').prop('readonly', true);


      // Clear the return time input
      $('.return_pickup_time').val('');

      // Clear the selected time in flatpickr
      if (returnTimeInstance && returnTimeInstance.clear) {
          returnTimeInstance.clear();
      }
  
      // Reset minTime restriction
      returnTimeInstance.set({ minTime: null });
  

    });
    $(".book-later").click(function () {
        $('#vehicle_type').prop('selectedIndex', 0);
        $('.show_vehicle').prop('selectedIndex', 0);
    
        $(".date-time").css("display", "block");
        $(".load_radio_button").html('<input type="hidden" name="radio1" class="select-radio" value="book_later">');
    
        $('.return_pickup_time').val('');
    
        if (returnTimeInstance && returnTimeInstance.clear) {
            returnTimeInstance.clear();
        }
    
        returnTimeInstance.set({ minTime: null });
    
        const pickupDate = $('#datepicker2').datepicker('getDate');
        const returnDate = $('#datepicker4').datepicker('getDate');
        const pickupTime = $('.pickup_time').val();
    
        // If same date and pickupTime is set
        if (
            pickupDate &&
            returnDate &&
            pickupDate.toDateString() === returnDate.toDateString() &&
            pickupTime
        ) {
            const [h, m] = pickupTime.split(':').map(Number);
            let minutes = h * 60 + m + 1;
    
            // Prevent going beyond 23:59
            if (minutes >= 1440) minutes = 1439;
    
            const newH = Math.floor(minutes / 60);
            const newM = minutes % 60;
            const adjustedReturnTime = `${String(newH).padStart(2, '0')}:${String(newM).padStart(2, '0')}`;
    
            returnTimeInstance.set({
                minTime: adjustedReturnTime
            });
    
            returnTimeInstance.setDate(adjustedReturnTime, true);
        }
    });
    
    
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.profile-pic').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".file-upload").on('change', function() {
        readURL(this);
    });
    $(".upload-button").on('click', function() {
        $(".file-upload").click();
    });
});



$(function(){
    var current = location.pathname;
    $('.navbar-nav a').each(function(){
        var $this = $(this);
        //alert(current.substring(current.lastIndexOf('/') + 1));
        if($this.attr('href').substring(this.href.lastIndexOf('/') + 1) == current.substring(current.lastIndexOf('/') + 1)){
            $this.addClass('active');
        }
        
        if(current.substring(current.lastIndexOf('/') + 1) == '')
        {
            $('.navbar-nav a').first().addClass('active');
        }
    });

    $('.offcanvas-nav_links a').each(function(){
        var $this = $(this);
        //alert(current.split('/')[2]);
        if($this.attr('href').substring(this.href.lastIndexOf('/') + 1) == current.substring(current.lastIndexOf('/') + 1)){
            $this.addClass('active');
        }

        if(current.substring(current.lastIndexOf('/') + 1) == '')
        {
            $('.offcanvas-nav_links a').first().addClass('active');
        }
    });
        
    
});

// window.setTimeout(function () { 
//      $(".alert-success").alert('close'); 
// }, 3000);

if ($(location).attr('href').substring($(location).attr('href').lastIndexOf('/') + 1) == "#login")
{
    $('#login-popup').addClass('active');
    $('#login-popup').removeClass('fadeOutDown'); 
}