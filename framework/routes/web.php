<?php






Route::group(['middleware' => ['IsInstalled', 'lang_check_user', 'front_enable']], function () {


    Route::get('/login', function () {
        return view('customer_dashboard.log_in');
    })->name('log_in');
    
    Route::get('/sign_up', function () {
        return view('customer_dashboard.sign_up');
    })->name('sign_up');
    
    Route::get('/forgot-password', function () {
        return view('customer_dashboard.forgot_password');
    })->name('forgot_password');
    


    // define all routes here
   
    Route::get('show_document', 'FrontEnd\HomeController@show_document');

    Route::get('current-location','FrontEnd\BookingController@current_location')->middleware('auth_user');


    Route::get('get_ratings', 'FrontEnd\HomeController@get_ratings');

    Route::get('get_free_vehicle', 'FrontEnd\HomeController@get_free_vehicle');

   
    Route::get('get-vehicle','FrontEnd\BookingController@get_vehicle')->middleware('auth_user');
    
   
    Route::get('/', 'FrontEnd\HomeController@index')->name('frontend.home');
      Route::get('admin/drivers/{id}/edit', 'DriversController@edit')->name('drivers.edit');
        Route::get('admin/drivers/{driver}/send', 'DriversController@send')->name('drivers.send.form');
    // Route::get('edit_profile', 'FrontEnd\HomeController@edit_profile')->middleware('auth_user')->name('frontend.edit_profile');
    Route::post('edit_profile', 'FrontEnd\HomeController@edit_profile_post')->middleware('auth_user');
    Route::get('contact', 'FrontEnd\HomeController@contact')->name('frontend.contact');
    Route::get('about', 'FrontEnd\HomeController@about')->name('frontend.about');
    Route::get('service', 'FrontEnd\HomeController@service')->name('frontend.service');
    Route::get('solutions', 'FrontEnd\HomeController@solutions')->name('frontend.solutions');
    Route::get('network', 'FrontEnd\HomeController@network')->name('frontend.network');
    Route::post('user-login', 'FrontEnd\HomeController@user_login');
    Route::get('booking-history/{id}', 'FrontEnd\HomeController@booking_history')->middleware('auth_user')->name('frontend.booking_history');
    Route::post('user-logout', 'FrontEnd\HomeController@user_logout');
   // Route::get('forgot-password', 'FrontEnd\HomeController@forgot');
    Route::post('forgot-password', 'FrontEnd\HomeController@send_reset_link');
    Route::get('forgot-password/{token}', 'FrontEnd\HomeController@forget_email')->name('new_password');
    Route::get('reset-password/{token}', 'FrontEnd\HomeController@reset');
    Route::post('reset-password', 'FrontEnd\HomeController@reset_password');
    Route::post('reset-password-email', 'FrontEnd\HomeController@reset_password_email');
    Route::post('user-register', 'FrontEnd\HomeController@customer_register');
    Route::post('send-enquiry', 'FrontEnd\HomeController@send_enquiry')->name('user.enquiry');
    Route::post('book', 'FrontEnd\HomeController@book')->middleware('auth_user');
    Route::patch('/update/{id}', 'HomeController@update')->name('book.update');


    Route::get('load_bookinghistory', 'FrontEnd\HomeController@load_bookinghistory');
            Route::patch('/admin/drivers/{driver}/send', 'DriversController@send')->name('drivers.send');


 

     Route::get('dashboard','FrontEnd\DashboardController@index')->name('dashboard')->middleware('auth_user');
     Route::get('getinfo','FrontEnd\DashboardController@getinfo')->name('dashboard.getinfo')->middleware('auth_user');

     Route::get('show_info','FrontEnd\DashboardController@show_info')->name('dashboard.showinfo')->middleware('auth_user');

     Route::get('single_booking_info','FrontEnd\DashboardController@single_booking_info')->name('dashboard.single_booking_info')->middleware('auth_user');

     Route::get('single_ongoing_booking','FrontEnd\DashboardController@single_ongoing_booking')->name('dashboard.single_ongoing_booking')->middleware('auth_user');
   

     Route::get('booking_details/{id}','FrontEnd\DashboardController@booking_details')->name('dashboard.booking_details')->middleware('auth_user');

     Route::get('booking_details_ongoing/{id}','FrontEnd\DashboardController@booking_details_ongoing')->name('dashboard.booking_details_ongoing')->middleware('auth_user');

     Route::post('update-profile','FrontEnd\UserinfoController@update_profile')->name('update.profile')->middleware('auth_user');

     Route::post('update-password','FrontEnd\UserinfoController@update_password')->name('update.password')->middleware('auth_user');

     Route::get('create_booking','FrontEnd\BookingController@index')->name('create_booking')->middleware('auth_user');

    Route::get('booking-fetch','FrontEnd\BookingController@booking_fetch')->name('booking.fetch')->middleware('auth_user');

    Route::post('save-booking','FrontEnd\BookingController@save_booking')->name('booking.save')->middleware('auth_user');

    Route::get('/show-booking-info','FrontEnd\BookingController@show_booking_info')->middleware('auth_user');

    
    Route::get('/bookings/invoice/{id}', 'FrontEnd\BookingController@invoice');

    Route::get('invoice-print/{id}', 'FrontEnd\BookingController@invoice_print');


    Route::get('/bookings/receipt/{id}', 'FrontEnd\BookingController@receipt');

    Route::get('receipt-print/{id}', 'FrontEnd\BookingController@receipt_print');


    Route::get('my-bookings', function () {
        return view('customer_dashboard.booking');
    })->name('my_bookings')->middleware('auth_user');

    Route::get('new_profile', function () {
        return view('customer_dashboard.new_profile');
    })->name('user_profile')->middleware('auth_user');


    Route::get('save-booking-alert','FrontEnd\BookingController@save_booking_alert')->middleware('auth_user');


});

