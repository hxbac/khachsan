<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\UserCred;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AjaxController extends Controller
{
    public function rooms(Request $request)
    {
        $chk_avail = json_decode($request->chk_avail, true);
        $guests = json_decode($request->guests, true);
        $facility_list = json_decode($request->facility_list, true);

        $adults = $guests['adults'] ?? 0;
        $children = $guests['children'] ?? 0;

        // Validate ngày (như cũ)...

        // Lấy setting shutdown
        $settings = Setting::where('sr_no', 1)->first();
        $shutdown = $settings ? $settings->shutdown : false;

        // Query rooms với join facilities, features, ảnh thumb
        $rooms = DB::table('rooms')
            ->leftJoin('room_facilities', 'rooms.id', '=', 'room_facilities.room_id')
            ->leftJoin('facilities', 'room_facilities.facilities_id', '=', 'facilities.id')
            ->leftJoin('room_features', 'rooms.id', '=', 'room_features.room_id')
            ->leftJoin('features', 'room_features.features_id', '=', 'features.id')
            ->select(
                'rooms.*',
                DB::raw('GROUP_CONCAT(DISTINCT facilities.name) as facility_names'),
                DB::raw('GROUP_CONCAT(DISTINCT facilities.id) as facility_ids'),
                DB::raw('GROUP_CONCAT(DISTINCT features.name) as feature_names'),
                DB::raw("(SELECT image FROM room_images WHERE room_images.room_id = rooms.id AND room_images.thumb = 1 LIMIT 1) as thumb_image")
            )
            ->where('rooms.adult', '>=', $adults)
            ->where('rooms.children', '>=', $children)
            ->where('rooms.status', 1)
            ->where('rooms.removed', 0)
            ->groupBy('rooms.id')
            ->get();

        $output = "";
        $count_rooms = 0;

        foreach ($rooms as $room) {
            // Kiểm tra booking trùng ngày (nếu có lọc ngày)
            if (!empty($chk_avail['checkin']) && !empty($chk_avail['checkout'])) {
                $total_bookings = DB::table('booking_order')
                    ->whereIn('booking_status', ['Đã Đặt', 'Đã Xác Nhận Đặt Phòng'])
                    ->where('room_id', $room->id)
                    ->where(function ($query) use ($chk_avail) {
                        $query->whereNotBetween('check_in', [$chk_avail['checkout'], $chk_avail['checkin']])
                            ->orWhereNotBetween('check_out', [$chk_avail['checkout'], $chk_avail['checkin']]);
                    })
                    ->count();

                if ($room->quantity - $total_bookings <= 0) {
                    continue;
                }
            }

            // Kiểm tra filter facilities
            $room_facility_ids = explode(',', $room->facility_ids ?? '');
            $required_facilities = $facility_list['facilities'] ?? [];

            if (count($required_facilities) > 0 && count(array_intersect($required_facilities, $room_facility_ids)) !== count($required_facilities)) {
                continue;
            }

            // Tạo HTML facility
            $facility_names = explode(',', $room->facility_names ?? '');
            $facilities_html = '';
            foreach ($facility_names as $fname) {
                $facilities_html .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>{$fname}</span>";
            }

            // Tạo HTML features
            $feature_names = explode(',', $room->feature_names ?? '');
            $features_html = '';
            foreach ($feature_names as $feaname) {
                $features_html .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>{$feaname}</span>";
            }

            $price = number_format($room->price, 0, '.', ',');

            $room_thumb = $room->thumb_image ? asset('images/rooms/' . $room->thumb_image) : asset('images/rooms/thumbnail.jpg');

            $login = auth()->check() ? 1 : 0;

            $book_btn = "";
            if (!$shutdown) {
                $book_btn = "<button onclick='checkLoginToBook($login, {$room->id})' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Đặt Ngay</button>";
            }

            $output .= "
          <div class='card mb-4 border-0 shadow'>
            <div class='row g-0 p-3 align-items-center'>
              <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                <img src='$room_thumb' class='img-fluid rounded'>
              </div>
              <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                <h5 class='mb-3'>{$room->name}</h5>
                <div class='features mb-3'>
                  <h6 class='mb-1'>Cơ Sở</h6>
                  $features_html
                </div>
                <div class='facilities mb-3'>
                  <h6 class='mb-1'>Tiện Nghi</h6>
                  $facilities_html
                </div>
                <div class='guests'>
                  <h6 class='mb-1'>Khách Hàng</h6>
                  <span class='badge rounded-pill bg-light text-dark text-wrap'>{$room->adult} Người Lớn</span>
                  <span class='badge rounded-pill bg-light text-dark text-wrap'>{$room->children} Trẻ Em</span>
                </div>
              </div>
              <div class='col-md-2 mt-lg-0 mt-md-0 mt-4 text-center'>
                <h6 class='mb-4'>$price ₫ mỗi đêm</h6>
                $book_btn
                <a href='" . route('rooms.detail', ['id' => $room->id]) . "' class='btn btn-sm w-100 btn-outline-dark shadow-none'>Chi Tiết</a>
              </div>
            </div>
          </div>
        ";

            $count_rooms++;
        }

        if ($count_rooms > 0) {
            return $output;
        }
        return "<h3 class='text-center text-danger'>Không có phòng nào !!!</h3>";
    }

    public function register(Request $request)
    {
        if ($request->pass !== $request->cpass) {
            return 'pass_mismatch';
        }

        $data = $request->all();

        $exists = UserCred::where('email', $data['email'])
            ->orWhere('phonenum', $data['phonenum'])
            ->first();

        if ($exists) {
            return $exists->email === $data['email'] ? 'email_already' : 'phone_already';
        }

        // Xử lý upload ảnh
        $profilePath = null;
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $stored = $file->move(public_path('images/users'), $filename);
            $profilePath = $stored ? 'images/users/' . $filename : null;
        }

        if (!$profilePath) {
            return 'inv_img';
        }

        // Tạo người dùng
        $user = new UserCred();
        $user->name        = $data['name'];
        $user->email       = $data['email'];
        $user->address     = $data['address'];
        $user->phonenum    = $data['phonenum'];
        $user->pincode     = $data['pincode'];
        $user->dob         = $data['dob'];
        $user->profile     = $profilePath;
        $user->password    = Hash::make($data['pass']);

        if ($user->save()) {
            return 1;
        }

        return 'ins_failed';
    }

    public function login(Request $request)
    {
        $data = $request->all();

        $user = UserCred::where('email', $data['email_mob'])
            ->orWhere('phonenum', $data['email_mob'])
            ->first();

        if (!$user) {
            return 'inv_email_mob';
        }

        if (!$user->email_verified_at) {
            return 'not_verified';
        }

        if ($user->status == 0) {
            return 'inactive';
        }

        $credentials = [
            'email' => $user->email,
            'password' => $data['pass'],
        ];

        if (Auth::attempt($credentials)) {
            return 1;
        }

        return 'invalid_pass';
    }
}
