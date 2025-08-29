<?php

namespace App\Http\Resources\Regulations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateResource extends JsonResource
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
            'date' => $this?->date?->format('d.m.Y'),
            'file' => url('/storage/'.$this->file),
        ];
    }
}
