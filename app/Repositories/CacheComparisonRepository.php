<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\CacheComparisonRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CacheComparisonRepository implements CacheComparisonRepositoryInterface
{
    protected $productModel;
    protected $cacheTTL = 60 * 24 * 7; 

    public function __construct(Product $productModel)
    {
        $this->productModel = $productModel;
    }

    /**
     * Get product IDs from cache
     */
    private function getCacheComparisonIds(string $cacheKey): array
    {
        return Cache::get($cacheKey, []);
    }

    /**
     * Save product IDs to cache
     */
    private function saveCacheComparisons(string $cacheKey, array $productIds): void
    {
        Cache::put($cacheKey, $productIds, $this->cacheTTL);
    }

    /**
     * Get products from cache by key
     */
    public function getCacheComparisons(string $cacheKey)
    {
        $productIds = $this->getCacheComparisonIds($cacheKey);
        
        if (empty($productIds)) {
            return collect([]);
        }
        
        return $this->productModel
            ->whereIn('id', $productIds)
            ->with(['category', 'subProperties.property'])
            ->get();
    }

    /**
     * Add multiple products to comparisons
     */
    public function bulkAddToComparisons(string $cacheKey, array $productIds): array
    {
        $results = [
            'added' => [],
            'skipped' => []
        ];

        $existingProductIds = $this->getCacheComparisonIds($cacheKey);
        
        foreach ($productIds as $productId) {
            if (!in_array($productId, $existingProductIds)) {
                $existingProductIds[] = (int)$productId;
                $results['added'][] = (int)$productId;
            } else {
                $results['skipped'][] = (int)$productId;
            }
        }
        
        $this->saveCacheComparisons($cacheKey, $existingProductIds);
        
        return $results;
    }

    /**
     * Remove a product from comparisons
     */
    public function removeFromComparison(string $cacheKey, int $productId): bool
    {
        $productIds = $this->getCacheComparisonIds($cacheKey);
        
        $key = array_search($productId, $productIds);
        
        if ($key !== false) {
            unset($productIds[$key]);
            $this->saveCacheComparisons($cacheKey, array_values($productIds));
            return true;
        }
        
        return false;
    }

    /**
     * Get comparisons by category ID
     */
    public function getCacheComparisonsByCategory(string $cacheKey, int $categoryId)
    {
        $products = $this->getCacheComparisons($cacheKey);
        
        return $products->filter(function ($product) use ($categoryId) {
            return $product->category_id == $categoryId;
        })->values();
    }

    /**
     * Get comparison categories
     */
    public function getCacheComparisonCategories(string $cacheKey)
    {
        $products = $this->getCacheComparisons($cacheKey);
        
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