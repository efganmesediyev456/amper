<?php
namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\OrderResource;
use App\Http\Resources\Products\ProductResource;
use App\Models\Order;
use App\Models\UserFavorite;
use App\Models\Product;
use App\Repositories\UserFavoriteRepository;
use App\Services\Api\Products\UserFavoriteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserOrderController extends Controller
{



    public function getOrders()
    {
        try {
            $user = Auth::user();
            $orders = $user->orders()->orderBy("id","desc")->paginate(10);
            return OrderResource::collection($orders);
        } catch (\Exception $e) {
            return $this->responseMessage('error',$e->getMessage(), null, 400, null);
        }
    }

    public function changeAddress(Request $request){

        try {

            $this->validate($request,[
                'city_id'=>'required|exists:cities,id',
                'address'=>'required',
                'additional_info'=>'required|string',
                'order_id'=>'required|integer|exists:orders,id',
            ]);

            $order = Order::find($request->order_id);


            $order->deliveryAddress()->update([
                'city_id'=>$request->city_id,
                'address'=>$request->address,
                'additional_info'=>$request->additional_info
            ]);

            return $this->responseMessage('success', 'Ünvan uğurla dəyişdirildi',new OrderResource($order), 200, null);

        } catch (\Exception $e) {
            return $this->responseMessage('error',$e->getMessage(), null, 400, null);
        }

    }

}
