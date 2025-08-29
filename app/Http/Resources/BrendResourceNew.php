<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrendResourceNew extends JsonResource
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
            'title' => $this->title,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            
        ];
    }
}