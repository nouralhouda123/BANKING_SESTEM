<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_number' => $this->account_number ?? $this->id,
            'account_type' => $this->type,
            'balance' => $this->balance,
            'status' => $this->status,
            'status_change_reason' => $this->status_change_reason,
            'status_changed_at' => $this->status_changed_at?->format('Y-m-d H:i:s'),
            'status_changed_by' => $this->statusChangedBy?->name ?? null,
            'user' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
