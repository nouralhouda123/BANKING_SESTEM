<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrandChildSimpleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_type' => $this->account_type, // من العمود account_type
            'balance' => $this->balance,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'user_id' => $this->user_id
        ];
    }
}
