<?php

namespace App\Repositories\Contracts;

interface SessionComparisonRepositoryInterface
{
    public function getSessionComparisons(string $sessionId);
    public function bulkAddToComparisons(string $sessionId, array $productIds): array;
    public function removeFromComparison(string $sessionId, int $productId): bool;
    public function getSessionComparisonsByCategory(string $sessionId, int $categoryId);
    public function getSessionComparisonCategories(string $sessionId);
}