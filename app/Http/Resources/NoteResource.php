<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
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
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'content' => $this->resource->content,
            'color' => $this->resource->color,
            'created_at' => $this->resource->created_at->toDateTimeString(),
            'updated_at' => $this->resource->updated_at->toDateTimeString()
        ];
    }
}
