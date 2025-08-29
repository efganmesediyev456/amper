<?php

namespace App\Http\Resources\Products;

use App\Enums\OrderStatusEnum;
use App\Http\Resources\StatusHistoryResource;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


     public function toStatuses($statuses)
    {
        return $statuses->map(function ($item)  {
            return [
                'id' => $item->id,
                'status' => $item->status,
                'stage' => \App\Enums\OrderStatusEnum::from($item->status)?->toString(app()->getLocale()),
                'date' => $this->status->status >= $item->status ?  $item->created_at->format('d.m.Y') : null,
                'isActive' => $this->status->status == OrderStatusEnum::CANCELED->value ? false : $this->status->status >= $item->status,
            ];
        })->toArray();
    }


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'delivery_type' => $this->delivery_type,
            'total_amount' => $this->total_amount,
            'order_date' => $this->created_at->translatedFormat('d F Y'),
            'status_histories'=>$this->toStatuses($this->statusesHistories->sortBy('id')),
            'status'=>new StatusHistoryResource($this->status),
            'items'=>OrderItemResource::collection($this->items),
            'store_address' => $this->when($this->delivery_type=='store_pickup', new StoreAddressResource($this->store)),
            'delivery_address' => $this->when($this->delivery_type=='home_delivery', new DeliveryAddressResource($this->deliveryAddress)),
        ];
    }

}
