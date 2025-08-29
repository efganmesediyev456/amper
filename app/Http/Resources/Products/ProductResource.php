<?php

namespace App\Http\Resources\Products;

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


     protected function formatCreatedAt($date)
     {
         if (!$date) return null;

         $carbon = Carbon::parse($date);
         $daysDiff = $carbon->diffInDays(now());

         if ($daysDiff <= 1) {
             return $carbon->diffForHumans();
         } else {
             return $carbon->format('d/m/Y');
         }
     }



    public function toArray(Request $request): array
    {
        $array = json_decode($this->seo_keywords, true);
        $data = [];
        if(is_array($array) and count($array)){
           foreach ($array as $key => $value) {
               $value['id']=$key+1;
               $data[] = $value;
           }
        }

        return [
            'id' => $this->id,
            'productTitle' => $this->title,
            'slug' => $this->getTranslatedSlugs(),
            'seo_keywords'=>$data,
            'seo_description'=>$this->seo_description,
            'description'=>$this->description,
            'productCode'=>$this->product_code,
            'isNew'=>$this->is_new ? true : false,
            'productPrice'=>$this->price,
            'discountPrice'=>$this->discountPrice,
            'stockCount'=>$this->quantity,
            'category'=>$this->category?->title,
            'category_id'=>$this->category?->id,
//            'subcategory'=>$this->subcategory?->title,
            'brand'=>$this->brand?->title,
            'productImage' =>  $this->image_url,
            'images' => $this->media?->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image' => $gallery->image_url,
                ];
            }),
            'properties' => $this->subProperties?->map(function ($property) {
                return [
                    'id' => $property->id,
                    'property' => $property->property->title,
                    'value' => $property->title,
                ];
            }),
            'comments' => $this->reviews?->map(function ($property) {
                return [
                    'id' => $property->id,
                    'comment' => $property->comment,
                    'range' => $property->rating,
                    'time' => $this->formatCreatedAt($property->created_at)
                ];
            }),

            'commentStarCount' => $this->reviews()?->count(),
            'commentStarAvg' => number_format($this->reviews()?->avg('rating') ?? 0, 1, '.', ''),
            'total_sales' => $this->total_sales,
            'brend' => $this->brend?->title,
            'brend_id'=>$this->brend_id
       ];
    }

    private function getTranslatedSlugs(): array
    {
        $languages = Language::pluck('code')->toArray();

        $slugs = [];
        foreach ($languages as $lang) {
            $slugs[$lang] = $this->getTranslation('slug', $lang) ?? null;
        }

        return $slugs;
    }
}


