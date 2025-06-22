<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facilities;
use App\Models\Features;
use App\Models\RoomFacilities;
use App\Models\RoomFeatures;
use Illuminate\Http\Request;

class FeaturesFacilitiesController extends Controller
{
    public function index()
    {
        return view('admin.FeaturesFacilities.index');
    }
    public function add_feature(Request $request)
    {
        $res = Features::insert([
            'name' => $request->name,
        ]);

        return $res ? 1 : 0;
    }
    public function get_features()
    {
        $features = Features::get();
        return view('admin.FeaturesFacilities.list_feature', compact('features'))->render();
    }
    public function rem_feature(Request $request)
    {
        $featureId = $request->query('rem_feature');

        $isUsed = RoomFeatures::where('features_id', $featureId)
            ->exists();

        if ($isUsed) {
            return 'room_added';
        }

        $res = Features::where('id', $featureId)
            ->delete();

        return $res;
    }
    public function add_facility(Request $request)
    {
        $path = null;
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $stored = $file->move(public_path('images/facilities'), $filename);
            $path = $stored ? $filename : null;
        }

        if (!$path) {
            return 'upd_failed';
        }

        $res = Facilities::insert([
            'icon' => $path,
            'name' => $request->input('name'),
            'description' => $request->input('desc'),
        ]);

        return $res ? 1 : 0;
    }
    public function get_facilities()
    {
        $facilities = Facilities::get();
        return view('admin.FeaturesFacilities.list_facilities', compact('facilities'));
    }
    public function rem_facility(Request $request)
    {
        $facilityId = $request->query('rem_facility');

        $isUsed = RoomFacilities::where('facilities_id', $facilityId)
            ->exists();

        if ($isUsed) {
            return 'room_added';
        }

        $facility = Facilities::where('id', $facilityId)
            ->first();

        if (!$facility) {
            return response()->json(0);
        }

        $res = Facilities::where('id', $facilityId)
            ->delete();

        return response()->json($res);
    }
}
