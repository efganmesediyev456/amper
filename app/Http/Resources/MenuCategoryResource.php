<?php

namespace App\Http\Resources;

use App\Models\Language;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuCategoryResource extends JsonResource
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
        $count = Product::where('category_id', $this->id)->count();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->getTranslatedSlugs($this),
            'seo_keywords'=>$this->seoKeywords($this->seo_keywords),
            'seo_description'=>$this->seo_description,
            'image' => url('storage/'.$this->image),
            'subcategories'=>SubCategoryResource::collection($this->subCategories),
            'count'=> $count
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
