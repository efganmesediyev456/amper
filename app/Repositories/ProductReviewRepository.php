<?php

namespace App\Repositories;

use App\Models\ProductReview;
use App\Repositories\Contracts\ProductReviewRepositoryInterface;

class ProductReviewRepository implements ProductReviewRepositoryInterface
{
    public function create(array $data)
    {
        return ProductReview::create([
            'product_id' => $data['product_id'],
            'user_id' => auth()->id(),
            'comment' => $data['comment'],
            'rating' => $data['rating'],
        ]);
    }
}
