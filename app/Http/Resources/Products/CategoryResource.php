<?php

namespace App\Http\Resources\Products;

use App\Http\Resources\SubCategoryResource;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function seoKeywords($values){
        $array = json_decode($values, true);
        $data = [];
        if(is_array($array) and count($array)){
            foreach ($array as $key => $value) {
                $value['id']=$key+1;
                $data[] = $value;
            }
        }
        return $data;
    }

    public function toArray(Request $request): array
    {
        // Paginate products - you can adjust the page size (15) as needed
//        $paginatedProducts = $this->products()->paginate(15);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->getTranslatedSlugs($this),
            'seo_keywords'=>$this->seoKeywords($this->seo_keywords),
            'seo_description'=>$this->seo_description,
            'image' => url('storage/'.$this->image),
            'subCategories'=>SubCategoryResource::collection($this->subCategories),
            // 'sub_categories' => $this->subCategories?->map(function ($item) {
            //     return [
            //         'id' => $item->id,
            //         'title' => $item->title,
            //         'slug' => $this->getTranslatedSlugs($item),
            //         'seo_keywords'=>$this->seoKeywords($item->seo_keywords),
            //         'seo_description'=>$item->seo_description,
            //         'image' => url('storage/'.$item->image),
            //     ];
            // }),
//            'products' => [
//                'data' => ProductResource::collection($paginatedProducts),
//                'meta' => [
//                    'total' => $paginatedProducts->total(),
//                    'per_page' => $paginatedProducts->perPage(),
//                    'current_page' => $paginatedProducts->currentPage(),
//                    'last_page' => $paginatedProducts->lastPage(),
//                    'from' => $paginatedProducts->firstItem(),
//                    'to' => $paginatedProducts->lastItem(),
//                ]
//            ]
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