// Route::get('/', 'FrontendController@index')->middleware('IsInstalled');
// if (env('front_enable') == 'no') {
//     Route::get('/', function () {
//         return redirect('admin');
//     })->middleware('IsInstalled');
// } else {
//     Route::get('/', 'FrontendController@index')->middleware('IsInstalled');
// }

Route::post('check-license-plate', 'VehiclesController@checkLicensePlate')->name('check.license.plate');
Route::get('dtable-posts-lists', 'DatatablesController@index');
Route::get('dtable-custom-posts', 'DatatablesController@get_custom_posts');

Route::post('redirect-payment', 'FrontEnd\HomeController@redirect_payment')->name('redirect-payment');
Route::get('redirect-payment/{method}/{booking_id}', 'FrontEnd\HomeController@redirect');

Route::get('installation', 'LaravelWebInstaller@index');
Route::post('installed', 'LaravelWebInstaller@install');
Route::get('installed', 'LaravelWebInstaller@index');
Route::get('migrate', 'LaravelWebInstaller@db_migration');
Route::get('migration', 'LaravelWebInstaller@migration');
Route::get('upgrade', 'UpdateVersion@upgrade')->middleware('canInstall');
Route::get('upgrade3', 'UpdateVersion@upgrade3')->middleware('canInstall');
Route::get('upgrade4', 'UpdateVersion@upgrade4')->middleware('canInstall');
Route::get('upgrade4.0.2', 'UpdateVersion@upgrade402')->middleware('canInstall');
Route::get('upgrade4.0.3', 'UpdateVersion@upgrade403')->middleware('canInstall');
Route::get('upgrade5', 'UpdateVersion@upgrade5')->middleware('canInstall');
Route::get('upgrade6', 'UpdateVersion@upgrade6')->middleware('canInstall');
Route::get('upgrade6.0.1', 'UpdateVersion@upgrade601')->middleware('canInstall');
Route::get('upgrade6.0.2', 'UpdateVersion@upgrade602')->middleware('canInstall');
Route::get('upgrade6.0.3', 'UpdateVersion@upgrade603')->middleware('canInstall');
Route::get('upgrade6.1', 'UpdateVersion@upgrade61')->middleware('canInstall');

// stripe payment integration
Route::get('stripe/{booking_id}', 'PaymentController@stripe');
Route::get('stripe-success', 'PaymentController@stripe_success');
Route::get('stripe-cancel', 'PaymentController@stripe_cancel');

// paystack payment integration
// Route::get('paystack','PaymentController@paystack');
Route::get('paystack/{booking_id}', 'PaymentController@paystack');
Route::get('paystack-success','PaymentController@paystack_callback');

Route::get('transaction','PaymentController@transaction');

// razorpay payment integration
Route::get('razorpay/{booking_id}', 'PaymentController@razorpay');
Route::post('razorpay-success', 'PaymentController@razorpay_success');
Route::get('razorpay-failed', 'PaymentController@razorpay_failed');
// routes/web.php or routes/admin.php
Route::post('admin/vehicles-bookings-fetch', [VehiclesController::class, 'fetch_booking_data'])->name('vehicles.bookings.fetch');


// cash payment
Route::get('cash/{booking_id}', 'PaymentController@cash');

Route::get('sample-payment', function () {
    return view('payments.test_pay');
});

// Route::post('redirect-payment', 'PaymentController@redirect_payment');

// Route::get('all-data', function () {
//     $bookings = BookingPaymentsModel::latest()->get();
//     foreach ($bookings as $booking) {
//         if ($booking->payment_details != null) {
//             echo "<pre>";
//             print_r(json_decode($booking->payment_details));
//             echo "---------------------------------------------<br>";
//         }
//     }
// });

Route::namespace ('FrontEnd')->group(function () {
	Route::post('user-booking-history/{id}', 'FrontendApiController@user_booking_history');
	Route::post('booking-history/{id}', 'FrontendApiController@booking_history');
	Route::post('book-now', 'FrontendApiController@book_now');
	Route::post('book-later', 'FrontendApiController@book_later');
});
