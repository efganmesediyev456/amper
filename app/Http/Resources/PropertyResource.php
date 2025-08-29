<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    // Static array to track all used random numbers
    private static $usedRandomNumbers = [];
   
    private function generateUniqueRandomNumber()
    {
        do {
            $randomNumber = mt_rand(10000, 999999);
        } while (in_array($randomNumber, self::$usedRandomNumbers));
        
        self::$usedRandomNumbers[] = $randomNumber;
        
        return $randomNumber;
    }
    
    public function toArray($request)
    {
        $mainRandomIndex = $this->generateUniqueRandomNumber();
        
        $subProperties = $this->subProperties?->where('status',1) ? $this->subProperties?->where('status',1)?->map(function($item, $key) {
            return [
                'id' => $item->id,
                'index' => $this->generateUniqueRandomNumber(), 
                'title' => $item->title
            ];
        }) : [];
        
        return [
            'id' => $this->id,
            'index' => $mainRandomIndex,
            'title' => $this->title,
            'property_list' => $subProperties
        ];
    }
}