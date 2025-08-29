<?php

namespace App\Http\Resources\Products;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreAddressResource extends JsonResource
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
            'address' => $this->address,
        ];
    }

}
