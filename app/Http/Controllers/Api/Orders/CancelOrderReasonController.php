<?php
namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\CancelOrderResource;
use App\Models\OrderCancellationReason;

class CancelOrderReasonController extends Controller {
    public function index(){
        $reasons = OrderCancellationReason::orderBy('order','asc')->get();
        return CancelOrderResource::collection($reasons);
    }
}
