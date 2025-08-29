<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StatusHistoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $foundDeleted = false;

        return $this->collection->map(function ($item) use (&$foundDeleted) {
            if ($item->deleted_at && !$foundDeleted) {
                $foundDeleted = true;
            }
            
            return [
                'id' => $item->id,
                'stage' => \App\Enums\OrderStatusEnum::from($item->status)?->toString(app()->getLocale()),
                'date' => $item->created_at->format('d.m.Y'),
                'isActive' => !$foundDeleted,
            ];
        })->toArray();
    }
}
