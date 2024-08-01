<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "message" => "Tampil Data Profile",
            "data" => [
                        'id' => $this->id,
                        'age' => $this->age,
                        'bio' => $this->bio,
                        'user_id' => $this->user_id,
                        'created_at' => $this->created_at,
                        'updated_at' => $this->updated_at
            ]
        ];
    }
}
