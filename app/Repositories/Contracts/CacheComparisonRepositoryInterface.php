<?php

namespace App\Repositories\Contracts;

interface CacheComparisonRepositoryInterface
{
    public function getCacheComparisons(string $cacheKey);
    public function bulkAddToComparisons(string $cacheKey, array $productIds): array;
    public function removeFromComparison(string $cacheKey, int $productId): bool;
    public function getCacheComparisonsByCategory(string $cacheKey, int $categoryId);
    public function getCacheComparisonCategories(string $cacheKey);
}