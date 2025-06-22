<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = DB::table('rating_review as rr')
            ->join('user_cred as uc', 'rr.user_id', '=', 'uc.id')
            ->join('rooms as r', 'rr.room_id', '=', 'r.id')
            ->select('rr.*', 'uc.name as uname', 'r.name as rname')
            ->orderByDesc('rr.sr_no')
            ->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function seen(Request $request)
    {
        $seenId = $request->query('seen');

        if ($seenId === 'all') {
            $updated = DB::table('rating_review')->update(['seen' => 1]);

            if ($updated) {
                return back()->with('success', 'Đánh dấu tất cả là đã đọc!');
            } else {
                return back()->with('error', 'Lỗi hệ thống!');
            }
        }

        if (is_numeric($seenId)) {
            $updated = DB::table('rating_review')
                ->where('sr_no', $seenId)
                ->update(['seen' => 1]);

            if ($updated) {
                return back()->with('success', 'Đánh dấu là đã đọc!');
            } else {
                return back()->with('error', 'Lỗi hệ thống!');
            }
        }

        return back()->with('error', 'Tham số không hợp lệ!');
    }

    public function delete(Request $request)
    {
        $delId = $request->query('delete');

        if ($delId === 'all') {
            $deleted = DB::table('rating_review')->delete();

            if ($deleted) {
                return back()->with('success', 'Tất cả dữ liệu đã bị xóa!');
            } else {
                return back()->with('error', 'Lỗi hệ thống!');
            }
        }

        if (is_numeric($delId)) {
            $deleted = DB::table('rating_review')->where('sr_no', $delId)->delete();

            if ($deleted) {
                return back()->with('success', 'Đã xóa dữ liệu!');
            } else {
                return back()->with('error', 'Lỗi hệ thống!');
            }
        }

        return back()->with('error', 'Tham số không hợp lệ!');
    }
}
