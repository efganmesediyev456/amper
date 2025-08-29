<?php

namespace App\Http\Resources\Products;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryAddressResource extends JsonResource
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
            'city' => $this->city?->title,
            'address' => $this->address,
            'additional_info' => $this->additional_info,
        ];
    }

}
