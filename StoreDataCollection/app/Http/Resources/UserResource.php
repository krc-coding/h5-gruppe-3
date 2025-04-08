<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // instead of returning everything we only want these two thing returned when returning a user.
        return [
            'id' => $this->id,
            'username' => $this->username,
        ];
    }
}
