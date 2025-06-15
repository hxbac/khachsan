<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomSettingController extends Controller
{
    public function index(Request $request)
    {
        $features = DB::table('features')->get();
        $facilities = DB::table('facilities')->get();
        return view('admin.roomSettings.index', compact('features', 'facilities'));
    }
    public function add_room(Request $request)
    {
        $features = json_decode($request->input('features'));
        $facilities = json_decode($request->input('facilities'));
        $quantity = (int) $request->input('quantity');

        $flag = 0;

        DB::beginTransaction();

        try {
            // Insert room
            $room_id = DB::table('rooms')->insertGetId([
                'name' => $request->input('name'),
                'area' => $request->input('area'),
                'price' => $request->input('price'),
                'quantity' => $quantity,
                'adult' => $request->input('adult'),
                'children' => $request->input('children'),
                'description' => $request->input('desc'),
            ]);

            // Insert room_facilities
            foreach ($facilities as $f) {
                DB::table('room_facilities')->insert([
                    'room_id' => $room_id,
                    'facilities_id' => $f,
                ]);
            }

            // Insert room_features
            foreach ($features as $f) {
                DB::table('room_features')->insert([
                    'room_id' => $room_id,
                    'features_id' => $f,
                ]);
            }

            // Insert room_number
            for ($i = 1; $i <= $quantity; $i++) {
                DB::table('room_number')->insert([
                    'room_id' => $room_id,
                ]);
            }

            DB::commit();
            $flag = 1;
        } catch (\Exception $e) {
            DB::rollBack();
            $flag = 0;
        }

        return response()->json($flag);
    }
    public function get_all_rooms(Request $request)
    {
        $rooms = DB::table('rooms')
            ->where('removed', 0)
            ->orderBy('id')
            ->get();

        return view('admin.roomSettings.list', compact('rooms'));
    }
    public function edit_details(Request $request) {
        $roomId = $request->input('get_room');

        $room = DB::table('rooms')->where('id', $roomId)->first();

        $features = DB::table('room_features')
            ->where('room_id', $roomId)
            ->pluck('features_id')
            ->toArray();

        $facilities = DB::table('room_facilities')
            ->where('room_id', $roomId)
            ->pluck('facilities_id')
            ->toArray();

        return response()->json([
            'roomdata' => $room,
            'features' => $features,
            'facilities' => $facilities,
        ]);
    }
    public function edit_room(Request $request)
    {
        $frmData = $request->all();
        $features = json_decode($frmData['features']);
        $facilities = json_decode($frmData['facilities']);
        $roomId = $frmData['room_id'];
        $flag = false;

        DB::beginTransaction();
        try {
            DB::table('rooms')->where('id', $roomId)->update([
                'name'        => $frmData['name'],
                'area'        => $frmData['area'],
                'price'       => $frmData['price'],
                'quantity'    => $frmData['quantity'],
                'adult'       => $frmData['adult'],
                'children'    => $frmData['children'],
                'description' => $frmData['desc'],
            ]);

            DB::table('room_features')->where('room_id', $roomId)->delete();
            DB::table('room_facilities')->where('room_id', $roomId)->delete();

            $facilitiesData = array_map(fn($f) => [
                'room_id' => $roomId,
                'facilities_id' => $f
            ], $facilities);
            DB::table('room_facilities')->insert($facilitiesData);

            $featuresData = array_map(fn($f) => [
                'room_id' => $roomId,
                'features_id' => $f
            ], $features);
            DB::table('room_features')->insert($featuresData);

            DB::commit();
            $flag = true;
        } catch (\Exception $e) {
            DB::rollBack();
            $flag = false;
        }

        return response()->json($flag ? 1 : 0);
    }
    public function toggle_status(Request $request) {
        $roomId = $request->input('toggle_status');
        $value = $request->input('value');

        $updated = DB::table('rooms')
            ->where('id', $roomId)
            ->update(['status' => $value]);

        return response()->json($updated ? 1 : 0);
    }
    public function add_image(Request $request) {
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                return response('inv_img');
            }

            $path = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $stored = $file->move(public_path('images/rooms'), $filename);
                $path = $stored ? $filename : null;
            }

            if (!$path) {
                return response('upd_failed');
            }

            $inserted = DB::table('room_images')->insert([
                'room_id' => $request->input('room_id'),
                'image' => $filename
            ]);

            return response()->json($inserted ? 1 : 0);
        }

        return response('inv_img');
    }
    public function get_room_images(Request $request) {
        $roomId = $request->input('get_room_images');

        $images = DB::table('room_images')
                    ->where('room_id', $roomId)
                    ->get();

        return view('admin.roomSettings.room_images_table', compact('images'));
    }
    public function rem_image(Request $request) {
        $image = DB::table('room_images')
                ->where('sr_no', $request->image_id)
                ->where('room_id', $request->room_id)
                ->first();

        if (!$image) {
            return response()->json(0);
        }

        $deleted = DB::table('room_images')
                    ->where('sr_no', $request->image_id)
                    ->where('room_id', $request->room_id)
                    ->delete();

        return response()->json($deleted);
    }
    public function remove_room(Request $request) {
        $roomId = $request->room_id;

        $res2 = DB::table('room_images')->where('room_id', $roomId)->delete();
        $res3 = DB::table('room_features')->where('room_id', $roomId)->delete();
        $res4 = DB::table('room_facilities')->where('room_id', $roomId)->delete();
        $res5 = DB::table('rooms')->where('id', $roomId)->update(['removed' => 1]);

        if ($res2 || $res3 || $res4 || $res5) {
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    public function thumb_image(Request $request) {
        $room_id = $request->input('room_id');
        $image_id = $request->input('image_id');

        DB::table('room_images')
            ->where('room_id', $room_id)
            ->update(['thumb' => 0]);

        $res = DB::table('room_images')
            ->where('sr_no', $image_id)
            ->where('room_id', $room_id)
            ->update(['thumb' => 1]);

        return response()->json($res);
    }
}
