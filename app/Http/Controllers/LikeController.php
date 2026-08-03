<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LikeController extends Controller
{
    public function toggle(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->published()->firstOrFail();

        $user = $request->user();
        $fingerprint = $user ? null : ($request->header('X-Fingerprint') ?: Str::uuid()->toString());

        $existing = PostLike::query()
            ->where('post_id', $post->id)
            ->where(fn ($q) => $user ? $q->where('user_id', $user->id) : $q->where('fingerprint', $fingerprint))
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $user?->id,
                'fingerprint' => $fingerprint,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }
}
