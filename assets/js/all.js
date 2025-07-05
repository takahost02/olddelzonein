



$(document).ready(function() {

    $(document).on("change",".edit_gender",function(){

       $(".hide_gender").val($(this).val());

   });

     $(document).on("click",".save-data" ,function() {

       

           

       var bookingid = '';

       var message = '';

       var status = '';

       if ($(this).data('rcode') == "100") {

           if ($(".sele-vehicle-breakdown :selected").data('bookingid') === undefined || $(

                   ".sele-vehicle-breakdown :selected").data('bookingid') === '') {

               $(".error-vehicle-breakdown").text('This Field is required.').css('color', 'red');

           }

          

           status = 'vehicle_breakdown';

           bookingid = $(".sele-vehicle-breakdown :selected").data('bookingid');

           message = $(".sele-vehicle-breakdown :selected").data('message');

       } else {

         

       

           if ($(".sele-driver-alert :selected").data('bookingid') === undefined || $(

                   ".sele-driver-alert :selected").data('bookingid') === '') {

               $(".error-driver-alert").text('This Field is required.').css('color', 'red');

           }

        

           console.log("test");

           status = 'driver_alert';

           bookingid = $(".sele-driver-alert :selected").data('bookingid');

           message = $(".sele-driver-alert :selected").data('message');

       }

       

       

       if(bookingid && message && status )

       {

           $.ajax({

               url: booking_alert,

               type: "get",

               data: {

                   'bookingid': bookingid,

                   'message': message,

                   'status': status

               },

               success: function(data) {

                   var tblprint = '';

       

                   if (data.status == 100) {

                       tblprint += ` <div class="alert alert-success custom-alert" role="alert">

                     <strong style="color:white;">Your Request Sent Successfully</strong>

                    </div>`;

                       $(".custom-alert-msg").html(tblprint);

       

                       $('.sele-vehicle-breakdown').prop('selectedIndex',0);

                      

                       $(".error-vehicle-breakdown").text('');

       

                       $('.sele-driver-alert').prop('selectedIndex',0);

                       

                       $(".error-driver-alert").text('');

       

                       $("#exampleModal100").modal("hide");

                       $("#exampleModal101").modal("hide");

                       $("#exampleModal102").modal("hide");

                       $("#exampleModal103").modal("hide");

       

                       setTimeout(function() {

                           $(".custom-alert-msg").html('');

                       }, 6000);

       

                   } else {

       

       

                       tblprint += ` <div class="alert alert-danger custom-alert" role="alert">

                     <strong style="color:white;">Your Request Sent Not Successfully</strong>

                 </div>`;

                       $(".custom-alert-msg").html(tblprint);

       

                       $('.sele-vehicle-breakdown').prop('selectedIndex',0);

                       

                       $(".error-vehicle-breakdown").text('');

       

       

                       $('.sele-driver-alert').prop('selectedIndex',0);

                   

                       $(".error-driver-alert").text('');

       

       

                       $("#exampleModal100").modal("hide");

                       $("#exampleModal101").modal("hide");

                       $("#exampleModal102").modal("hide");

                       $("#exampleModal103").modal("hide");

       

                       setTimeout(function() {

                           $(".custom-alert-msg").html('');

                       }, 6000);

                   }

               },

               error: function(jqXHR, exception) {

                   var tblprint = '';

                   tblprint += ` <div class="alert alert-danger custom-alert" role="alert">

                     <strong style="color:white;">Your Request Sent Not Successfully.</strong>

               </div>`;

                   $(".custom-alert-msg").html(tblprint);

       

                   $('.sele-vehicle-breakdown').prop('selectedIndex',0);

                   $(".error-vehicle-breakdown").text('');

           

           

                   $('.sele-driver-alert').prop('selectedIndex',0);

                   $(".error-driver-alert").text('');

       

                   $("#exampleModal100").modal("hide");

                   $("#exampleModal101").modal("hide");

                   $("#exampleModal102").modal("hide");

                   $("#exampleModal103").modal("hide");

                   

                   setTimeout(function() {

                       $(".custom-alert-msg").html('');

                   }, 6000);

               }

           });

       

         }

       

       });

       

       

     



  

   

  

  $('.user-email, .user-pass').on('keypress', function(event) {

       if (event.which === 13) { // Check if Enter key is pressed

           event.preventDefault(); // Prevent default form submission

           $('.login-user').click(); // Trigger click event of login button

       }

   });





   $(".login-user").on("click", function() {

   var email = $('.user-email').val();

   var password = $('.user-pass').val();

   

   $.ajax({

       

       url: login, // Changed to 'route' instead of 'url'

       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

       type: "post",

       data: {

           "email": email,

           "password": password

       },

       beforeSend: function() {

           // Show loader or any indication of request processing

           $(".hide-1").removeClass('d-none');

           $(".hide-2").addClass('d-none');

       },

       success: function(data) {

           $(".hide-1").addClass('d-none');

           $(".hide-2").removeClass('d-none');

           

           var error = data.error;

           var status = data.status;

           var tblprint = '';

           

           if (error) {

               setError(".error-email1", error.email);

               setError(".error-password1", error.password);

           } else {

                  

                   $(".error-email1").text('');

                   $(".error-password1").text('');

               if (status == 100) {

                   $('.user-email').val('');

                   $('.user-pass').val('');



                   window.location.href = Home;

                   return;

               }

               

               tblprint = '<div class="alert alert-danger" role="alert" style="color:white;">';

               

               if (status == 200 || status == 300) {

                    tblprint += '<strong>' + (status == 200 ? 'Invalid login credentials or customer not verified.' : 'Invalid login credentials') + '</strong>';

               } else {

                   tblprint += '<strong>There is some error</strong>';

               }

               

               tblprint += '</div>';

               

               if (status == 300) {

                   $(".msg-login").html(tblprint);

                   

                   setTimeout(function() {

                       $(".msg-login").html('');

                   }, 6000); 

               } else {

                   $(".msg-login").html(tblprint);


                   setTimeout(function() {

                        $(".msg-login").html('');

                    }, 6000); 

               }

           }

       },

       error: function(xhr, status, error) {

           $(".hide-1").addClass('d-none');

           $(".hide-2").removeClass('d-none');

       }

   });

});



function setError(selector, error) {

   var $element = $(selector);

   if (error) {

       $element.text(error).css('color', 'red');

   } else {

       $element.text('');

   }

}













$('#large-screen-form').on('keypress', function(event) {

   if (event.which === 13) { // Check if Enter key is pressed

       event.preventDefault(); // Prevent default form submission

       $('.register-user').click(); // Trigger click event of login button

   }

});





   $(".register-user").on("click", function() {

   $.ajax({

       url: register,

       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

       type: "post",

       data: $("#large-screen-form").serialize(),

       beforeSend: function() {

           $(".hide-3").removeClass('d-none');

           $(".hide-4").addClass('d-none');

       },

       success: function(data) {

           $(".hide-3").addClass('d-none');

           $(".hide-4").removeClass('d-none');

           if(data.error) {

               setErrorMessage(data.error.first_name, ".error-first_name");

               setErrorMessage(data.error.last_name, ".error-last_name");

               setErrorMessage(data.error.email, ".error-email");

               setErrorMessage(data.error.password, ".error-password");

               setErrorMessage(data.error.confirm_password, ".error-confirm_password");

               setErrorMessage(data.error.gender, ".error-gender");

               setErrorMessage(data.error.phone, ".error-phone");

               setErrorMessage(data.error.agree, ".error-agree");

           }

           else if(data['status']==100)

           {

               $('#large-screen-form')[0].reset();

               $(".error-first_name").text('');

               $(".error-last_name").text('');

               $(".error-email").text('');

               $(".error-password").text('');

               $(".error-gender").text('');

               $(".error-phone").text('');

               $(".error-confirm_password").text('');

               $(".error-agree").text('');

               var tblprint="";



               tblprint+=` <div class="alert alert-success" role="alert" style="color:white;">

                                  <strong>You are registered Successfully! please login here.</strong>

                           </div>`;

               $(".register-msg").html(tblprint);





               setTimeout(function() {

                       $(".register-msg").html('');

                   }, 6000); 

           }

       },

       error: function(xhr, status, error) {

        $(".hide-3").addClass('d-none');

        $(".hide-4").removeClass('d-none');

       }

   });

});



function setErrorMessage(error, elementSelector, isList = false) {

   var $element = $(elementSelector);

   if (error) {

       if (isList && Array.isArray(error)) {

           var errorMessage = '<ul>';

           error.forEach(function(message) {

               errorMessage += '<li>' + message + '</li>';

           });

           errorMessage += '</ul>';

           $element.html(errorMessage).css('color', 'red');

       } else {

           $element.text(error).css('color', 'red');

       }

   } else {

       $element.text('');

   }

}







$('.edit_gender,.edit_fullname,.edit_phone,.edit_email').on('keypress', function(event) {

   if (event.which === 13) { // Check if Enter key is pressed

       event.preventDefault(); // Prevent default form submission

       $('.profile_save').click(); // Trigger click event of login button

   }

});







   $(".profile_save").on("click", function() {

   var gender = $(".hide_gender").val();

   var fullname = $(".edit_fullname").val();

   var phone = $(".edit_phone").val();

   var email = $(".edit_email").val();

   var files = $(".edit_image").prop("files");

   var image = files.length > 0 ? files[0] : '';



   var form_data = new FormData();

   form_data.append("gender", gender);

   form_data.append("full_name", fullname);

   form_data.append("phone", phone);

   form_data.append("email", email);

   form_data.append("image", image);





   $.ajax({

       url: edit_profile,

       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

       cache: false,

       contentType: false,

       processData: false,

       data: form_data,

       type: 'post',

       beforeSend: function() {

           $(".hide-5").removeClass('d-none');

           $(".hide-6").addClass('d-none');

       },

       success: function(data) {

        $(".hide-5").addClass('d-none');

        $(".hide-6").removeClass('d-none');

           if(data.error)
            {

                

               if(data.error.full_name)

               {

                   $(".error-edit-fullname").text(data.error.full_name).css('color','red');

               }

               else

               {

                   $(".error-edit-fullname").text('');

               }



               if(data.error.gender)

               {

                   $(".error-edit-gender").text(data.error.gender).css('color','red');

               }

               else

               {

                   $(".error-edit-gender").text('');

               }

               if(data.error.phone)

               {

                   $(".error-edit-phone").text(data.error.phone).css('color','red');

               }

               else

               {

                   $(".error-edit-phone").text('');

               }

               if(data.error.email)

               {

                   $(".error-edit-email").text(data.error.email).css('color','red');

               }

               else

               {

                   $(".error-edit-email").text('');

               }

           }

           else if(data['status'] == 100)

           {

              



             


               $(".error-edit-fullname").text('');

               $(".error-edit-gender").text('');

               $(".error-edit-phone").text('');

               $(".error-edit-email").text('');



               var tblprint="";

               tblprint+=` <div class="alert alert-success" role="alert">

                                  <strong>your profile update successfully</strong>

                           </div>`;

               $(".msg-edit-profile").html(tblprint);



                setTimeout(function() {

                       $(".msg-edit-profile").html('');

                       $("#reset_password").modal('hide');

                     window.location.reload();

                     

                   }, 1000); 

               load_carousel();

           }

           

       },

       error: function(xhr, status, error) {


        $(".hide-5").addClass('d-none');

        $(".hide-6").removeClass('d-none');

           
       }

   });

});







$('#forget-password-email').on('keypress', function(event) {

   if (event.which === 13) { // Check if Enter key is pressed

       event.preventDefault(); // Prevent default form submission

       $('.forget-password-email').click(); // Trigger click event of login button

   }

});





   $(".forget-password-email").on("click",function(){

      $.ajax({

           url:forgot_password,

           headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

           type:"post",

           data:$("#forget-password-email").serialize(),

           beforeSend: function() {

           $(".hide-11").removeClass('d-none');

           $(".hide-12").addClass('d-none');

            },

           success:function(data)

           {

               $(".hide-11").addClass('d-none');

               $(".hide-12").removeClass('d-none');

               if(data.error)

               {

                    setErrorMessage(data.error.email, ".error-forget-email");

               }

               else

               {



                   $('#forget-password-email')[0].reset();

                   $(".error-forget-email").text('');

                   var tblprint="";

                   tblprint+=` <div class="alert alert-success" role="alert" style="color:white;">

                                   <strong>${data['message']}</strong>

                               </div>`;

                   $(".msg-forget-email").html(tblprint);



                   setTimeout(function() {

                       $(".msg-forget-email").html('');

                       

                   }, 2000); 

               }

           },
           error: function(xhr, status, error) {


            $(".hide-11").addClass('d-none');

            $(".hide-12").removeClass('d-none');

    
               
           }

      });

       

   });





   $('#reset-password-email').on('keypress', function(event) {

       if (event.which === 13) { // Check if Enter key is pressed

           event.preventDefault(); // Prevent default form submission

           $('.reset-password-email').click(); // Trigger click event of login button

       }

   });

   

       $(".reset-password-email").on("click",function(){

           $.ajax({

               url:reset_password_email,

               headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

               type:"post",

               data:$("#reset-password-email").serialize(),

               beforeSend: function() {

               $(".hide-9").removeClass('d-none');

               $(".hide-10").addClass('d-none');

                },

               success:function(data)

               {

                   $(".hide-9").addClass('d-none');

                   $(".hide-10").removeClass('d-none');

                   if(data.error)

                   {

                        setErrorMessage(data.error.email, ".error-reset-email");

                        setErrorMessage(data.error.password, ".error-reset-password");

                   }

                   else if(data['status'] == 200)

                   {

                       var tblprint="";

                       tblprint+=` <div class="alert alert-danger" role="alert" style="color:white;">

                                       <strong>${data['message']}</strong>

                                   </div>`;

                           $(".msg-forget-email").html(tblprint);

       

                           setTimeout(function() {

                               $(".msg-forget-email").html('');

                               

                           }, 3000); 

                   }

                   else

                   {

                       $('#reset-password-email')[0].reset();

                       $(".error-reset-email").text('');

                       $(".error-reset-password").text('');

   

                     var tblprint="";

                        tblprint+=` <div class="alert alert-success" role="alert" style="color:white;">

                                        <strong>${data['message']}</strong>

                                    </div>`;

                        $(".msg-forget-email").html(tblprint);

    

                        setTimeout(function() {

                            $(".msg-forget-email").html('');

                            window.location.href = Home;

                        }, 3000); 

                   }

               },
               error: function(xhr, status, error) {


                $(".hide-9").addClass('d-none');

                $(".hide-10").removeClass('d-none');
    
        
                   
               }

           });

       });

   





       $('#reset-password').on('keypress', function(event) {

           if (event.which === 13) { // Check if Enter key is pressed

               event.preventDefault(); // Prevent default form submission

               $('.reset-user-password').click(); // Trigger click event of login button

           }

       });

       

       

           $(".reset-user-password").on("click",function(){

                $.ajax({

                   url:reset_password,

                   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

                   type:"post",

                   data:$("#reset-password").serialize(),

                   beforeSend: function() {

                   $(".hide-7").removeClass('d-none');

                   $(".hide-8").addClass('d-none');

                    },

                   success:function(data)

                   {

                       $(".hide-7").addClass('d-none');

                         $(".hide-8").removeClass('d-none');

                       if(data.error)

                       {

                            setErrorMessage(data.error.password, ".error-current-password");

                            setErrorMessage(data.error.new_password, ".error-new-password");

                          

                       }

                       else if(data.status== 200)

                       {

                           

                           $('#reset-password')[0].reset();

                           $(".error-current-password").text('');

                           $(".error-new-password").text('');

       

                           var tblprint="";

                           tblprint+=` <div class="alert alert-success" role="alert">

                                           <strong>${data['message']}</strong>

                                       </div>`;

                           $(".msg-reset-password").html(tblprint);

       

                           setTimeout(function() {

                               $(".msg-reset-password").html('');

                               $("#reset_password").modal('hide');

                               

                            }, 3000); 

                       }

                       else 

                       {



                        var tblprint="";

                           tblprint+=` <div class="alert alert-danger" role="alert">

                                           <strong>${data['message']}</strong>

                                       </div>`;

                           $(".msg-reset-password").html(tblprint);

       

                           setTimeout(function() {

                               $(".msg-reset-password").html('');

                            }, 3000); 

                       }

                   },
                   error: function(xhr, status, error) {


                    $(".hide-7").addClass('d-none');

                    $(".hide-8").removeClass('d-none');
        
            
                       
                   }

               });

           });

       

           $('#booking_form').on('keypress', function(event) {



               

               if (event.which === 13) { // Check if Enter key is pressed

                   event.preventDefault(); // Prevent default form submission

                   $('.booking-save').click(); // Trigger click event of login button



                   

               }

           });

           

               $(".booking-save").on("click", function() {



                  



                                

               $.ajax({

                   url: book,

                   headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

                   type: "post",

                   data: $("#booking_form").serialize(),

                   beforeSend: function() {

                       $(".hide-13").removeClass('d-none');

                       $(".hide-14").addClass('d-none');

                   },

                   success: function(data) {

           



                       $(".hide-13").addClass('d-none');

                       $(".hide-14").removeClass('d-none');

                       var tblprint = '';

           

                       if (data.error) {

                       

                           if (data.error) {

                                   $(".error-pickup_address").text('');

                                   $(".error-dropoff_address").text('');

                                   $(".error-vehicle_type").text('');

                                   $(".error-vehicle").text('');

                                   $(".error-no_of_person").text('');

                                   $(".error-pickup_date_time").html('');

                                   $(".error-return_pickup_date_time").html('');

                                   $(".error-return_dropoff_date_time").html('');

           

                                   $.each(data.error, function(key, value) {

                                       setErrorMessage4(value, ".error-" + key);

                                   });

                               }

           

           

                               if (data.error.pickup_date || data.error.pickup_time) {

                                   var errorMessage = '';

           

                                   if (data.error.pickup_date) {

                                       errorMessage += `<li style="color: red;">${data.error.pickup_date}</li>`;

                                   }

           

                                   if (data.error.pickup_time) {

                                       errorMessage += `<li style="color: red;">${data.error.pickup_time}</li>`;

                                   }

           

                                   $(".error-pickup_date_time").html(errorMessage);

                               } else {

                                   

                                   $(".error-pickup_date_time").empty();

                               }



                               if (data.error.return_pickup_date || data.error.return_pickup_time) {

                                   var errorMessage = '';

           

                                   if (data.error.return_pickup_date) {

                                       errorMessage += `<li style="color: red;">${data.error.return_pickup_date}</li>`;

                                   }

           

                                   if (data.error.return_pickup_time) {

                                       errorMessage += `<li style="color: red;">${data.error.return_pickup_time}</li>`;

                                   }

           

                                   $(".error-return_pickup_date_time").html(errorMessage);

                               } else {

                                   

                                   $(".error-return_pickup_date_time").empty();

                               }


                               if (data.error.return_dropoff_date || data.error.return_dropoff_time) {

                                   var errorMessage = '';

           

                                   if (data.error.return_dropoff_date) {

                                       errorMessage += `<li style="color: red;">${data.error.return_dropoff_date}</li>`;

                                   }

           

                                   if (data.error.return_dropoff_time) {

                                       errorMessage += `<li style="color: red;">${data.error.return_dropoff_time}</li>`;

                                   }

           

                                   $(".error-return_dropoff_date_time").html(errorMessage);

                               } else {

                                   

                                   $(".error-return_dropoff_date_time").empty();

                               }
           

           

                       } else {

                      

                           $(".show_vehicle").html('');

                           $(".show_vehicle").html("<option value='' disabled selected>Select a vehicle</option>");

                           $(".error-pickup_address").text('');

                           $(".error-dropoff_address").text('');

                           $(".error-vehicle_type").text('');

                           $(".error-vehicle").text('');

                           $(".error-no_of_person").text('');

                           $(".error-pickup_date_time").html('');

                           $(".error-return_pickup_date_time").html('');

                           $(".error-return_dropoff_date_time").html('');


           

                           switch (data.status) {

                               case 100:

                                   window.location.href = redirect_url+"/" + data.method + '/' + data.method;

                                   break;

                               case 200:

                               case 300:

                                   $('#booking_form')[0].reset();

                               case 400:

                               case 500:

                                   tblprint = `<div class="alert alert-${data.status == 200 ? 'danger' : 'success'} custom-alert" role="alert">

                                                   <strong>${data.message}</strong>

                                               </div>`;

                                   break;

                               default:

                                   tblprint = `<div class="alert alert-primary" role="alert">

                                                   <strong>You are Not Logged In.<a href=`+login_url+`>Click here to Login!</a></strong>

                                               </div>`;

                                   break;

                           }

           

                           $(".message-booking").html(tblprint);

           

                                setTimeout(function() {

                                   $(".message-booking").html('');

                               }, 4000);

                         

                      }

                   },

                   error: function(jqXHR, textStatus, errorThrown) {

                    $(".hide-13").addClass('d-none');

                       $(".hide-14").removeClass('d-none');

                       var tblprint = `<div class="alert alert-primary" role="alert">

                                                   <strong>You are Not Logged In.<a href=`+login_url+`>Click here to Login!</a></strong>

                                       </div>`;

                       $(".message-booking").html(tblprint);

           

                       setTimeout(function() {

                           $(".message-booking").html('');

                       }, 2000);

                   }

               });

           });

           

           

           function setErrorMessage4(message, element) {

               if (message) {

                   $(element).html(`<li style="color: red;">${message}</li>`);

               } else {

                   $(element).empty(); // Clear the content of the specified element

               }

           }









      

});









