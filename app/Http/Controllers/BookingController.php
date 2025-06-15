<?php

namespace App\Http\Controllers;

use App\Models\BookingDetail;
use App\Models\BookingOrder;
use App\Models\Room;
use App\Models\RoomNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{
    public function confirm(Request $request)
    {
        $roomId = $request->query('id');

        if (!$roomId || config('settings.shutdown')) {
            return redirect()->route('rooms.index');
        }

        if (!Auth::check()) {
            return redirect()->route('rooms.index');
        }

        $room = Room::where('id', $roomId)
            ->where('status', 1)
            ->where('removed', 0)
            ->first();

        if (!$room) {
            return redirect('rooms');
        }

        $price = number_format($room->price, 0, '.', ',');

        Session::put('room', [
            'id' => $room->id,
            'name' => $room->name,
            'price' => $room->price,
            'payment' => null,
            'available' => false,
        ]);

        $user = Auth::user();

        $quantityResult = RoomNumber::where('room_id', $room->id)
            ->whereNull('room_num')
            ->where('status', 0)
            ->select(DB::raw('COUNT(*) as total_room'))
            ->first();

        $totalRoom = $quantityResult->total_room ?? 0;

        $roomThumb = asset('images/rooms/thumbnail.jpg');
        $thumb = DB::table('room_images')
            ->where('room_id', $room->id)
            ->where('thumb', 1)
            ->first();

        if ($thumb) {
            $roomThumb = asset('images/rooms/' . $thumb->image);
        }

        return view('home.booking.confirm', compact('room', 'price', 'user', 'totalRoom', 'roomThumb'));
    }

    public function checkAvailability(Request $request)
    {
        $data = $request->all();

        $today = now()->startOfDay();
        $checkin = \Carbon\Carbon::parse($data['check_in'])->startOfDay();
        $checkout = \Carbon\Carbon::parse($data['check_out'])->startOfDay();

        if ($checkin->eq($checkout)) {
            return response()->json(['status' => 'check_in_out_equal']);
        }

        if ($checkout->lt($checkin)) {
            return response()->json(['status' => 'check_out_earlier']);
        }

        if ($checkin->lt($today)) {
            return response()->json(['status' => 'check_in_earlier']);
        }

        $roomSession = Session::get('room');
        $roomId = $roomSession['id'];

        $totalBookings = BookingOrder::whereIn('booking_status', ['Đã Đặt', 'Đã Xác Nhận Đặt Phòng'])
            ->where('room_id', $roomId)
            ->where(function ($query) use ($data) {
                $query->where('check_out', '>', $data['check_in'])
                    ->where('check_in', '<', $data['check_out']);
            })
            ->count();

        $totalRooms = RoomNumber::where('room_id', $roomId)
            ->whereNotNull('room_num')
            ->count();

        $availableRooms = $totalRooms - $totalBookings;

        if ($availableRooms <= 0) {
            return response()->json(['status' => 'unavailable']);
        }

        $price = $roomSession['price'];
        $nights = $checkin->diffInDays($checkout);
        $days = $nights + 1;
        $payment = $price * $nights;
        $totalPayment = $payment * $data['check_quantity'];

        $roomSession['payment'] = $payment;
        $roomSession['available'] = true;
        Session::put('room', $roomSession);

        return response()->json([
            'status' => 'available',
            'c_rooms' => $availableRooms,
            'nights' => $nights,
            'days' => $days,
            'payment' => number_format($payment, 0, '.', ','),
            'total_payment' => number_format($totalPayment, 0, '.', ','),
            'out_of_room' => $availableRooms - $data['check_quantity'],
        ]);
    }

    public function payNow(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('home.index');
        }

        $data = $request->all();

        $userId = Auth::id();
        $roomSession = Session::get('room');

        for ($i = 1; $i <= $data['quantity']; $i++) {
            $orderId = 'ORD_' . $userId . random_int(11, 99);
            $txnAmount = $roomSession['payment'];

            // Insert into booking_order
            $bookingId = BookingOrder::insertGetId([
                'user_id' => $userId,
                'room_id' => $roomSession['id'],
                'check_in' => $data['checkin'],
                'check_out' => $data['checkout'],
                'order_id' => $orderId,
            ]);

            BookingDetail::insert([
                'booking_id' => $bookingId,
                'room_name' => $roomSession['name'],
                'price' => $roomSession['price'],
                'total_pay' => $txnAmount,
                'user_name' => $data['name'],
                'phonenum' => $data['phonenum'],
                'address' => $data['address'],
            ]);
        }

        return redirect()->route('user.bookings');
    }

    public function cancel(Request $request) {
        $userId = Auth::id();
        $bookingId = $request->id;

        $result = BookingOrder::where('booking_id', $bookingId)
            ->where('user_id', $userId)
            ->update([
                'booking_status' => 'Đã Huỷ',
                'refund' => 0,
            ]);

        return $result;
    }
}
