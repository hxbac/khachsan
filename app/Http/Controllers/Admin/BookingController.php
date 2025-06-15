<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Mpdf\Mpdf;

class BookingController extends Controller
{
    public function index()
    {
        return view('admin.bookings.index');
    }

    public function get_list(Request $request)
    {
        $search = '%' . $request->input('search') . '%';

        $bookings = DB::table('booking_order as bo')
            ->join('booking_details as bd', 'bo.booking_id', '=', 'bd.booking_id')
            ->whereIn('bo.booking_status', ['Đã Thanh Toán', 'Đã Huỷ', 'Đã Xác Nhận Đặt Phòng'])
            ->where(function ($query) use ($search) {
                $query->where('bo.order_id', 'like', $search)
                    ->orWhere('bd.phonenum', 'like', $search)
                    ->orWhere('bd.user_name', 'like', $search);
            })
            ->orderByDesc('bo.booking_id')
            ->select('bo.*', 'bd.*')
            ->get();

        if ($bookings->isEmpty()) {
            return response("<b>Không tìm thấy dữ liệu nào!</b>");
        }

        $tableData = '';
        $i = 1;

        foreach ($bookings as $data) {
            $roomNoData = '';

            if (in_array($data->booking_status, ['Đã Thanh Toán', 'Đã Xác Nhận Đặt Phòng'])) {
                $room = DB::table('room_number')->where('id', $data->room_no)->first();
                if ($room) {
                    $roomNoData = "<br><b>Số Phòng:</b> {$room->room_num}";
                }
            }

            $price = number_format($data->price, 0, '.', ',');
            $priceTotal = number_format($data->trans_amt, 0, '.', ',');

            $date = \Carbon\Carbon::parse($data->datentime)->format('d-m-Y');
            $time = \Carbon\Carbon::parse($data->datentime)->format('H : i');
            $checkin = \Carbon\Carbon::parse($data->check_in)->format('d-m-Y');
            $checkout = \Carbon\Carbon::parse($data->check_out)->format('d-m-Y');

            $statusBg = match ($data->booking_status) {
                'Đã Thanh Toán' => 'bg-success',
                'Đã Huỷ' => 'bg-danger',
                default => 'bg-primary'
            };

            $tableData .= "
        <tr>
            <td>{$i}</td>
            <td>
                <span class='badge bg-primary'>
                    ID Đơn: {$data->order_id}
                </span>
                <br>
                <b>Tên:</b> {$data->user_name}
                <br>
                <b>Điện Thoại:</b> {$data->phonenum}
            </td>
            <td>
                <b>Loại Phòng:</b> {$data->room_name}
                {$roomNoData}
                <br>
                <b>Giá:</b> {$price} ₫
            </td>
            <td>
                <b>Thời Gian Đặt:</b> {$time} | {$date}
                <br>
                <b>Thời Gian Vào:</b> {$checkin}
                <br>
                <b>Thời Gian Trả:</b> {$checkout}
                <br>
                <b>Thanh Toán:</b> {$priceTotal} ₫
            </td>
            <td>
                <span class='badge {$statusBg}'>{$data->booking_status}</span>
            </td>
        ";

            if ($data->booking_status === 'Đã Thanh Toán') {
                $tableData .= "
            <td>
                <button type='button' onclick='download({$data->booking_id})' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                    <i class='bi bi-file-earmark-arrow-down-fill'></i>
                </button>
            </td>
            ";
            }

            $tableData .= '</tr>';
            $i++;
        }

        return response($tableData);
    }

    public function check_in()
    {
        return view('admin.bookings.check_in');
    }

