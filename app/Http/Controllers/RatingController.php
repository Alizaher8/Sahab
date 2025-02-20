<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRatingRequest;
use App\Http\Requests\UpdateRatingRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use app\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(CreateRatingRequest $request)
    {

        $userId = Auth::id();

        $booking = Booking::where('status', '=', 'completed')
                            ->where('user_id', $userId)
                            ->where('place_id', $request->place_id)
                            ->where('service_id', $request->service_id)
                            ->first();

        if (!$booking) {
            return response()->json(['message' => 'you should have completed booking to rate'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $rating = Rating::where([
            'user_id' => $userId,
            'place_id' => $request->place_id || null,
            'service_id' => $request->service_id || null
            ])->firstOrFail();

        if (!$rating) {
            return response()->json(['message' => 'you rate before'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $rating = new Rating();

        $rating->rate = $request->rate;
        $rating->user_id = $request->user_id;
        $rating->place_id = $request->place_id;
        $rating->service_id = $request->service_id;

        $rating->save();

        return response()->json(['message' => 'special Day added successfully'], Response::HTTP_OK);
    }

    public function update(UpdateRatingRequest $request, $id)
    {
        $rating = Rating::findOrFail($id);

        if (!$rating) {
            return response()->json(['message' => 'rating not found'], Response::HTTP_NOT_FOUND);
        }

        $rating->rate = $request->rate || $request->rate;
        $rating->user_id = $request->user_id || $rating->user_id;
        $rating->place_id = $request->place_id || $rating->place_id;
        $rating->service_id = $request->service_id || $rating->service_id;

        $rating->save();

        return response()->json(['message' => 'special day updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);

        if (!$rating) {
            return response()->json(['message' => 'special day not found'], Response::HTTP_NOT_FOUND);
        }

        $rating->delete();
        return response()->json(['message' => 'special day deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $rating = Rating::findOrFail($id);

        if (!$rating) {
            return response()->json(['message' => 'special day not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($rating, Response::HTTP_OK);
    }

    public function index()
    {
        $ratings = Rating::all();
        return response()->json($ratings, Response::HTTP_OK);
    }
}
