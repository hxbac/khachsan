<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;

class CarouselController extends Controller
{
    public function index()
    {
        return view('admin.carousels.index');
    }
    public function add_image(Request $request)
    {
        $path = null;
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $stored = $file->move(public_path('images/carousel'), $filename);
            $path = $stored ? $filename : null;
        }

        if (!$path) {
            return 'upd_failed';
        }

        Carousel::create([
            'image' => $path
        ]);

        return 1;
    }
    public function get_carousel()
    {
        $carousels = Carousel::all();
        return view('admin.carousels.list', compact('carousels'))->render();
    }
    public function rem_image(Request $request)
    {
        $carousel = Carousel::where('sr_no', $request->rem_image)->first();

        if (!$carousel) {
            return 0;
        }

        Carousel::where('sr_no', $request->rem_image)->delete();

        return 1;
    }
}
