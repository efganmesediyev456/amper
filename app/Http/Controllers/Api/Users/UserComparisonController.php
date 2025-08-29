<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComparisonResource;
use App\Http\Resources\Products\ProductResource;
use App\Models\UserComparison;
use App\Models\Product;
use App\Repositories\UserComparisonRepository;
use App\Services\Api\Products\UserComparisonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserComparisonController extends Controller
{
    private $comparisonService;

    public function __construct(
        UserComparisonService $comparisonService,
    ) {
        $this->comparisonService = $comparisonService;
    }

    public function addToComparisons(Request $request)
    {
        try {
            $validatedData = $this->validateComparisonRequest($request);

            $result = $this->comparisonService->addToComparisons(
                Auth::id(),
                $validatedData['product_ids']
            );

            return $this->responseMessage('success', 'Məhsullar uğurla müqayisə siyahısına əlavə olundu', $this->getComparisons(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function addSingleToComparison(Request $request)
    {
        try {
            $validatedData = $this->validateSingleComparisonRequest($request);

            $result = $this->comparisonService->addToComparisons(
                Auth::id(),
                [$validatedData['product_id']]
            );

            return $this->responseMessage('success', 'Məhsul uğurla müqayisə siyahısına əlavə olundu', $this->getComparisons(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function removeFromComparison($productId)
    {
        try {
            $result = $this->comparisonService->removeFromComparison(Auth::id(), $productId);

            if ($result) {
                return $this->responseMessage('success', 'Məhsul müqayisə siyahısından silindi', $this->getComparisons(), 200, null);
            } else {
                return $this->responseMessage('error', 'Məhsul müqayisə siyahısında tapılmadı', null, 404, null);
            }
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function getComparisons()
    {
        try {
            $comparisons = $this->comparisonService->getComparisons(Auth::id());
            return ProductResource::collection($comparisons);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }


    public function getComparisonsByCategory()
    {
        try {
            $products = $this->comparisonService->getComparisons(Auth::id());

            // Group products by category
            $groupedProducts = $products->groupBy('category_id');

            $result = [];
            foreach ($groupedProducts as $categoryId => $categoryProducts) {
                $result[] = [
                    'category_id' => $categoryId,
                    'category_name' => $categoryProducts->first()->category->title,
                    'products' => ComparisonResource::collection($categoryProducts)
                ];
            }

            return $this->responseMessage('success', 'Məhsullar kateqoriyalara görə qruplaşdırıldı', $result, 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function getComparisonsByCategoryId($id)
    {
        try {
            $products = $this->comparisonService->getComparisonsByCategory(Auth::id(), $id);

            if ($products->isEmpty()) {
                return $this->responseMessage('error', 'Bu kateqoriyada müqayisə ediləcək məhsul tapılmadı', null, 404, null);
            }

            $result = ComparisonResource::collection($products);
            return $this->responseMessage('success', 'Məhsullar müqayisə edildi', $result, 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }


    public function getComparisonCategories()
    {
        try {
            $categories = $this->comparisonService->getComparisonCategories(Auth::id());

            if ($categories->isEmpty()) {
                return $this->responseMessage('error', 'Müqayisə siyahısında məhsul tapılmadı', null, 404, null);
            }

            return $this->responseMessage('success', 'Kateqoriya siyahısı', $categories, 200, null);
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
}
