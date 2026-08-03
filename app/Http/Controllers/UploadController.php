<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,webp,gif', 'max:4096'],
        ]);

        $path = $request->file('image')->store('uploads', 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
        ], 201);
    }
}
