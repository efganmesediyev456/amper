<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrdersDataTable;
use App\DataTables\OrderItemsDataTable;
use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Order::class;
    }

    public function index(OrdersDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.orders.index');
    }

    public function show(Order $order, OrderItemsDataTable $dataTable)
    {
        return $dataTable
            ->with('order', $order)
            ->render('backend.pages.orders.show', compact('order'));
    }


    public function updateStatus(Request $request, $order)
    {
        $order = Order::where('order_number', $order)->first();
        $request->validate([
            'status' => 'required|integer|in:' . implode(',', array_column(OrderStatusEnum::cases(), 'value'))
        ]);

        try {
            DB::beginTransaction();

            if ($request->status == OrderStatusEnum::CANCELED->value) {
                $order->status?->delete();
                $orderStatus = new OrderStatus();
                $orderStatus->order_id = $order->id;
                $orderStatus->status = $request->status;
                $orderStatus->save();
            } else {
                for ($i = 1; $i <= 5; $i++) {
                    $order = $order->fresh();

                    $existingStatus = $order->statusesHistories()->where('status', $i)->withTrashed()->first();

                    if ($existingStatus) {
                        $existingStatus->created_at = now();
                        $existingStatus->deleted_at = null;
                        $existingStatus->save();
                    } else {
                        $orderStatus = new OrderStatus();
                        $orderStatus->order_id = $order->id;
                        $orderStatus->status = $i;
                        $orderStatus->save();
                    }


                }

                foreach ($order->statusesHistories()->withTrashed()->get() as $status) {
                    if ($status->status == $request->status) {
                        $status->deleted_at = null;
                    } else {
                        $status->delete();
                    }
                }
            }



            $user = $order->user;
            // dd(view("backend.pages.render.order_notifications", compact('order'))->render());
            if ($user) {
                $this->notificationService->sendNotification(
                    $user,
                    'order_status_change_by_user',
                    [
                        'message' => __("api.Sizin sifarişinizin statusu yeniləndi") . ' ' . $order->order_number,
                        'additional_info' => view("backend.pages.render.order_notifications", compact('order'))->render()
                    ]
                );
            }



            DB::commit();
            $statusEnum = OrderStatusEnum::from($request->status);
            $statusName = $statusEnum->toString();
            return redirect()->back()->with('success', "Sifariş statusu \"$statusName\" olaraq yeniləndi.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Status yeniləmə əməliyyatı zamanı xəta: ' . $e->getMessage());
        }
    }

}

