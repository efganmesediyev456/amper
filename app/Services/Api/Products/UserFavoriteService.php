<?php
namespace App\Services\Api\Products;

use App\Repositories\Contracts\UserFavoriteRepositoryInterface;

class UserFavoriteService 
{
    private $repository;

    public function __construct(UserFavoriteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addToFavorites(int $userId, array $productIds): array
    {
        return $this->repository->bulkAddToFavorites($userId, $productIds);
    }

    public function getFavorites(int $userId)
    {
        return $this->repository->getUserFavorites($userId);
    }
}
