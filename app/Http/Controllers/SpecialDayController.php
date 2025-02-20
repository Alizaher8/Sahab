<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpecialDayRequest;
use App\Http\Requests\UpdateSpecialDayRequest;
use Illuminate\Http\Response;
use app\Models\SpecialDay;

class SpecialDayController extends Controller
{

    public function store(CreateSpecialDayRequest $request)
    {

        $specialDay = new SpecialDay();

        $specialDay->title = $request->title;
        $specialDay->price = $request->price;
        $specialDay->start_dat = $request->start_dat;
        $specialDay->end_date = $request->end_date;
        $specialDay->place_id = $request->place_id;

        $specialDay->save();

        return response()->json(['message' => 'special Day added successfully'], Response::HTTP_OK);
    }

    public function update(UpdateSpecialDayRequest $request, $id)
    {
        $specialDay = SpecialDay::findOrFail($id);

        if (!$specialDay) {
            return response()->json(['message' => 'specialDay not found'], Response::HTTP_NOT_FOUND);
        }

        $specialDay->title = $request->title;
        $specialDay->price = $request->price;
        $specialDay->start_dat = $request->start_dat;
        $specialDay->end_date = $request->end_date;
        $specialDay->place_id = $request->place_id;

        $specialDay->save();

        return response()->json(['message' => 'special day updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $specialDay = SpecialDay::findOrFail($id);

        if (!$specialDay) {
            return response()->json(['message' => 'special day not found'], Response::HTTP_NOT_FOUND);
        }

        $specialDay->delete();
        return response()->json(['message' => 'special day deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $specialDay = SpecialDay::findOrFail($id);

        if (!$specialDay) {
            return response()->json(['message' => 'special day not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($specialDay, Response::HTTP_OK);
    }

    public function index()
    {
        $specialDays = SpecialDay::all();
        return response()->json($specialDays, Response::HTTP_OK);
    }

}
