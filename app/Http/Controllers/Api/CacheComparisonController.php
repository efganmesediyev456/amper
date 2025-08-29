<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\CategoryResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\CacheComparisonResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\Api\Products\CacheComparisonService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CacheComparisonController extends Controller
{
    private $comparisonService;

    public function __construct(
        CacheComparisonService $comparisonService,
    ) {
        $this->comparisonService = $comparisonService;
    }

    public function addToComparisons(Request $request)
    {
        try {
            $validatedData = $this->validateComparisonRequest($request);
            $cacheKey = $this->getCacheKey($request);

            $result = $this->comparisonService->addToComparisons(
                $cacheKey,
                $validatedData['product_ids']
            );

            return $this->responseMessage('success', 'Məhsullar uğurla müqayisə siyahısına əlavə olundu', $this->getComparisons($request), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function addSingleToComparison(Request $request)
    {
        try {
            $validatedData = $this->validateSingleComparisonRequest($request);
            $cacheKey = $this->getCacheKey($request);

            $result = $this->comparisonService->addToComparisons(
                $cacheKey,
                [$validatedData['product_id']]
            );

            return $this->responseMessage('success', 'Məhsul uğurla müqayisə siyahısına əlavə olundu', $this->getComparisons($request), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function removeFromComparison(Request $request, $productId)
    {
        try {
            $cacheKey = $this->getCacheKey($request);
            $result = $this->comparisonService->removeFromComparison($cacheKey, $productId);
            if ($result) {
                return $this->responseMessage('success', 'Məhsul müqayisə siyahısından silindi', $this->getComparisons($request), 200, null);
            } else {
                return $this->responseMessage('error', 'Məhsul müqayisə siyahısında tapılmadı', null, 404, null);
            }
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function getComparisons(Request $request)
    {
        try {
            $cacheKey = $this->getCacheKey($request);
            $comparisons = $this->comparisonService->getComparisons($cacheKey);
            return ProductResource::collection($comparisons);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function getComparisonsByCategory(Request $request)
    {
        try {
            $cacheKey = $this->getCacheKey($request);
            $products = $this->comparisonService->getComparisons($cacheKey);

            // Group products by category
            $groupedProducts = $products->groupBy('category_id');

            $result = [];
            foreach ($groupedProducts as $categoryId => $categoryProducts) {
                $result[] = [
                    'category_id' => $categoryId,
                    'category_name' => $categoryProducts->first()->category->title,
                    'products' => CacheComparisonResource::collection($categoryProducts)
                ];
            }

            return $this->responseMessage('success', 'Məhsullar kateqoriyalara görə qruplaşdırıldı', $result, 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function getComparisonsByCategoryId(Request $request, $id)
    {
        $this->validate($request,[
            'product_ids'=>'required|array'
        ]);
        try {
            $products = Product::where('category_id', $id)->whereIn('id', $request->product_ids)->get();
            $productsAll = $products;


            $properties = new Collection();
            foreach($products as $product){
                foreach($product->subProperties as $property){
                    $properties->push($property);
                }
            }
            $properties=$properties->unique();

            $productsList = [];

            foreach($properties as $property){
                $array = [];
                foreach($products as $product){
                   $array[$product->id]=$product?->subProperties?->where('id',$property->id)?->first() ? true : false;
                }
                $productsList[$property->property?->title][$property->title]=$array;
            }

            $allTrue = [];
            $hasMixed = [];
            foreach ($productsList as $attribute => $values) {
                foreach ($values as $value => $products) {
                    foreach ($products as $productId => $status) {
                        if (!isset($allTrue[$attribute][$value])) {
                            $allTrue[$attribute][$value] = true;
                        }

                        if ($status !== true) {
                            $allTrue[$attribute][$value] = false;
                        }
                    }

                    $uniqueValues = array_unique(array_values($products));
                    if (count($uniqueValues) > 1) {
                        $hasMixed[$attribute][$value] = $products;
                    }
                }
            }
            $finalAllTrue = [];
            foreach ($allTrue as $attribute => $values) {
                foreach ($values as $value => $isAllTrue) {
                    if ($isAllTrue) {
                        $finalAllTrue[$attribute][$value] = $productsList[$attribute][$value];
                    }
                }
            }


            $products = $productsAll->map(function($item){
                return [
                    'id'=>$item->id,
                    'title'=>$item->title,
                    'price'=>$item->price,
                    'image'=>url('/storage/'.$item->image)
                ];
             });

             if($request->type=='all'){
                return [
                    'products'=>$products,
                    'all'=>$productsList,
                    // 'similar' => $finalAllTrue,
                    // 'different' => $hasMixed,
                ];
             }
             
             else if($request->type=='similar'){
                return [
                    'products'=>$products,
                    'similar' => $finalAllTrue,
                    // 'different' => $hasMixed,
                ];
             }
             else if($request->type=='different'){
                return [
                    'products'=>$products,
                    'different' => $hasMixed,
                ];
             }

            


        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function getComparisonCategories(Request $request)
    {
        try {
            $cacheKey = $this->getCacheKey($request);
            $categories=Product::whereIn('id',$request->product_ids)->get()->pluck('category_id')->unique();

            $categories=Category::whereIn('id',$categories)->get();
            if ($categories->isEmpty()) {
                return $this->responseMessage('error', 'Müqayisə siyahısında məhsul tapılmadı', null, 404, null);
            }

            return CategoryResource::collection($categories);


        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    private function validateComparisonRequest(Request $request): array
    {
        return $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id|integer'
        ]);
    }

    private function validateSingleComparisonRequest(Request $request): array
    {
        return $request->validate([
            'product_id' => 'required|exists:products,id|integer'
        ]);
    }

    private function getCacheKey(Request $request): string
    {
        $ipAddress = $request->ip();
        return 'comparison_' . md5($ipAddress);
    }
}