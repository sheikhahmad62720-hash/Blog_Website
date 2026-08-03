<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::query()
            ->withCount(['posts as post_count' => fn ($q) => $q->published()])
            ->orderBy('name')
            ->get();
    }

    public function show(Request $request, string $slug)
    {
        return Category::with(['publishedPosts' => fn ($q) => $q->with(['author', 'category', 'tags'])])
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
