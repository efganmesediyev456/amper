<?php

namespace App\Repositories;

use App\Models\UserComparison;
use App\Repositories\Contracts\UserComparisonRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserComparisonRepository implements UserComparisonRepositoryInterface
{
    protected $model;

    public function __construct(UserComparison $model)
    {
        $this->model = $model;
    }

    public function findByUserAndProduct(int $userId, int $productId): ?UserComparison
    {
        return $this->model->where([
            'user_id' => $userId,
            'product_id' => $productId
        ])->first();
    }

    public function create(array $data): UserComparison
    {
        return $this->model->create($data);
    }

    public function getUserComparisons(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('product')
            ->latest()
            ->get()
            ->pluck('product');
    }

    public function removeFromComparison(int $userId, int $productId): bool
    {
        $comparison = $this->findByUserAndProduct($userId, $productId);

        if ($comparison) {
            return $comparison->delete();
        }

        return false;
    }

    public function bulkAddToComparisons(int $userId, array $productIds): array
    {
        $results = [
            'added' => [],
            'skipped' => []
        ];

        DB::beginTransaction();
        try {
            foreach ($productIds as $productId) {
                $existingComparison = $this->findByUserAndProduct($userId, $productId);

                if (!$existingComparison) {
                    $this->create([
                        'user_id' => $userId,
                        'product_id' => $productId
                    ]);
                    $results['added'][] = $productId;
                } else {
                    $results['skipped'][] = $productId;
                }
            }

            DB::commit();
            return $results;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function getUserComparisonsByCategory(int $userId, int $categoryId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['product' => function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            }])
            ->whereHas('product', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->get()
            ->pluck('product');
    }


    public function getUserComparisonCategories(int $userId)
    {
        $products = $this->model
            ->where('user_id', $userId)
            ->with('product.category')
            ->get()
            ->pluck('product');
        
        $productsByCategory = $products->groupBy('category_id');
        
        $categories = $products->pluck('category')
            ->unique('id')
            ->map(function($category) use ($productsByCategory) {
                $categoryId = $category->id;
                return [
                    'id' => $categoryId,
                    'name' => $category->title,
                    'slug' => $category->slug,
                    'product_count' => $productsByCategory->get($categoryId)->count()
                ];
            })
            ->values();
            
        return $categories;
    }
}
