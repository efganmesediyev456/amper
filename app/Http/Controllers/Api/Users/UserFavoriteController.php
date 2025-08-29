<?php
namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\ProductResource;
use App\Models\UserFavorite;
use App\Models\Product;
use App\Repositories\UserFavoriteRepository;
use App\Services\Api\Products\UserFavoriteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserFavoriteController extends Controller
{
    private $favoriteService;
    public function __construct(
        UserFavoriteService $favoriteService,
    ) {
        $this->favoriteService = $favoriteService;
    }

    public function addToFavorites(Request $request)
    {
        try {
            $validatedData = $this->validateFavoriteRequest($request);

            $result = $this->favoriteService->addToFavorites(
                Auth::id(), 
                $validatedData['product_ids']
            );
            
            return $this->responseMessage('success',__('api.Products have been successfully added to your favorites'),$this->getFavorites(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error',$e->getMessage(), null, 400, null);
        }
    }

    public function getFavorites()
    {
        try {
            $favorites = $this->favoriteService->getFavorites(Auth::id());
            return ProductResource::collection($favorites);
        } catch (\Exception $e) {
            return $this->responseMessage('error',$e->getMessage(), null, 400, null);
        }
    }

    private function validateFavoriteRequest(Request $request): array
    {
        return $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id|integer'
        ]);
    }
}
