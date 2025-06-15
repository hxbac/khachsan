<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactDetail;
use App\Models\Setting;
use App\Models\TeamDetails;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        return view('admin.settings.index');
    }
    public function get_general() {
        $data = Setting::where('sr_no', 1)
            ->first();

        return response()->json($data);
    }
    public function upd_general(Request $request) {
        $data = $request->all();
        $res = Setting::where('sr_no', 1)
        ->update([
            'site_title' => $data['site_title'],
            'site_about' => $data['site_about'],
        ]);

    return $res;
    }
    public function upd_shutdown(Request $request) {
        $shutdown = $request->upd_shutdown;

        Setting::where('sr_no', 1)
            ->update(['shutdown' => $shutdown]);

        return response()->json(1);
    }
    public function get_contacts() {
        $data = ContactDetail::where('sr_no', 1)
        ->first();

        return response()->json($data);
    }
    public function upd_contacts(Request $request) {
        $data = $request->only([
            'address', 'gmap', 'pn1', 'pn2',
            'email', 'fb', 'insta', 'tw', 'iframe'
        ]);

        $res = ContactDetail::where('sr_no', 1)
            ->update($data);

        return response()->json(1);
    }
    public function add_member(Request $request) {
        if (!$request->hasFile('picture')) {
            return response()->json('inv_img');
        }

        $file = $request->file('picture');

        try {

            $filename = null;
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $stored = $file->move(public_path('images/about'), $filename);
            }

            $res = TeamDetails::insert([
                'name' => $request->input('name'),
                'picture' => $filename,
            ]);

            return response()->json(1);
        } catch (\Exception $e) {
            return response()->json('upd_failed');
        }
    }
    public function get_members() {
        $team = TeamDetails::get();
        return view('admin.settings.team', compact('team'))->render();
    }
    public function rem_member(Request $request) {
        $sr_no = $request->rem_member;

        $member = TeamDetails::where('sr_no', $sr_no)->first();

        if ($member) {
            $res = TeamDetails::where('sr_no', $sr_no)->delete();
            return response()->json($res ? 1 : 0);
        }

        return response()->json(0);
    }
}
