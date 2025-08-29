<?php

namespace App\Services\Api\Products;

use App\Repositories\Contracts\UserCardRepositoryInterface;

class UserCardService
{
    private $repository;

    public function __construct(UserCardRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function addToCards(int $userId, array $products): array
    {
        return $this->repository->bulkAddToCards($userId, $products);
    }

    public function getCards(int $userId)
    {
        return $this->repository->getUserCards($userId);
    }

    public function removeCardProduct(int $userId, int $productId): void
    {
        $this->repository->removeCardProduct($userId, $productId);
    }

    public function updateCardQuantityIncrease(int $userId, int $productId): void
    {
        $this->repository->updateCardQuantityIncrease($userId, $productId);
    }
    public function updateCardQuantityDecrease(int $userId, int $productId): void
    {
        $this->repository->updateCardQuantityDecrease($userId, $productId);
    }
}