    public function get_bookings(Request $request)
    {

        $search = '%' . $request->input('search') . '%';

        $results = DB::table('booking_order as bo')
            ->join('booking_details as bd', 'bo.booking_id', '=', 'bd.booking_id')
            ->where(function ($query) use ($search) {
                $query->where('bo.order_id', 'like', $search)
                    ->orWhere('bd.phonenum', 'like', $search)
                    ->orWhere('bd.user_name', 'like', $search);
            })
            ->where('bo.booking_status', 'Đã Đặt')
            ->where('bo.arrival', 0)
            ->orderBy('bo.booking_id', 'asc')
            ->select('bo.*', 'bd.*')
            ->get();

        if ($results->isEmpty()) {
            return '<b>Không tìm thấy dữ liệu nào!</b>';
        }

        $table_data = '';
        $i = 1;

        foreach ($results as $data) {
            $date = Carbon::parse($data->datentime)->format('d-m-Y');
            $time = Carbon::parse($data->datentime)->format('H : i');
            $checkin = Carbon::parse($data->check_in)->format('d-m-Y');
            $checkout = Carbon::parse($data->check_out)->format('d-m-Y');
            $count_days = Carbon::parse($data->check_in)->diffInDays(Carbon::parse($data->check_out));

            $rooms = DB::table('room_number')
                ->where('room_id', $data->room_id)
                ->whereNotNull('room_num')
                ->where('status', 0)
                ->get();

            $room_option = '';
            foreach ($rooms as $room) {
                $room_option .= "<option value='{$room->id}'>{$room->room_num}</option>";
            }

            $price = number_format($data->price, 0, '.', ',');
            $price_total = number_format($data->total_pay, 0, '.', ',');

            $table_data .= view('admin.bookings.get_bookings', compact(
                'i',
                'data',
                'checkin',
                'checkout',
                'count_days',
                'date',
                'time',
                'price',
                'price_total',
                'room_option'
            ))->render();

            $i++;
        }

        return $table_data;
    }

    public function kh_booking(Request $request)
    {
        $room_no = $request->input('room_no');
        $booking_id = $request->input('booking_id');

        $res = DB::table('booking_order as bo')
            ->join('booking_details as bd', 'bo.booking_id', '=', 'bd.booking_id')
            ->where('bo.booking_id', $booking_id)
            ->update([
                'bo.booking_status' => 'Đã Xác Nhận Đặt Phòng',
                'bd.room_no' => $room_no
            ]);

        $res1 = DB::table('room_number')
            ->where('id', $room_no)
            ->update(['status' => 1]);

        return response()->json($res);
    }

    public function huy_booking(Request $request)
    {
        $booking_id = $request->input('booking_id');

        $res = DB::table('booking_order as bo')
            ->join('rooms as r', 'bo.room_id', '=', 'r.id')
            ->where('bo.booking_id', $booking_id)
            ->update([
                'bo.booking_status' => 'Đã Huỷ',
                'bo.refund' => 0
            ]);

        return response()->json($res);
    }

    public function check_out()
    {
        return view('admin.bookings.check_out');
    }

    public function get_bookings_checkout(Request $request)
    {
        $search = $request->input('search');

        $bookings = DB::table('booking_order as bo')
            ->join('booking_details as bd', 'bo.booking_id', '=', 'bd.booking_id')
            ->where(function ($query) use ($search) {
                $query->where('bo.order_id', 'like', "%{$search}%")
                    ->orWhere('bd.phonenum', 'like', "%{$search}%")
                    ->orWhere('bd.user_name', 'like', "%{$search}%");
            })
            ->where('bo.booking_status', 'Đã Xác Nhận Đặt Phòng')
            ->where('bo.arrival', 0)
            ->orderBy('bo.booking_id', 'asc')
            ->select('bo.*', 'bd.*')
            ->get();

        if ($bookings->isEmpty()) {
            return response("<b>Không tìm thấy dữ liệu nào!</b>");
        }

        $data = [];

        foreach ($bookings as $booking) {
            $room_info = DB::table('room_number')->where('id', $booking->room_no)->first();
            $room_id = $room_info->room_id ?? null;
            $room_num = $room_info->room_num ?? null;

            $available_rooms = DB::table('room_number')
                ->where('room_id', $room_id)
                ->whereNotNull('room_num')
                ->where('status', 0)
                ->get();

            $checkin = \Carbon\Carbon::parse($booking->check_in);
            $checkout = \Carbon\Carbon::parse($booking->check_out);
            $today = \Carbon\Carbon::today();

            $count_days = $checkin->diffInDays($checkout);
            $time_out = $today->diffInDays($checkout);

            $han_phong = $today >= $checkout
                ? "<span class='badge bg-warning'>Đã Hết Hạn</span>"
                : ($time_out - 1) . ' đêm';

            $data[] = [
                'booking' => $booking,
                'room_num' => $room_num,
                'available_rooms' => $available_rooms,
                'price' => number_format($booking->price, 0, '.', ','),
                'total_pay' => number_format($booking->total_pay, 0, '.', ','),
                'checkin' => $checkin->format('d-m-Y'),
                'checkout' => $checkout->format('d-m-Y'),
                'date' => \Carbon\Carbon::parse($booking->datentime)->format('d-m-Y'),
                'count_days' => $count_days,
                'han_phong' => $han_phong
            ];
        }

        return view('admin.bookings.get_bookings_checkout', compact('data'))->render();
    }

