<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\ContactDetail;
use App\Models\Facilities;
use App\Models\RatingReview;
use App\Models\Room;
use App\Models\TeamDetails;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $carousels = Carousel::all();

        $guests_res = Room::where('status', 1)
            ->where('removed', 0)
            ->selectRaw('MAX(adult) as max_adult, MAX(children) as max_children')
            ->first();

        $rooms = Room::select(
            'rooms.*',
            DB::raw('MAX(CASE WHEN room_images.thumb = 1 THEN room_images.image ELSE NULL END) as thumb_image'),
            DB::raw('AVG(rating_review.rating) as avg_rating'),
            DB::raw('GROUP_CONCAT(DISTINCT features.name SEPARATOR ",") as feature_names'),
            DB::raw('GROUP_CONCAT(DISTINCT facilities.name SEPARATOR ",") as facility_names')
        )
            ->leftJoin('room_images', 'rooms.id', '=', 'room_images.room_id')
            ->leftJoin('rating_review', 'rooms.id', '=', 'rating_review.room_id')
            ->leftJoin('room_features', 'rooms.id', '=', 'room_features.room_id')
            ->leftJoin('features', 'room_features.features_id', '=', 'features.id')
            ->leftJoin('room_facilities', 'rooms.id', '=', 'room_facilities.room_id')
            ->leftJoin('facilities', 'room_facilities.facilities_id', '=', 'facilities.id')
            ->where('rooms.status', 1)
            ->where('rooms.removed', 0)
            ->groupBy('rooms.id')
            ->orderByDesc('rooms.id')
            ->limit(3)
            ->get();

        $facilities = Facilities::orderByDesc('id')->limit(5)->get();

        $reviews = RatingReview::select('rating_review.*', 'user_cred.name as uname', 'user_cred.profile', 'rooms.name as rname')
            ->join('user_cred', 'rating_review.user_id', '=', 'user_cred.id')
            ->join('rooms', 'rating_review.room_id', '=', 'rooms.id')
            ->orderByDesc('sr_no')
            ->limit(6)
            ->get();

        return view('home.index', compact('carousels', 'guests_res', 'rooms', 'facilities', 'reviews'));
    }

    public function facilities()
    {
        $facilities = Facilities::all();
        return view('home.facilities', compact('facilities'));
    }

    public function contact()
    {
        $contact = ContactDetail::where('sr_no', 1)->first();
        return view('home.contact', compact('contact'));
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $res = UserQuery::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        if ($res) {
            // Ví dụ bạn có thể dùng session flash để alert
            session()->flash('success', 'Mail sent!');
        } else {
            session()->flash('error', 'Server Down! Try again later.');
        }

        return back();
    }

    public function about() {
        $teamDetails = TeamDetails::all();
        return view('home.about', compact('teamDetails'));
    }
}
