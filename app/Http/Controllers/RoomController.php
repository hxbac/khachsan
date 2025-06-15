<?php

namespace App\Http\Controllers;

use App\Models\Facilities;
use App\Models\Features;
use App\Models\RatingReview;
use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request) {
        $checkin_default = '';
        $checkout_default = '';
        $adult_default = '';
        $children_default = '';

        if ($request->has('check_availability')) {
            $checkin_default = $request->input('checkin', '');
            $checkout_default = $request->input('checkout', '');
            $adult_default = $request->input('adult', '');
            $children_default = $request->input('children', '');
        }

        $facilities = Facilities::all();

        return view('home.rooms.index', compact(
            'checkin_default',
            'checkout_default',
            'adult_default',
            'children_default',
            'facilities',
        ));
    }

    public function show(Request $request) {
        $id = $request->query('id');

        if (!$id) {
            abort(404);
        }

        $room = Room::where('id', $id)
            ->where('status', 1)
            ->where('removed', 0)
            ->first();

        if (!$room) {
            abort(404);
        }

        $images = RoomImage::where('room_id', $room->id)->get();


        $avgRating = RatingReview::where('room_id', $id)
            ->orderByDesc('sr_no')
            ->limit(20)
            ->avg('rating');

        $features = Features::select('name')
            ->join('room_features', 'features.id', '=', 'room_features.features_id')
            ->where('room_features.room_id', $id)
            ->get();

        $facilities = Facilities::select('name')
            ->join('room_facilities', 'facilities.id', '=', 'room_facilities.facilities_id')
            ->where('room_facilities.room_id', $id)
            ->get();

        $reviews = RatingReview::select(
                'rating_review.*',
                'user_cred.name as uname',
                'user_cred.profile',
                'rooms.name as rname'
            )
            ->join('user_cred', 'rating_review.user_id', '=', 'user_cred.id')
            ->join('rooms', 'rating_review.room_id', '=', 'rooms.id')
            ->where('rating_review.room_id', $id)
            ->orderByDesc('sr_no')
            ->limit(15)
            ->get();

        return view('home.rooms.show', compact(
            'room',
            'images',
            'avgRating',
            'features',
            'facilities',
            'reviews',
        ));
    }
}
