<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PlaceController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'icon' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $place = new Place();

        $photo = $request->file('icon');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photoPath = $photo->storeAs('photos', $photoName, 'public');


        $place->title = $request->title;
        $place->icon = $photoPath;
        $place->address = $request->address;
        $place->description = $request->description;
        $place->address = $request->address;
        $place->weekday_price = $request->weekday_price;
        $place->weekend_price = $request->weekend_price;
        $place->tag = $request->tag;
        $place->category_id = $request->category_id;
        $place->vendor_id = $request->vendor_id;

        if ($request->amenities) {
            $place->amenities()->attach($request->amenities);
        }
        $place->save();

        return response()->json(['message' => 'place added successfully'], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $place = Place::findOrFail($id);

        if (!$place) {
            return response()->json(['message' => 'place not found'], Response::HTTP_NOT_FOUND);
        }

        $photo = $request->file('image');
        if ($photo) {
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('photos', $photoName, 'public');
            $place->image = $photoPath;
        }

        $place->title = $request->title || $place->title;
        $place->address = $request->address || $place->address;
        $place->description = $request->description || $place->description;
        $place->address = $request->address || $place->address;
        $place->weekday_price = $request->weekday_price || $place->weekday_price;
        $place->weekend_price = $request->weekend_price || $place->weekend_price;
        $place->tag = $request->tag || $place->tag;
        $place->category_id = $request->category_id || $place->category_id;
        $place->vendor_id = $request->vendor_id || $place->vendor_id;
        $place->featured = $request->featured || $place->featured;
        $place->bookable = $request->bookable || $place->bookable;
        $place->available = $request->available || $place->available;
        if ($request->amenities) {
            $place->amenities()->detach();
            $place->amenities()->attach($request->amenities);
        }
        $place->save();

        // $place->update($request->all());
        return response()->json(['message' => 'place updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $place = Place::findOrFail($id);

        if (!$place) {
            return response()->json(['message' => 'place not found'], Response::HTTP_NOT_FOUND);
        }

        $place->delete();
        return response()->json(['message' => 'place deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $place = Place::with('amenities', 'specialDays', 'placeImages')->findOrFail($id);

        if (!$place) {
            return response()->json(['message' => 'place not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($place, Response::HTTP_OK);
    }

    public function index()
    {
        $places = Place::with('amenities', 'specialDays', 'placeImages')
        ->where('available', true)
        ->orderBy('price', 'asc')
        ->select('places.*', DB::raw('AVG(ratings.rate) as rating'))
        ->leftJoin('ratings', 'places.id', '=', 'ratings.place_id')
        ->groupBy('places.id')
        ->get();
        return response()->json($places, Response::HTTP_OK);
    }

    public function getAllFeatured()
    {
        $places = Place::with('amenities', 'specialDays', 'placeImages')
        ->where('available', true)
        ->where('featured', true)
        ->orderBy('weekday_price', 'asc')
        ->select('places.*', DB::raw('AVG(ratings.rate) as rating'))
        ->leftJoin('ratings', 'places.id', '=', 'ratings.place_id')
        ->groupBy('places.id')
        ->get();
        return response()->json($places, Response::HTTP_OK);
    }
}
