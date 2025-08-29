<?php
namespace App\Repositories\Contracts;

use App\Models\UserFavorite;

interface UserFavoriteRepositoryInterface
{
    public function create(array $data): UserFavorite;
    public function getUserFavorites(int $userId);
    public function bulkAddToFavorites(int $userId, array $productIds): array;
}
