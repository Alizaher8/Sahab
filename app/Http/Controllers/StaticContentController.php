<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStaticContentRequest;
use App\Http\Requests\UpdateStaticContentRequest;
use App\Models\StaticContent;
// use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StaticContentController extends Controller
{
    public function store(CreateStaticContentRequest $request)
    {

        $user = new StaticContent();
        $user->title = $request->title;
        $user->content = $request->content;
        $user->save();

        return response()->json(['message' => 'content added successfully'], Response::HTTP_OK);
    }

    public function update(UpdateStaticContentRequest $request, $id)
    {
        $user = StaticContent::findOrFail($id);

        if (!$user) {
            return response()->json(['message' => 'content not found'], Response::HTTP_NOT_FOUND);
        }

        $user = new StaticContent();
        $user->title = $request->title;
        $user->content = $request->content;
        $user->save();

        return response()->json(['message' => 'content updated successfully'], Response::HTTP_OK);
    }

    public function index()
    {
        $contents = StaticContent::all();
        return response()->json($contents, Response::HTTP_OK);
    }
}
