<?php
namespace App\Repositories\Contracts;

use App\Models\UserComparison;

interface UserComparisonRepositoryInterface
{
    public function create(array $data): UserComparison;
    public function getUserComparisons(int $userId);
    public function bulkAddToComparisons(int $userId, array $productIds): array;
    public function removeFromComparison(int $userId, int $productId): bool;
    public function getUserComparisonsByCategory(int $userId, int $categoryId);
}