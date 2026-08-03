<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->published()
            ->with(['author', 'category', 'tags'])
            ->when($request->category, fn ($q, $slug) => $q->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when($request->tag, fn ($q, $slug) => $q->whereHas('tags', fn ($t) => $t->where('slug', $slug)))
            ->when($request->author, fn ($q, $id) => $q->where('user_id', $id))
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('published_at')
            ->paginate((int) $request->input('per_page', 9))
            ->withQueryString();

        return PostResource::collection($posts);
    }

    public function show(Request $request, string $slug)
    {
        $post = Post::query()
            ->with(['author', 'category', 'tags'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        if (! $request->user()?->isAdmin()) {
            $post->increment('views');
            $post->refresh();
        }

        $post->liked = $this->resolveLiked($request, $post);

        return (new PostResource($post->load(['author', 'category', 'tags'])))->withContent();
    }

    public function related(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->published()->firstOrFail();

        $related = Post::query()
            ->published()
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->where('category_id', $post->category_id)
                    ->orWhereHas('tags', fn ($t) => $t->whereIn('tags.id', $post->tags->pluck('id')));
            })
            ->with(['author', 'category', 'tags'])
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return PostResource::collection($related);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->uniqueSlug($request->input('slug') ?: $request->input('title'));
        $data['published_at'] = $data['published_at'] ?: now();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post = Post::create($data);
        $post->tags()->sync($request->input('tags', []));

        return (new PostResource($post->load(['author', 'category', 'tags'])))->response()->setStatusCode(201);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        if (isset($data['slug']) && $data['slug'] !== $post->slug) {
            $data['slug'] = $this->uniqueSlug($data['slug'], $post->id);
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post->update($data);

        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags', []));
        }

        return new PostResource($post->load(['author', 'category', 'tags']));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }

    public function indexAdmin(Request $request)
    {
        $posts = Post::query()
            ->with(['author', 'category'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->search, fn ($q, $search) => $q->where('title', 'like', "%{$search}%"))
            ->orderByDesc('created_at')
            ->paginate((int) $request->input('per_page', 15));

        return PostResource::collection($posts);
    }

    private function resolveLiked(Request $request, Post $post): bool
    {
        if ($request->user()) {
            return $post->likes()->where('user_id', $request->user()->id)->exists();
        }

        $fingerprint = $request->header('X-Fingerprint');

        return $fingerprint ? $post->likes()->where('fingerprint', $fingerprint)->exists() : false;
    }

    private function uniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value) ?: Str::random(8);
        $slug = $base;
        $i = 2;

        while (Post::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
