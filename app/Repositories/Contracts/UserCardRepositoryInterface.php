<?php
namespace App\Repositories\Contracts;

use App\Models\UserCard;
use App\Models\UserFavorite;

interface UserCardRepositoryInterface
{
    public function create(array $data): UserCard;
    public function getUserCards(int $userId);
    public function bulkAddToCards(int $userId, array $productIds): array;
    public function removeCardProduct(int $userId, int $productId): void;

    public function updateCardQuantityIncrease(int $userId, int $productId): void;
    public function updateCardQuantityDecrease(int $userId, int $productId): void;

}
