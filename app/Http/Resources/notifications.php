<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class notifications extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'type'            => $this->type,
            'title'           => $this->title,
            'data'            => $this->data,
            'read_at' => $this->read_at ? $this->read_at->format('Y-m-d') : null,
        ];
    }
}