    public function change_room(Request $request)
    {
        $data = $request->all();

        $newRoom = DB::table('room_number')->where('id', $data['room_no_id_new'])->first();
        if (!$newRoom) {
            return response('Phòng mới không tồn tại', 404);
        }

        $oldRoom = DB::table('room_number')->where('id', $data['room_id_num_old'])->first();
        if (!$oldRoom) {
            return response('Phòng cũ không tồn tại', 404);
        }

        DB::beginTransaction();
        try {
            DB::table('booking_details')
                ->where('booking_id', $data['booking_id'])
                ->update(['room_no' => $data['room_no_id_new']]);

            DB::table('room_number')
                ->where('id', $data['room_id_num_old'])
                ->update(['status' => 0]);

            DB::table('room_number')
                ->where('id', $data['room_no_id_new'])
                ->update(['status' => 1]);

            DB::commit();
            return response(1);
        } catch (\Exception $e) {
            DB::rollBack();
            return response('Lỗi hệ thống: ' . $e->getMessage(), 500);
        }
    }

    public function payment_booking(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            DB::table('booking_order as bo')
                ->join('booking_details as bd', 'bo.booking_id', '=', 'bd.booking_id')
                ->join('rooms as r', 'bo.room_id', '=', 'r.id')
                ->where('bo.booking_id', $data['booking_id'])
                ->update([
                    'bo.arrival' => 1,
                    'bo.booking_status' => $data['booking_status'],
                    'bo.trans_amt' => $data['trans_amt'],
                    'bo.trans_status' => $data['trans_status']
                ]);

            DB::table('room_number')
                ->where('id', $data['room_num'])
                ->update(['status' => 0]);

            DB::commit();
            return response(1);
        } catch (\Exception $e) {
            DB::rollBack();
            return response('Lỗi khi cập nhật booking: ' . $e->getMessage(), 500);
        }
    }

    public function cancel_booking(Request $request)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            DB::table('booking_order as bo')
                ->join('rooms as r', 'bo.room_id', '=', 'r.id')
                ->where('bo.booking_id', $data['booking_id'])
                ->update([
                    'bo.booking_status' => 'Đã Huỷ',
                    'bo.refund' => 0
                ]);

            DB::table('room_number')
                ->where('id', $data['room_num_id'])
                ->update(['status' => 0]);

            DB::commit();
            return response(1);
        } catch (\Exception $e) {
            DB::rollBack();
            return response('Lỗi khi huỷ đặt phòng: ' . $e->getMessage(), 500);
        }
    }

    public function generatePdf(Request $request)
    {
        $id = $request->query('id');

        $data = DB::table('booking_order as bo')
            ->join('booking_details as bd', 'bo.booking_id', '=', 'bd.booking_id')
            ->join('user_cred as uc', 'bo.user_id', '=', 'uc.id')
            ->select('bo.*', 'bd.*', 'uc.email')
            ->whereIn('bo.booking_status', ['Đã Thanh Toán', 'Đã Huỷ', 'Đã Xác Nhận Đặt Phòng'])
            ->where('bo.booking_id', $id)
            ->first();

        if (!$data) {
            return redirect()->route('admin.dashboard');
        }

        $room = DB::table('room_number')->where('id', $data->room_no)->first();
        $room_no = $room ? $room->room_num : '---';

        $date = \Carbon\Carbon::parse($data->datentime)->format('H:i | d-m-Y');
        $checkin = \Carbon\Carbon::parse($data->check_in)->format('d-m-Y');
        $checkout = \Carbon\Carbon::parse($data->check_out)->format('d-m-Y');
        $checkin_date = new \DateTime($data->check_in);
        $checkout_date = new \DateTime($data->check_out);
        $count_nights = $checkin_date->diff($checkout_date)->days;
        $count_days = $count_nights + 1;

        $price = number_format($data->price, 0, '.', ',');
        $price_total = number_format($data->trans_amt, 0, '.', ',');

        $html = view('admin.bookings.invoice_pdf', compact(
            'data',
            'room_no',
            'date',
            'checkin',
            'checkout',
            'count_nights',
            'count_days',
            'price',
            'price_total'
        ))->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        ob_end_clean();

        return Response::make($mpdf->Output($data->order_id . '.pdf', 'D'));
    }
}
