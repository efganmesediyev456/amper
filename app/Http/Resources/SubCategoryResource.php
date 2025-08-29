<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        // Paginate products - you can adjust the page size (15) as needed
//        $paginatedProducts = $this->products()->paginate(15);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->getTranslatedSlugs($this),
            'image' => url('storage/'.$this->image),
            'brends'=>BrendResource::collection($this->brends),
        ];
    }
    private function getTranslatedSlugs($item): array
    {
        $languages = Language::pluck('code')->toArray();
        $slugs = [];
        foreach ($languages as $lang) {
            $slugs[$lang] = $item->getTranslation('slug', $lang) ?? null;
        }
        return $slugs;
    }
}
