<?php

namespace App\Http\Resources;

use App\Enums\OrderStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusHistoryResource extends JsonResource
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
            'status'=>$this->status,
            'stage'=>OrderStatusEnum::from($this->status)?->toString(app()->getLocale()),
            'date'=>$this->created_at->format('d.m.Y'),
            'isActive'=>$this->status ? true : false
        ];
    }
}
