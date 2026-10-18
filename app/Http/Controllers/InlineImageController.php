<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InlineImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        $path = $validated['image']->storePublicly('posts/inline', 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
        ], 201);
    }
}
