<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        return view('admin.rooms.index');
    }

    public function list()
    {

        $rooms = DB::table('rooms')
            ->where('status', 1)
            ->where('removed', 0)
            ->get();

        foreach ($rooms as $room) {
            $room->bookings = DB::table('booking_order as bo')
                ->join('booking_details as bd', 'bo.booking_id', '=', 'bd.booking_id')
                ->where('bo.room_id', $room->id)
                ->whereIn('bo.booking_status', ['Đã Xác Nhận Đặt Phòng', 'Đã Đặt'])
                ->select('bo.*', 'bd.*')
                ->get();

            $room->room_numbers = DB::table('rooms as r')
                ->join('room_number as rn', 'r.id', '=', 'rn.room_id')
                ->where('r.id', $room->id)
                ->where('r.removed', 0)
                ->select('rn.id as idRoomNum', 'rn.room_num as roomNum', 'rn.status as roomNumStatus')
                ->get();

            foreach ($room->room_numbers as $roomNum) {
                if ($roomNum->roomNumStatus == 1) {
                    $roomNum->info = DB::table('booking_details as bd')
                        ->join('booking_order as bo', 'bo.booking_id', '=', 'bd.booking_id')
                        ->where('bd.room_no', $roomNum->idRoomNum)
                        ->where('bo.booking_status', 'Đã Xác Nhận Đặt Phòng')
                        ->select('bo.*', 'bd.*')
                        ->first();
                }
            }
        }
        return view('admin.rooms.list', compact('rooms'))->render();
    }

    public function addNumber(Request $request)
    {
        $data = $request->all();

        $res = RoomNumber::where('id', $data['room_number_id'])
            ->update([
                'room_num' => $data['room_no'],
            ]);

        return $res === 1 ? 1 : 0;
    }

    public function deleteNumber(Request $request)
    {
        $data = $request->all();

        $res = RoomNumber::where('id', $data['id_room_number_str'])
            ->update([
                'room_num' => null,
            ]);

        return $res === 1 ? 1 : 0;
    }
}
