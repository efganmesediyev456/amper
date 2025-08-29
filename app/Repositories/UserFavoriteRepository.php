<?php
namespace App\Repositories;

use App\Models\Product;
use App\Models\UserFavorite;
use App\Repositories\Contracts\UserFavoriteRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class UserFavoriteRepository implements UserFavoriteRepositoryInterface
{
    protected $model;

    public function __construct(UserFavorite $model)
    {
        $this->model = $model;
    }

    public function findByUserAndProduct(int $userId, int $productId): ?UserFavorite
    {
        return $this->model->where([
            'user_id' => $userId,
            'product_id' => $productId
        ])->first();
    }

    public function create(array $data): UserFavorite
    {
        return $this->model->create($data);
    }

    public function getUserFavorites(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('product')
            ->orderBy('id','desc')
            ->get()
            ->pluck('product');
    }

    public function bulkAddToFavorites(int $userId, array $productIds): array
    {
        $results = [
            'added' => [],
            'skipped' => []
        ];

        DB::beginTransaction();
        try {
            foreach ($productIds as $productId) {
                $existingFavorite = $this->findByUserAndProduct($userId, $productId);

                if(is_null(Product::status()->find($productId))){
                    throw new Exception('Belə bir məhsul mövcud deyil');
                }

                if (!$existingFavorite) {
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
}
