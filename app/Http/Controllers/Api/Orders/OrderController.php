<?php

namespace App\Http\Controllers\Api\Orders;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryAddress;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\Store;
use App\Models\UserCard;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class OrderController extends Controller
{
    public $orderPrice = 5;

    public function getPrice($productItem)
    {
        return $productItem->discountPrice ? $productItem->discountPrice : $productItem->price;
    }

    public function getProductPrice($id)
    {
        $productItem = Product::find($id);
        return $productItem->discountPrice ? $productItem->discountPrice : $productItem->price;
    }
    public function placeOrder(Request $request)
    {
        // $this->validate($request, [
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'phone' => 'required|string|max:20',
        //     'delivery_type' => 'required|string|in:home_delivery,store_pickup',
        //     'cart_items' => 'required|array',
        //     'cart_items.*.product_id' => 'required|exists:products,id',
        //     'cart_items.*.quantity' => 'required|integer|min:1',
        //     'cart_items.*.price' => 'required|numeric|min:0',
        // ], [
        //     'first_name.required' => __('api.first_name.required'),
        //     'first_name.string' => __('api.first_name.string'),
        //     'first_name.max' => __('api.first_name.max'),

        //     'last_name.required' => __('api.last_name.required'),
        //     'last_name.string' => __('api.last_name.string'),
        //     'last_name.max' => __('api.last_name.max'),

        //     'email.required' => __('api.email.required'),
        //     'email.email' => __('api.email.email'),
        //     'email.max' => __('api.email.max'),

        //     'phone.required' => __('api.phone.required'),
        //     'phone.string' => __('api.phone.string'),
        //     'phone.max' => __('api.phone.max'),

        //     'delivery_type.required' => __('api.delivery_type.required'),
        //     'delivery_type.string' => __('api.delivery_type.string'),
        //     'delivery_type.in' => __('api.delivery_type.in'),

        //     'cart_items.required' => __('api.cart_items.required'),
        //     'cart_items.array' => __('api.cart_items.array'),

        //     'cart_items.*.product_id.required' => __('api.cart_items.product_id.required'),
        //     'cart_items.*.product_id.exists' => __('api.cart_items.product_id.exists'),

        //     'cart_items.*.quantity.required' => __('api.cart_items.quantity.required'),
        //     'cart_items.*.quantity.integer' => __('api.cart_items.quantity.integer'),
        //     'cart_items.*.quantity.min' => __('api.cart_items.quantity.min'),

        //     'cart_items.*.price.required' => __('api.cart_items.price.required'),
        //     'cart_items.*.price.numeric' => __('api.cart_items.price.numeric'),
        //     'cart_items.*.price.min' => __('api.cart_items.price.min'),
        // ]);


        $user = null;
        if ($request->delivery_type == 'home_delivery') {
            $deliveryValidator = Validator::make($request->all(), [
                'city_id' => 'required|integer|exists:cities,id',
                'address' => 'required|string|max:500',
                'additional_info' => 'nullable|string|max:1000',
            ]);
            if ($deliveryValidator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $deliveryValidator->errors()->first(),
                    'errors' => $deliveryValidator->errors()
                ], 422);
            }
        } else { // store_pickup
            $pickupValidator = Validator::make($request->all(), [
                'store' => 'required',
            ]);

            if ($pickupValidator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $pickupValidator->errors()
                ], 422);
            }
        }

        if (auth('api')->check()) {
            $user = Auth::guard('api')->user();

            $userCart = UserCard::where('user_id', $user->id)->get();
            $cartProductIds = $userCart->pluck('product_id')->toArray();
            foreach ($request->cart_items as $item) {
                $productId = $item['product_id'];
                if (!in_array($productId, $cartProductIds)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => __('api.One or more products are not in your cart'),
                        'product_id' => $productId
                    ], 400);
                }
            }
        }


        $cartTotal = $this->orderPrice;
        foreach ($request->cart_items as $item) {
            $productItem = Product::find($item['product_id']);
            $price = $this->getPrice($productItem);
            $cartTotal += $price * $item['quantity'];
        }

        try {
            \DB::beginTransaction();

            $order = new Order();
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->delivery_type = $request->delivery_type;
            $order->total_amount = $cartTotal;
            $order->user_id = Auth::guard('api')->user()?->id;

            $order->save();


            for ($i = 1; $i <= 5; $i++) {

                $orderStatus = new OrderStatus();
                $orderStatus->order_id = $order->id;
                $orderStatus->status = $i;
                $orderStatus->save();
                if ($i != 1) {
                    $orderStatus->delete();
                }
            }

            // Add delivery address or store pickup details
            if ($request->delivery_type == 'home_delivery') {
                $deliveryAddress = new DeliveryAddress();
                $deliveryAddress->order_id = $order->id;
                $deliveryAddress->city_id = $request->city_id;
                $deliveryAddress->address = $request->address;
                $deliveryAddress->additional_info = $request->additional_info ?? null;
                $deliveryAddress->save();
            } else {
                $store = new Store();
                $store->address = $request->store;
                $store->order_id = $order->id;
                $store->save();
            }


            // Save order items
            foreach ($request->cart_items as $item) {
                if (is_null(Product::status()->find($item['product_id']))) {
                    throw new Exception(__('api.not_found'));
                }

                $product = Product::find($item['product_id']);
                $product->decrement('quantity', $item['quantity']);

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $this->getProductPrice($item['product_id']);
                $orderItem->total = $this->getProductPrice($item['product_id']) * $item['quantity'];
                $orderItem->save();
            }

            if (auth('api')->check()) {
                UserCard::where('user_id', $user->id)->delete();
                $user = auth('api')->user();

                $this->notificationService->sendNotification(
                    $user,
                    'new_order',
                    [
                        'message' => __("api.Yeni sifariş yaradıldı") . ' ' . $order->order_number,
                        'additional_info' => view("backend.pages.render.order_notifications", compact('order'))->render()
                    ]
                );
            }


            \DB::commit();

            // Prepare response
            $responseData = [
                'status' => 'success',
                'message' => __('api.Order placed successfully'),
                'order' => [
                    'id' => $order->id,
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'delivery_type' => $order->delivery_type,
                    'total_amount' => $order->total_amount + $this->orderPrice,
                    'items' => $order->items,
                ]
            ];

            // Add delivery-specific details to response
            if ($order->delivery_type == 'home_delivery') {
                $responseData['order']['delivery_address'] = [
                    'city' => $deliveryAddress->city->title,
                    'address' => $deliveryAddress->address,
                    'additional_info' => $deliveryAddress->additional_info,
                ];
            } else {
                $responseData['order']['store'] = [
                    'id' => $store->id,
                    'address' => $store->address,
                ];
            }

            return response()->json($responseData, 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancelOrder(Request $request)
    {


        $this->validate($request, [
            'order_id' => 'required|exists:orders,id',
            // 'reason_id' => 'required|exists:order_cancellation_reasons,id',
            'reason' => 'sometimes|max:5000',
        ], [
            'order_id.required' => __('api.order_id.required'),
            'order_id.exists' => __('api.order_id.exists'),
            'reason.max' => __('api.reason.max'),
        ]);




        try {
            $order = Order::find($request->order_id);

            if ($order->status->status >= OrderStatusEnum::PREPARE->value) {
                throw new Exception(__('api.order_cannot_be_cancelled_preparing'));
            }

            if ($order->status->status == OrderStatusEnum::CANCELED->value) {
                throw new Exception(__('api.order_already_cancelled'));
            }


            foreach ($order->items as $item) {
                $product = $item->product;
                $product->increment('quantity', $item->quantity);
            }



            $order->status()->delete();
            $order->status()->create([
                "status" => OrderStatusEnum::CANCELED->value,
                "reason_id" => $request->reason_id,
                "reason" => $request->reason
            ]);



            $user = $order->user;

            $this->notificationService->sendNotification(
                $user,
                'cancel_order',
                [
                    'message' => __("api.Sifarişiniz ləğv olundu") . ' ' . $order->order_number,
                    'additional_info' => view("backend.pages.render.order_notifications", compact('order'))->render()
                ]
            );




            return $this->responseMessage('success', __('api.Your order has been successfully canceled'), null, 200);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400);
        }

    }
}
