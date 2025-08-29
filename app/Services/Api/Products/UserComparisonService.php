<?php

namespace App\Services\Api\Products;

use App\Repositories\Contracts\UserComparisonRepositoryInterface;

class UserComparisonService
{
    private $repository;

    public function __construct(UserComparisonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addToComparisons(int $userId, array $productIds): array
    {
        return $this->repository->bulkAddToComparisons($userId, $productIds);
    }

    public function addSingleToComparison(int $userId, int $productId): array
    {
        return $this->repository->bulkAddToComparisons($userId, [$productId]);
    }

    public function getComparisons(int $userId)
    {
        return $this->repository->getUserComparisons($userId);
    }

    public function removeFromComparison(int $userId, int $productId): bool
    {
        return $this->repository->removeFromComparison($userId, $productId);
    }

    public function getComparisonsByCategory(int $userId, int $categoryId)
    {
        return $this->repository->getUserComparisonsByCategory($userId, $categoryId);
    }

    public function getComparisonCategories(int $userId)
    {
        return $this->repository->getUserComparisonCategories($userId);
    }
}
