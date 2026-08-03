<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function show(Request $request, User $author)
    {
        $posts = $author->posts()
            ->published()
            ->with(['author', 'category', 'tags'])
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return response()->json([
            'author' => [
                'id' => $author->id,
                'name' => $author->name,
                'bio' => $author->bio,
                'avatar' => $author->avatar ? asset('storage/'.$author->avatar) : null,
            ],
            'posts' => PostResource::collection($posts),
        ]);
    }
}
