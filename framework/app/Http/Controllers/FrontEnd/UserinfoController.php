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

use App\Traits\FirebasePassword;

class UserinfoController extends Controller {

    use FirebasePassword;

    public function update_profile(Request $request)
    {

       
        $rules =[
            'first_name'=>'required',
            'last_name'=>'required',
            'gender'=>'required',
            'email'=>'required|email|unique:users,email,'.Auth::user()->id,
            'phone'=>'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        $name=$request->first_name.' '.$request->last_name;

        $user=User::find(Auth::user()->id);
        $user->name=$name;
        $user->first_name=$request->first_name;
        $user->last_name=$request->last_name;
        $user->email=$request->email;
        $user->gender=$request->gender;
        $user->mobno=$request->phone;

        if(isset($request->address))
        {
            $user->address=$request->address;
        }



        if(isset($request->image))
        {
            $destinationImage='./uploads';
		    $official_profile=$request->image;
            $offical_img_path=uniqid().$official_profile->getClientOriginalName();
            $official_profile->move($destinationImage, $offical_img_path);
            $user->profile_pic=$offical_img_path;
        }

        if($user->save())
        {
            return response()->json(['status'=>100]);
        }
        else
        {
            return response()->json(['status'=>200]);
        }

    }

    public function update_password(Request $request)
    {
        $rules = [
            'password' => 'required',
            'new_password' => 'required|min:6|max:18|regex:/^(?=(.*\d){2,})(?=.*[a-z])(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,18}$/',
            'confirm_password' => 'required|same:new_password',
        ];

        $messages = [
            'password.required' => 'The current password is required.',
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password must be at least 6 characters long.',
            'new_password.max' => 'The new password must not exceed 18 characters.',
            'new_password.regex' => 'The new password must contain at least 2 digits, 1 lowercase letter, and 1 special character (@$!%*?&).',
            'confirm_password.required' => 'The confirmation password is required.',
            'confirm_password.same' => 'The confirmation password does not match the new password.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }

        if (Hash::check($request->password, Auth::user()->password)) {
            $user = User::find(Auth::user()->id);
            $user->password = bcrypt($request->new_password);

            if ($user->save()) {
                $this->newpassword($user->email, $request->new_password);

                return response()->json(['status' => 100]);
            } else {
                return response()->json(['status' => 200]);
            }
        } else {
            return response()->json(['status' => 200]);
        }
    }



}