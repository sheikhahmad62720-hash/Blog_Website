<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'parent_id' => $this->parent_id,
            'author_id' => $this->user_id,
            'body' => $this->body,
            'author' => $this->when($this->user_id, fn () => $this->user?->name, $this->author_name),
            'avatar' => $this->user?->avatar
                ? asset('storage/'.$this->user->avatar)
                : null,
            'is_author' => $this->user_id ? $this->user?->isAdmin() : false,
            'created_at' => $this->created_at->diffForHumans(),
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
