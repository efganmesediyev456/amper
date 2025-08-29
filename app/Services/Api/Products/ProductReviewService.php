<?php

namespace App\Services\Api\Products;

use App\Repositories\Contracts\ProductReviewRepositoryInterface as ContractsProductReviewRepositoryInterface;
use App\Repositories\Interfaces\ProductReviewRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class ProductReviewService
{
    protected $productReviewRepository;

    public function __construct(ContractsProductReviewRepositoryInterface $productReviewRepository)
    {
        $this->productReviewRepository = $productReviewRepository;
    }

    public function createReview(array $data)
    {
        $validator = Validator::make($data, [
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);


        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        return $this->productReviewRepository->create($data);
    }
}
