<?php
/*
@copyright
Fleet Manager v7.0
Copyright (C) 2017-2025 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\ReviewModel;
use Illuminate\Support\Facades\Auth;

class ReviewRatings extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $userType = $user->user_type;

        // Base review query
        $reviews = ReviewModel::orderBy('id', 'desc');

        // If the user is a vehicle owner, only show reviews of their drivers
        if ($userType === 'V') {
            $reviews->where('driver_id', $userId); // adjust this if driver-user relation differs
        }

        $data['reviews'] = $reviews->get();

        return view('reviews', $data);
    }
}
