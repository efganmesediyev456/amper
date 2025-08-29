<?php

namespace App\Http\Resources;

use App\Models\Language;
use App\Models\VacancyShareSocial;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    public function toArray($request)
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
            'vacancy_title' => $this->vacancy_title,
            'vacancy_location' => $this->vacancy_location,
            'description' => $this->description,
            'slug' => $this->getTranslatedSlugs(),
            'seo_keywords' => $data,
            'seo_description' => $this->seo_description,
            'vacany_start_at' => $this->vacany_start_at->format('d.m.Y'),
            'vacany_expired_at' => $this->vacany_expired_at->format('d.m.Y'),
            'image' => url('/storage/'.$this->image),
            'view' => $this->view
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
