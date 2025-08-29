<?php

namespace App\Http\Controllers\Api;

// Gerekli kullanımlar

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerDetailResource;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BrendResource;
use App\Http\Resources\BrendResourceNew;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\SubCategoryResource;
use App\Http\Resources\WeeklyOffersResource;
use App\Http\Resources\SocialLinkResource;
use App\Models\Banner;
use App\Models\Brend;
use App\Models\LangTranslation;
use App\Models\Product;
use App\Models\BannerDetail;
use App\Models\SocialLink;
use App\Models\SubCategory;
use App\Models\WeeklySelection;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {

        $seasonalProducts =  Product::status()
                ->where('is_seasonal', true)
                ->latest()
                ->take(15)
                ->get();
        

        $specialOfferProducts = Product::status()
                ->where('is_special_offer', true)
                ->latest()
                ->take(15)
                ->get();
        

        $bundleProducts =Product::status()
                ->where('is_bundle', true)
                ->latest()
                ->take(15)
                ->get();
        




        return response()->json([
            'data' => [
                'seasonal_products' => [
                    'title' => $this->getTranslate('seasonal_products'),
                    'products' => ProductResource::collection($seasonalProducts)
                ],
                'special_offer_products' => [
                    'title' => $this->getTranslate('special_offer_products'),
                    'products' => ProductResource::collection($specialOfferProducts)
                ],
                'bundle_products' => [
                    'title' => $this->getTranslate('bundle_products'),
                    'products' => ProductResource::collection($bundleProducts)
                ]
            ]
        ]);
    }

    public function getTranslate($key){
        $query = LangTranslation::where('key',$key)->where('locale', app()->getLocale())?->first()?->value;
        return $query;
    }


    public function getBrends()
    {

        $brends = 
        // Cache::remember("brends", 3600, function () {
            // return 
            Brend::status()
                ->orderBy('order', 'asc')
                ->get();
        // });

        return BrendResource::collection($brends);
    }


    public function getBrend($id)
    {
        try{
             $brend = Brend::status()->where('id', $id)
                ->orderBy('order', 'asc')
                ->first();
                if(is_null($brend)){
                    throw new Exception("Brend not found");
                }
            
            // $products = Product::where('brend_id', $brend->id)->pluck('subcategory_id')->unique()->values();
            // $categories = SubCategory::whereIn('id', $products)->get();
            $categories= $brend->subcategories;

            $data = [
                "brend"=>new BrendResourceNew($brend),
                "subcategories"=>$categories->map(function($cat){
                    return [
                        'id'=>$cat->id,
                        'title'=>$cat->title,
                        'icon'=>url('/storage/'.$cat->icon)
                    ];
                })
            ];
            
            return $this->responseMessage("success", "Successfully Operation",$data, 200, null);


        }catch(\Exception $e){
            return $this->responseMessage("error", $e->getMessage(),null, 500, null);
        }

    }

    public function getBanners()
    {
        $banners = Cache::remember("banners", 3600, function () {
            return Banner::orderBy('order', 'asc')
                ->status()
                ->get();
        });

        return BannerResource::collection($banners);
    }
    public function getWeeklyOffers()
    {
        $currentDate = now();

        $weeklySelections = WeeklySelection::status()->where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->orderBy('order')
            ->get();

        if ($weeklySelections->isEmpty()) {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }

        $primarySelection = $weeklySelections->first();

        $productIds = [];
        foreach ($weeklySelections as $selection) {
            $productIds = array_merge($productIds, $selection->products->pluck('id')->toArray());
        }

        $productIds = array_unique($productIds);

        $products = Product::whereIn('id', $productIds)
            ->orderBy('id', 'asc')->get();

        return WeeklyOffersResource::collection($products->map(function ($product) use ($primarySelection) {
            return new WeeklyOffersResource($product, $primarySelection);
        }));
    }

    public function getBannerDetails()
    {
        $bannerDetails = Cache::remember("banner_details", 3600, function () {
            return BannerDetail::status()
                ->orderBy('order', 'asc')
                ->get();
        });

        return BannerDetailResource::collection($bannerDetails);
    }


    public function getDiscountedProducts()
    {
        $discountedProducts = Cache::remember("discounted_products", 3600, function () {
            return Product::status()
                ->whereNotNull('discountPrice')
                ->latest()
                ->limit(15)
                ->get();
        });

        return ProductResource::collection($discountedProducts);
    }

    public function getSocialLinks()
    {
        $socialLinks = Cache::remember("social_links", 3600, function () {
            return SocialLink::status()
                ->orderBy('order', 'asc')
                ->get();
        });

        return SocialLinkResource::collection($socialLinks);
    }
}
