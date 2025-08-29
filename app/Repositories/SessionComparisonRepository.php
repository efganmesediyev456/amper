<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\SessionComparisonRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class SessionComparisonRepository implements SessionComparisonRepositoryInterface
{
    protected $sessionPrefix = 'comparisons_';
    protected $productModel;

    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
    }

    private function getSessionKey(string $sessionId): string
    {
        return $this->sessionPrefix . $sessionId;
    }

    private function getSessionComparisonsArray(string $sessionId): array
    {
        $sessionKey = $this->getSessionKey($sessionId);
        return Session::get($sessionKey, []);
    }

    private function saveSessionComparisons(string $sessionId, array $comparisons): void
    {
        $sessionKey = $this->getSessionKey($sessionId);
        Session::put($sessionKey, $comparisons);
    }

    public function getSessionComparisons(string $sessionId)
    {
        $productIds = $this->getSessionComparisonsArray($sessionId);
        
        if (empty($productIds)) {
            return collect([]);
        }
        
        return $this->productModel
            ->whereIn('id', $productIds)
            ->with(['category', 'subProperties.property'])
            ->get();
    }

    public function bulkAddToComparisons(string $sessionId, array $productIds): array
    {
        $results = [
            'added' => [],
            'skipped' => []
        ];

        $existingProductIds = $this->getSessionComparisonsArray($sessionId);
        
        foreach ($productIds as $productId) {
            if (!in_array($productId, $existingProductIds)) {
                $existingProductIds[] = $productId;
                $results['added'][] = $productId;
            } else {
                $results['skipped'][] = $productId;
            }
        }
        
        $this->saveSessionComparisons($sessionId, $existingProductIds);
        
        return $results;
    }

    public function removeFromComparison(string $sessionId, int $productId): bool
    {
        $productIds = $this->getSessionComparisonsArray($sessionId);
        
        $key = array_search($productId, $productIds);
        
        if ($key !== false) {
            unset($productIds[$key]);
            $this->saveSessionComparisons($sessionId, array_values($productIds));
            return true;
        }
        
        return false;
    }

    public function getSessionComparisonsByCategory(string $sessionId, int $categoryId)
    {
        $products = $this->getSessionComparisons($sessionId);
        
        return $products->filter(function ($product) use ($categoryId) {
            return $product->category_id == $categoryId;
        })->values();
    }

    public function getSessionComparisonCategories(string $sessionId)
    {
        $products = $this->getSessionComparisons($sessionId);
        
        if ($products->isEmpty()) {
            return collect([]);
        }
        
        $productsByCategory = $products->groupBy('category_id');
        
        $categories = $products->pluck('category')
            ->unique('id')
            ->map(function($category) use ($productsByCategory) {
                $categoryId = $category->id;
                return [
                    'id' => $categoryId,
                    'name' => $category->title,
                    'slug' => $category->slug,
                    'product_count' => $productsByCategory->get($categoryId)->count()
                ];
            })
            ->values();
            
        return $categories;
    }
}