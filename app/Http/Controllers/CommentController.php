<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request, string $postSlug)
    {
        $post = Post::where('slug', $postSlug)->firstOrFail();

        $comments = $post->approvedComments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderByDesc('created_at')
            ->get();

        return CommentResource::collection($comments);
    }

    public function store(StoreCommentRequest $request, string $postSlug)
    {
        $post = Post::where('slug', $postSlug)->firstOrFail();

        $comment = $post->comments()->create([
            'user_id' => $request->user()?->id,
            'parent_id' => $request->input('parent_id'),
            'author_name' => $request->user()?->name ?? $request->input('author_name'),
            'author_email' => $request->user()?->email ?? $request->input('author_email'),
            'body' => $request->input('body'),
            'approved' => true,
        ]);

        $comment->load(['user', 'replies.user']);

        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->noContent();
    }
}
