<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public bool $includeContent = false;

    public function withContent(): static
    {
        $this->includeContent = true;

        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->short_excerpt,
            'content' => $this->when($this->includeContent, $this->content),
            'featured_image' => $this->featured_image
                ? asset('storage/'.$this->featured_image)
                : null,
            'status' => $this->status,
            'reading_time' => $this->reading_time,
            'views' => $this->views,
            'likes_count' => $this->likes_count,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'author' => $this->when($this->relationLoaded('author'), fn () => [
                'id' => $this->author?->id,
                'name' => $this->author?->name,
                'bio' => $this->author?->bio,
                'avatar' => $this->author?->avatar
                    ? asset('storage/'.$this->author->avatar)
                    : null,
            ]),
            'category' => $this->when($this->relationLoaded('category'), fn () => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ]),
            'tags' => $this->when($this->relationLoaded('tags'), fn () => $this->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])),
            'liked' => $this->when(isset($this->liked), $this->liked),
        ];
    }
}
