<?php

namespace App\Services\Api\Products;

use App\Repositories\Contracts\CacheComparisonRepositoryInterface;

class CacheComparisonService
{
    private $repository;

    public function __construct(CacheComparisonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addToComparisons(string $cacheKey, array $productIds): array
    {
        return $this->repository->bulkAddToComparisons($cacheKey, $productIds);
    }

    public function addSingleToComparison(string $cacheKey, int $productId): array
    {
        return $this->repository->bulkAddToComparisons($cacheKey, [$productId]);
    }

    public function getComparisons(string $cacheKey)
    {
        return $this->repository->getCacheComparisons($cacheKey);
    }

    public function removeFromComparison(string $cacheKey, int $productId): bool
    {
        return $this->repository->removeFromComparison($cacheKey, $productId);
    }

    public function getComparisonsByCategory(string $cacheKey, int $categoryId)
    {
        return $this->repository->getCacheComparisonsByCategory($cacheKey, $categoryId);
    }

    public function getComparisonCategories(string $cacheKey)
    {
        return $this->repository->getCacheComparisonCategories($cacheKey);
    }
}