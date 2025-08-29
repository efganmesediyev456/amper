<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Services\Api\Products\ProductReviewService;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    protected $productReviewService;

    public function __construct(ProductReviewService $productReviewService)
    {
        $this->productReviewService = $productReviewService;
    }

    public function store(Request $request)
    {
        try {
            $review = $this->productReviewService->createReview($request->all());
            return $this->responseMessage('success',__('api.Review has been successfully added'),$review, 201, null);
        } catch (\Exception $e) {
           return $this->responseMessage('error',$e->getMessage(),null, 400, null);
        }
    }
}
