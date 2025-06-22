<?php

namespace App\Http\Controllers;

use App\Models\UserCred;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function verify(Request $request) {
        $user = UserCred::where('token', $request->token)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->is_verified = 1;
            $user->token = null;
            $user->save();

            Auth::loginUsingId($user->id);
            session()->flash('success', 'Xác thực email thành công!');
        }
        return redirect()->route('home.index');
    }

    public function show()
    {
        $user = Auth::user();
        return view('home.users.show', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $exists = UserCred::where('phonenum', $request->phonenum)
            ->where('id', '<>', $user->id)
            ->exists();

        if ($exists) {
            return 'phone_already';
        }

        $updated = UserCred::where('id', $user->id)
            ->limit(1)
            ->update([
                'name' => $request->name,
                'address' => $request->address,
                'phonenum' => $request->phonenum,
                'pincode' => $request->pincode,
                'dob' => $request->dob,
            ]);

        if ($updated) {
            return 1;
        }

        return 0;
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

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

        $updated = UserCred::where('id', $user->id)
            ->limit(1)
            ->update(['profile' => $profilePath]);

        if ($updated) {
            return 1;
        }

        return 0;
    }

    public function changePassword(Request $request)
    {
        $user = UserCred::find(Auth::user()->id);
        $user->password = Hash::make($request->new_pass);
        if ($user->save()) {
            return 1;
        }
        return 0;
    }

    public function bookings()
    {
        $userId = Auth::id();

        $bookings = DB::table('booking_order AS bo')
            ->join('booking_details AS bd', 'bo.booking_id', '=', 'bd.booking_id')
            ->where('bo.user_id', $userId)
            ->orderByDesc('bo.booking_id')
            ->get()
            ->map(function ($booking) {
                // Format ngày và giá
                $booking->price = number_format($booking->price, 0, '.', ',');
                $booking->total_pay = number_format($booking->total_pay, 0, '.', ',');

                $booking->check_in = \Carbon\Carbon::parse($booking->check_in)->format('d-m-Y');
                $booking->check_out = \Carbon\Carbon::parse($booking->check_out)->format('d-m-Y');
                $booking->datentime = \Carbon\Carbon::parse($booking->datentime)->format('d-m-Y');

                // Số phòng
                if (in_array($booking->booking_status, ['Đã Thanh Toán', 'Đã Xác Nhận Đặt Phòng']) && $booking->room_no) {
                    $room = DB::table('room_number')->where('id', $booking->room_no)->value('room_num');
                    $booking->room_number = $room ? 'Số Phòng: ' . $room : null;
                }

                // Trạng thái
                switch ($booking->booking_status) {
                    case 'Đã Thanh Toán':
                        $booking->status_bg = 'bg-success';
                        if ($booking->arrival == 1 && $booking->rate_review == 0) {
                            $booking->action = 'review';
                        } elseif ($booking->arrival == 0) {
                            $booking->action = 'cancel';
                        }
                        break;

                    case 'Đã Huỷ':
                        $booking->status_bg = 'bg-danger';
                        break;

                    case 'Đã Xác Nhận Đặt Phòng':
                        $booking->status_bg = 'bg-primary';
                        break;

                    default:
                        $booking->status_bg = 'bg-warning';
                        $booking->action = 'cancel';
                        break;
                }

                return $booking;
            });

        return view('home.users.bookings', compact('bookings'));
    }

    public function review(Request $request) {
        $userId = Auth::id();

        $affected = DB::table('booking_order')
            ->where('booking_id', $request->booking_id)
            ->where('user_id', $userId)
            ->update(['rate_review' => 1]);

        $inserted = DB::table('rating_review')->insert([
            'booking_id' => $request->booking_id,
            'room_id'    => $request->room_id,
            'user_id'    => $userId,
            'rating'     => $request->rating,
            'review'     => $request->review,
        ]);

        session()->flash('success', 'Cảm ơn bạn đã đánh giá!');
        return response()->json(1);
    }
}
