<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Response;
use app\Models\User;

class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {

        $photo = $request->file('image');
        $photoName = time() . '_' . $photo->getClientOriginalName();
        $photoPath = $photo->storeAs('photos', $photoName, 'public');

        $user = new User();
        $user->name = $request->name;
        $user->image = $photoPath;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->supplier_code = $request->supplier_code;
        $user->save();

        return response()->json(['message' => 'user added successfully'], Response::HTTP_OK);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        $photo = $request->file('image');
        if ($photo) {
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photoPath = $photo->storeAs('photos', $photoName, 'public');
            $user->image = $photoPath || $user->image;
        }

        $user->name = $request->name || $user->name;
        $user->email = $request->email || $user->email;
        $user->phone = $request->phone || $user->phone;
        $user->supplier_code = $request->supplier_code || $user->supplier_code;
        $user->role = $request->role || $user->role;
        $user->save();
        return response()->json(['message' => 'user updated successfully'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        $user->delete();
        return response()->json(['message' => 'user deleted successfully'], Response::HTTP_OK);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($user, Response::HTTP_OK);
    }

    public function index()
    {
        $categories = User::all();
        return response()->json($categories, Response::HTTP_OK);
    }

    public function getPlacesForOneUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        $places = $user->places;

        return response()->json($places, Response::HTTP_OK);
    }

    public function getServicesForOneUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        $services = $user->services;

        return response()->json($services, Response::HTTP_OK);
    }
}
