<?php

namespace App\Http\Resources\Category;

use App\Http\Resources\Book\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResourceById extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'message' => 'Berhasil Menampilkan Data Category Dengan ID ' . $this->id,
        'data' => [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'list_books' => BookResource::collection($this->whenLoaded('list_books')),
        ]
    ];
    }
}
