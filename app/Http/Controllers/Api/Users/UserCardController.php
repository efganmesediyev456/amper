<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\CardResource;
use App\Http\Resources\Products\ProductResource;
use App\Models\UserCard;
use App\Services\Api\Products\UserCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserCardController extends Controller
{
    private $cardService;

    public function __construct(UserCardService $cardService)
    {
        $this->cardService = $cardService;
    }

    public function addToCards(Request $request)
    {
        try {
            $validatedData = $this->validateCardRequest($request);

            $result = $this->cardService->addToCards(
                Auth::id(),
                $validatedData['products']
            );

            return $this->responseMessage('success', __('api.Products have been successfully added to the cart'), $this->getCards(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }


    public function getCards()
    {
        try {
            $cards = $this->cardService->getCards(Auth::id());
            $cardsResource = CardResource::collection($cards);
            return $cardsResource->additional([
                'total' => CardResource::additionalData(request(), $cards)
            ]);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }


    public function calculatePrice(Request $request){
        try {
            $collections = new Collection();
            foreach($request->products as $product){
                $userCard= new UserCard([
                    'user_id'=>null,
                    'product_id'=>$product['id'],
                    'quantity'=>$product['quantity']
                ]);
                $collections->add($userCard);
            }
            
            $cardsResource = CardResource::collection($collections);
            return $cardsResource->additional([
                'total' => CardResource::additionalData(request(), $collections)
            ]);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }







    private function validateCardRequest(Request $request): array
    {
        return $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id|integer',
            'products.*.quantity' => 'required|integer|min:1'
        ],[
            'products.required' => __('api.products.required'),
            'products.array' => __('api.products.array'),
            'products.min' => __('api.products.min'),
        
            'products.*.id.required' => __('api.products.id_required'),
            'products.*.id.exists' => __('api.products.id_exists'),
            'products.*.id.integer' => __('api.products.id_integer'),
        
            'products.*.quantity.required' => __('api.products.quantity_required'),
            'products.*.quantity.integer' => __('api.products.quantity_integer'),
            'products.*.quantity.min' => __('api.products.quantity_min'),
        ]);
    }


    public function removeCardProduct(Request $request)
    {
        try {
            $validatedData = $this->validateRemoveCardRequest($request);
            $this->cardService->removeCardProduct(Auth::id(), $validatedData['product_id']);
            return $this->responseMessage('success', __('api.Product has been removed from the cart'), $this->getCards(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }


    public function updateCardQuantityIncrease(Request $request)
    {
        try {
            $validatedData = $this->validateUpdateQuantityRequestIncrease($request);

            $this->cardService->updateCardQuantityIncrease(
                Auth::id(),
                $validatedData['product_id']
            );

            return $this->responseMessage('success', __('api.Product quantity has been successfully updated'), $this->getCards(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }
    public function updateCardQuantityDecrease(Request $request)
    {
        try {
            $validatedData = $this->validateUpdateQuantityRequestIncrease($request);

            $this->cardService->updateCardQuantityDecrease(
                Auth::id(),
                $validatedData['product_id']
            );

            return $this->responseMessage('success', __('api.Product quantity has been successfully updated'), $this->getCards(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    private function validateUpdateQuantityRequestIncrease(Request $request): array
    {
        return $request->validate([
            'product_id' => 'required|exists:user_cards,product_id|integer',
        ]);
    }

    private function validateRemoveCardRequest(Request $request): array
    {
        return $request->validate([
            'product_id' => 'required|exists:user_cards,product_id|integer'
        ]);
    }
}
