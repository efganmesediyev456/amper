<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComparisonResource;
use App\Http\Resources\Products\ProductResource;
use App\Models\Product;
use App\Services\Api\Products\SessionComparisonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionComparisonController extends Controller
{
    private $comparisonService;

    public function __construct(
        SessionComparisonService $comparisonService,
    ) {
        $this->comparisonService = $comparisonService;
    }

    public function addToComparisons(Request $request)
    {
        try {
            $validatedData = $this->validateComparisonRequest($request);
            $sessionId = $this->getSessionIdentifier($request);


            $result = $this->comparisonService->addToComparisons(
                $sessionId,
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
            $sessionId = $this->getSessionIdentifier($request);

            $result = $this->comparisonService->addToComparisons(
                $sessionId,
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
            $sessionId = $this->getSessionIdentifier($request);
            $result = $this->comparisonService->removeFromComparison($sessionId, $productId);

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
            $sessionId = $this->getSessionIdentifier($request);

            $comparisons = $this->comparisonService->getComparisons($sessionId);
            return ProductResource::collection($comparisons);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function getComparisonsByCategory(Request $request)
    {
        try {
            $sessionId = $this->getSessionIdentifier($request);
            $products = $this->comparisonService->getComparisons($sessionId);

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

    public function getComparisonsByCategoryId(Request $request, $id)
    {
        try {
            $sessionId = $this->getSessionIdentifier($request);
            $products = $this->comparisonService->getComparisonsByCategory($sessionId, $id);

            if ($products->isEmpty()) {
                return $this->responseMessage('error', 'Bu kateqoriyada müqayisə ediləcək məhsul tapılmadı', null, 404, null);
            }

            $result = ComparisonResource::collection($products);
            return $this->responseMessage('success', 'Məhsullar müqayisə edildi', $result, 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    public function getComparisonCategories(Request $request)
    {
        try {
            $sessionId = $this->getSessionIdentifier($request);
            $categories = $this->comparisonService->getComparisonCategories($sessionId);

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

    private function getSessionIdentifier(Request $request): string
    {
        $ipAddress = $request->ip();
        $sessionKey = 'comparison_session_' . $ipAddress;
        
        return $sessionKey;
    }
}