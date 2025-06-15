<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserQuery;
use Illuminate\Http\Request;

class UserQueryController extends Controller
{
    public function index()
    {
        $queries = UserQuery::orderByDesc('sr_no')
            ->get();

        return view('admin.usersQuery.index', compact('queries'));
    }

    public function seen(Request $request)
    {
        $seen = $request->query('seen');

        if ($seen === 'all') {
            $res = UserQuery::update(['seen' => 1]);

            if ($res) {
                return redirect()->back()->with('success', 'Đánh dấu tất cả là đã đọc!');
            } else {
                return redirect()->back()->with('error', 'Lỗi hệ thống!');
            }
        } else {
            $res = UserQuery::where('sr_no', $seen)
                ->update(['seen' => 1]);

            if ($res) {
                return redirect()->back()->with('success', 'Đánh dấu là đã đọc!');
            } else {
                return redirect()->back()->with('error', 'Lỗi hệ thống!');
            }
        }
    }

    public function delete(Request $request)
    {
        $del = $request->query('del');

        if ($del === 'all') {
            $res = UserQuery::delete();

            if ($res) {
                return redirect()->back()->with('success', 'Tất cả dữ liệu đã bị xóa!');
            } else {
                return redirect()->back()->with('error', 'Operation failed!');
            }
        } else {
            $res = UserQuery::where('sr_no', $del)
                ->delete();

            if ($res) {
                return redirect()->back()->with('success', 'Đã xóa dữ liệu!');
            } else {
                return redirect()->back()->with('error', 'Đã xóa dữ liệu!');
            }
        }
    }
}
