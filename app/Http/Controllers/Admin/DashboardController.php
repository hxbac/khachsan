<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingOrder;
use App\Models\RatingReview;
use App\Models\Room;
use App\Models\Setting;
use App\Models\UserCred;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function handleLogin(Request $request)
    {
        $credentials = [
            'admin_name' => $request->admin_name,
            'password' => $request->admin_pass,
        ];

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->with('error', 'Đăng nhập không thành công - Thông tin xác thực không hợp lệ!');
        }
    }

    public function index()
    {
        $is_shutdown = Setting::value('shutdown');

        $current_bookings = BookingOrder::selectRaw("
            COUNT(CASE WHEN booking_status = 'Đã Thanh Toán' AND arrival = 0 THEN 1 END) AS new_bookings,
            COUNT(CASE WHEN booking_status = 'Đã Huỷ' AND refund = 0 THEN 1 END) AS refund_bookings
        ")
            ->first();

        $unread_queries = UserQuery::where('seen', 0)
            ->count();

        $unread_reviews = RatingReview::where('seen', 0)
            ->count();

        $current_users = UserCred::selectRaw("
            COUNT(id) AS total,
            COUNT(CASE WHEN status = 1 THEN 1 END) AS active,
            COUNT(CASE WHEN status = 0 THEN 1 END) AS inactive,
            COUNT(CASE WHEN is_verified = 0 THEN 1 END) AS unverified
        ")
            ->first();

        $total_rooms = Room::count();

        $kh_datphong = BookingOrder::where('booking_status', 'Đã Đặt')
            ->count();

        $tong_doanhthu = BookingOrder::sum('trans_amt');

        $total_rating = RatingReview::count();

        $total_khachhang = UserCred::count();

        $total_phanhoi = UserQuery::count();

        $total_phonghuy = BookingOrder::where('booking_status', 'Đã Huỷ')
            ->count();

        $tong_sophong = Room::sum('quantity');

        $total_phongdat = BookingOrder::where('booking_status', 'Đã Xác Nhận Đặt Phòng')
            ->count();

        return view('admin.index', compact(
            'is_shutdown',
            'current_bookings',
            'unread_queries',
            'unread_reviews',
            'current_users',
            'total_rooms',
            'kh_datphong',
            'tong_doanhthu',
            'total_rating',
            'total_khachhang',
            'total_phanhoi',
            'total_phonghuy',
            'tong_sophong',
            'total_phongdat'
        ));
    }
}
