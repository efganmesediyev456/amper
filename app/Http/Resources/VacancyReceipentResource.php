<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyReceipentResource extends JsonResource
{
    public function toArray($request)
    {
        
        return [
            'id' => $this->id,
            'title' => $this->title
        ];
    }
}
