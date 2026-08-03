<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return Tag::query()
            ->withCount(['posts as post_count' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get();
    }

    public function show(Request $request, string $slug)
    {
        return Tag::with(['publishedPosts' => fn ($q) => $q->with(['author', 'category', 'tags'])])
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
