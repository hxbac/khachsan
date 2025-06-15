<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function check_in(Request $request) {
        return view('admin.bookings.check_in');
    }
}
