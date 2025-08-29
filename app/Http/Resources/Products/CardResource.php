<?php

namespace App\Http\Resources\Products;

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{

    public static $deliveryPrice = 5;
    public function toArray(Request $request): array
    {
        return [
             'product' => new ProductResource($this->product),
             'card_quantity' => $this->quantity,
             'total_card_price' => $this->quantity * $this->product?->price,
             'total_card_discount_price' => $this->quantity * $this->product?->discountPrice,
        ];
    }


    public static function additionalData($request, $resource): array
    {
        $totalSum = 0;

        foreach ($resource as $item) {
            $totalSum += $item->quantity * $item->product?->price;
        }

        $totalDiscountPriceSum = 0;
        foreach ($resource as $item) {
            if($item->quantity * $item->product?->discountPrice){
                $totalDiscountPriceSum += $item->quantity * $item->product?->discountPrice;
            }else{
                $totalDiscountPriceSum += $item->quantity * $item->product?->price;
            }
        }

        return [
            'total_cart_sum_price' => $totalSum,
            'total_cart_discount_sum_price' => $totalDiscountPriceSum,
            'total_discount' => $totalSum - $totalDiscountPriceSum,
            'delivery_price' => self::$deliveryPrice,
            'final_sum_price' => $totalDiscountPriceSum + self::$deliveryPrice,
        ];
    }


}


