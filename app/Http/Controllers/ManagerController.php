<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        print $credentials;

        if (Auth::attempt($credentials)) {
            $manager = Auth::user();
            // $token = $manager->createToken('admin-token')->plainTextToken;

            return response()->json(['manager' => $manager], 200);
            // return response()->json(['token' => $token, 'manager' => $manager], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
