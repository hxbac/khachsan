<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserCred;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function list()
    {
        $users = UserCred::get();
        return view('admin.users.list', compact('users'));
    }

    public function toggleStatus(Request $request)
    {
        $data = $request->all();

        $res = UserCred::where('id', $data['id'])
            ->update(['status' => $data['value']]);

        return $res === 1 ? 1 : 0;
    }

    public function remove(Request $request)
    {
        $data = $request->all();

        $res = UserCred::where('id', $data['user_id'])
            ->where('is_verified', 0)
            ->delete();

        return $res === 1 ? 1 : 0;
    }

    public function search(Request $request)
    {
        $data = $request->all();

        $users = UserCred::where('name', 'like', '%' . $data['name'] . '%')
            ->get();

        return view('admin.users.list', compact('users'));
    }
}
