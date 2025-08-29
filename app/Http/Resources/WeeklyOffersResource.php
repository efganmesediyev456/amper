<?php

namespace App\Http\Resources;

use App\Models\Language;
use App\Models\WeeklySelection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyOffersResource extends JsonResource
{
    private $weeklySelection;

    public function __construct($resource, $weeklySelection = null)
    {
        parent::__construct($resource);
        $this->weeklySelection = $weeklySelection;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $endDate = $this->weeklySelection ? Carbon::parse($this->weeklySelection->end_date) : Carbon::now()->addDays(7);
        $now = Carbon::now();
        $diff = $now->diff($endDate);

        $infoTitle = $this->weeklySelection ? $this->weeklySelection->title : "Həftənin təklifləri";

        $discountText = "";
        if ($this->discountPrice && $this->price > $this->discountPrice) {
            $discountPercentage = round((($this->price - $this->discountPrice) / $this->price) * 100);
            $discountText = "{$discountPercentage}% endirim";
        } else {
            
            $discountText = '';
        }

        return [
            'id' => $this->id,
            'category' => $this->category?->title,
            'day' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'discount' => $discountText,
            'price' => (string)($this->discountPrice ?? $this->price),
            'productImage' => $this->image_url,
            'title' => $this->title,
            'infoTitle' => $infoTitle,
            'slug'=>$this->getTranslatedSlugs()
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