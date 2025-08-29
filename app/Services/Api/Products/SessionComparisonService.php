<?php

namespace App\Services\Api\Products;

use App\Repositories\Contracts\SessionComparisonRepositoryInterface;

class SessionComparisonService
{
    private $repository;

    public function __construct(SessionComparisonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addToComparisons(string $sessionId, array $productIds): array
    {
        return $this->repository->bulkAddToComparisons($sessionId, $productIds);
    }

    public function addSingleToComparison(string $sessionId, int $productId): array
    {
        return $this->repository->bulkAddToComparisons($sessionId, [$productId]);
    }

    public function getComparisons(string $sessionId)
    {
        return $this->repository->getSessionComparisons($sessionId);
    }

    public function removeFromComparison(string $sessionId, int $productId): bool
    {
        return $this->repository->removeFromComparison($sessionId, $productId);
    }

    public function getComparisonsByCategory(string $sessionId, int $categoryId)
    {
        return $this->repository->getSessionComparisonsByCategory($sessionId, $categoryId);
    }

    public function getComparisonCategories(string $sessionId)
    {
        return $this->repository->getSessionComparisonCategories($sessionId);
    }
}