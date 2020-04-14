<?php

namespace App\Http\Resources\post;

use App\Http\Resources\user\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "body" => $this->body,
            "stored_at" => $this->created_at->diffForHumans(),
            "user" => new UserResource($this->user)
        ];
    }
}
