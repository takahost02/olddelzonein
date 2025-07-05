<?php

/*

@copyright

Fleet Manager v7.0

Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.

Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;

use App\Mail\ForgotPassword;

use App\Mail\VehicleBooked;

use App\Model\Address;

use App\Model\Bookings;

use App\Model\Expense;

use App\Model\CompanyServicesModel;

use App\Model\Hyvikk;

use App\Model\MessageModel;

use App\Model\PasswordResetModel;

use App\Model\TeamModel;

use App\Model\Testimonial;

use App\Model\User;

use App\Model\VehicleModel;

use App\Model\VehicleTypeModel;

use App\Model\ReviewModel;

use Auth;

use Edujugon\PushNotification\PushNotification;

use Exception;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth as Login;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;

use session;

use Illuminate\Http\Response;

use DB;

use App\Model\VehicleBreakdown;

use App\Model\DriverAlertModel;

use Firebase;



class DashboardController extends Controller {



    public function index()



    {   



        //All Bookings Counts



        $all=Bookings::where('customer_id',Auth::user()->id)->whereYear('bookings.created_at', date('Y'))->count();



        $monthsall = [];



        $countsall = [];







        



        for ($i = 1; $i <= 12; $i++) {



            $monthsall[] = date('M', mktime(0, 0, 0, $i, 1));



            $countsall[] = 0;



        }







        $data_all = Bookings::selectRaw('MONTH(created_at) as month, COUNT(*) as count')



                    ->where('customer_id',Auth::user()->id)->groupBy('month')

                    ->whereYear('bookings.created_at', date('Y'))

                    ->get();







        foreach ($data_all as $item) {



            $monthIndex = $item->month - 1; 



            $countsall[$monthIndex] = $item->count;



        }







       //Bookings Cancelled Counts







        $cancel_booking=Bookings::join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')



		->where('bookings.customer_id',Auth::user()->id)->where('bookings_meta.key', 'ride_status')



		->where('bookings_meta.value', 'Cancelled')



        ->whereYear('bookings.created_at', date('Y'))->count();







     



        $monthscancel = [];



        $countscancel = [];







        



        // Initialize counts for each month



        for ($i = 1; $i <= 12; $i++) {



            $monthscancel[] = date('M', mktime(0, 0, 0, $i, 1));



            $countscancel[] = 0;



        }



        







        $data_cancel = Bookings::selectRaw('MONTH(bookings.created_at) as month, COUNT(*) as count')



            ->where('bookings.customer_id',Auth::user()->id)



            ->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')



            ->where('bookings_meta.key', 'ride_status')



            ->where('bookings_meta.value', 'Cancelled')



            ->groupBy('month')



            ->whereYear('bookings.created_at', date('Y'))



            ->get();



        



        foreach ($data_cancel as $item) {



            $monthIndex = $item->month - 1; 



            $countscancel[$monthIndex] = $item->count;



        }











        //Bookings Completed Counts







        $complete_booking=Bookings::join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')



		->where('bookings.customer_id',Auth::user()->id)



        ->where('bookings_meta.key', 'ride_status')



		->where('bookings_meta.value', 'Completed')



        ->whereYear('bookings.created_at', date('Y'))



        ->count();







     



        $monthscomplete = [];



        $countscomplete = [];



        



        // Initialize counts for each month



        for ($i = 1; $i <= 12; $i++) {



            $monthscomplete[] = date('M', mktime(0, 0, 0, $i, 1));



            $countscomplete[] = 0;



        }



        



        $data_complete = Bookings::selectRaw('MONTH(bookings.created_at) as month, COUNT(*) as count')



        ->where('bookings.customer_id',Auth::user()->id)   



        ->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')



            ->where('bookings_meta.key', 'ride_status')



            ->where('bookings_meta.value', 'Completed')



            ->groupBy('month')



            ->whereYear('bookings.created_at', date('Y'))



            ->get();



        







        foreach ($data_complete as $item) {



            $monthIndex = $item->month - 1; 



            $countscomplete[$monthIndex] = $item->count;



        }











        //Bookings Pending Counts



        $pending_booking = Bookings::join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')



        ->where('bookings.customer_id',Auth::user()->id)



        ->where('bookings_meta.key', 'ride_status')



        ->whereIn('bookings_meta.value', ['Pending','Upcoming'])



        ->whereYear('bookings.created_at', date('Y'))



        ->count();



    



        $monthspending = [];



        $countspending = [];



        



        // Initialize counts for each month



        for ($i = 1; $i <= 12; $i++) {



            $monthspending[] = date('M', mktime(0, 0, 0, $i, 1));



            $countspending[] = 0; // Initialize count to 0 for each month



        }



        



        $data_pending = Bookings::selectRaw('MONTH(bookings.created_at) as month, COUNT(*) as count')



        ->where('bookings.customer_id',Auth::user()->id)    



        ->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')



            ->where('bookings_meta.key', 'ride_status')



            ->whereIn('bookings_meta.value', ['Pending','Upcoming'])



            ->groupBy('month')



            ->whereYear('bookings.created_at', date('Y'))



            ->get();



        



        foreach ($data_pending as $item) {



            $monthIndex = $item->month - 1; 



            $countspending[$monthIndex] = $item->count;



        }







    



        //expense count current year 







        $monthsexpense = [];



     



        for ($i = 1; $i <= 12; $i++) {



            $monthsexpense[] = date('M', mktime(0, 0, 0, $i, 1));

        }

        

        $data_expense = Bookings::where('bookings.customer_id', Auth::user()->id)

        ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')

        ->whereIn('booking_payments.payment_status', ['succeeded', 'Success'])

        ->whereYear('booking_payments.created_at', date('Y'))

        ->selectRaw('MONTH(booking_payments.created_at) as month, SUM(booking_payments.amount) as total_amount')

        ->groupBy('month')

        ->get();

    

        // Initialize array for all 12 months (default to 0)

        $countsexpense = array_fill(0, 12, 0);

        

        foreach ($data_expense as $item) {

            $monthIndex = $item->month - 1; // Convert month number to array index

            $countsexpense[$monthIndex] = $item->total_amount; // Store the total amount for that month

        }

    



        $ongoing_booking=Bookings::join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')



		->where('bookings.customer_id',Auth::user()->id)



        ->where('bookings_meta.key', 'ride_status') 

        ->whereYear('bookings.created_at', date('Y'))

        ->where('bookings_meta.value', 'Ongoing')->count();





        return view('customer_dashboard.dashboard',compact('all','monthsall', 'countsall',



        'cancel_booking','monthscancel','countscancel',



        'complete_booking','monthscomplete','countscomplete',



        'pending_booking','monthspending','countspending',



        'monthsexpense','countsexpense','ongoing_booking',



        ));

        



    }





    public function getinfo()

    {

        $Places = Bookings::select('dest_addr')

        ->groupBy('dest_addr')

        ->get()

        ->count();



        $totalMinutes = Bookings::join('bookings_meta', 'bookings.id', '=', 'bookings_meta.booking_id')

        ->where('bookings.customer_id',Auth::user()->id)

        ->where('bookings_meta.key', 'total_time')

        ->whereNotNull('bookings_meta.value')

        ->where('bookings_meta.value', '<>', '')

        ->selectRaw('SUM(HOUR(bookings_meta.value) * 60 + MINUTE(bookings_meta.value)) AS total_minutes')

        ->value('total_minutes');

       

        

        $total_kms_sum = Bookings::join('bookings_meta', 'bookings.id', '=', 'bookings_meta.booking_id')

        ->where('bookings.customer_id',Auth::user()->id)

        ->where('bookings_meta.key', 'total_kms')

        ->whereNotNull('bookings_meta.value')

        ->where('bookings_meta.value', '<>', '')

        ->selectRaw('SUM(CAST(bookings_meta.value AS DECIMAL(10,2))) AS total_kms_sum')

        ->value('total_kms_sum');

    

         return response()->json(['places'=>$Places,'minutes'=>$totalMinutes,'kms'=>(int)$total_kms_sum]);

    }

    

    public function show_info()

    {

 

         $data=Bookings::select('bookings.*')->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')

		->where('bookings_meta.key', 'ride_status')

		->where('bookings_meta.value', 'Ongoing')->where('bookings.customer_id',Auth::user()->id)->orderBy('bookings.id','DESC')->get();

        return view('customer_dashboard.show_dashboard',compact('data'));

    }



    public function single_booking_info(Request $request)

    {

     



        $data=Bookings::select('bookings.*')->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')

		->where('bookings_meta.key', 'ride_status')

		->where('bookings_meta.value', 'Ongoing')->where('bookings.id',$request->id)->first();

        $vehicle = null;

        $v_type = null;

        $driver = null;



        if(isset($data->vehicle_id))

        {

            $vehicle=VehicleModel::where('id',$data->vehicle_id)->first();

        }

        if(isset($vehicle->type_id))   

        {   

            $v_type=VehicleTypeModel::where('id',$vehicle->type_id)->first();

            

        }

         if(isset($data->driver_id))

        {

            $driver=User::where('id',$data->driver_id)->first();

        }



        

        $rating = ReviewModel::where('booking_id', $data->id)->first();

        if ($rating != null) {

            $r = $rating->ratings;

        } else {

            $r = "4";

        }



              

        $ve_breakdown=VehicleBreakdown::all();



        $driver_alert=DriverAlertModel::all();



    

        return view('customer_dashboard.single_booking_info',compact('data','vehicle','v_type','driver','r','ve_breakdown','driver_alert'));

    }



    public function single_ongoing_booking(Request $request)

    {

        $data=Bookings::select('bookings.*')->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')

		->where('bookings_meta.key', 'ride_status')

		->where('bookings_meta.value', 'Ongoing')->where('bookings.id',$request->booking_id)->where('bookings.customer_id',Auth::user()->id)->latest()->get();

       



        return view('customer_dashboard.show_dashboard',compact('data'));

    }



    public function booking_details($id)

    {

        $data=Bookings::select('bookings.*')->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')

		->where('bookings.id',$id)->first();

        $vehicle = null;

        $v_type = null;

        $driver = null;



        if(isset($data->vehicle_id))

        {

            $vehicle=VehicleModel::where('id',$data->vehicle_id)->first();

        }

        if(isset($vehicle->type_id))   

        {   

            $v_type=VehicleTypeModel::where('id',$vehicle->type_id)->first();

            

        }

         if(isset($data->driver_id))

        {

            $driver=User::where('id',$data->driver_id)->first();

        }



     

        $rating = ReviewModel::where('booking_id', $data->id)->first();

        if ($rating != null) {

            $r = $rating->ratings;

        } else {

            $r = "-";

        }



        return view('customer_dashboard.booking_details',compact('data','vehicle','v_type','driver','r'));

    }



    public function booking_details_ongoing($id)

    {



         $data=Bookings::select('bookings.*')->join('bookings_meta', 'bookings_meta.booking_id', '=', 'bookings.id')

		->where('bookings.id',$id)->first();

        $vehicle = null;

        $v_type = null;

        $driver = null;



        if(isset($data->vehicle_id))

        {

            $vehicle=VehicleModel::where('id',$data->vehicle_id)->first();

        }

        if(isset($vehicle->type_id))   

        {   

            $v_type=VehicleTypeModel::where('id',$vehicle->type_id)->first();

            

        }

         if(isset($data->driver_id))

        {

            $driver=User::where('id',$data->driver_id)->first();

        }



        $rating = ReviewModel::where('booking_id', $data->id)->first();

        if ($rating != null) {

            $r = $rating->ratings;

        } else {

            $r = "-";

        }



        $ve_breakdown=VehicleBreakdown::all();



        $driver_alert=DriverAlertModel::all();



        

        return view('customer_dashboard.booking_details_ongoing',compact('data','vehicle','v_type','driver','r','ve_breakdown','driver_alert'));





    }





   







}