<?php

namespace App\Http\Resources\Regulations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'name' => $this->title,
            'position' => $this->position,
            'phone' => $this->phone,
            'mail' => $this->email,
            'image' => url('/storage/'.$this->image),
        ];
    }
}
