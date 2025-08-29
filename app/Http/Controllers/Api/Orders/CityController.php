<?php
namespace App\Http\Controllers\Api\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\CancelOrderResource;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\OrderCancellationReason;

class CityController extends Controller {
    public function index(){
        $cities = City::orderBy('order','asc')->status()->get();
        return CityResource::collection($cities);
    }
}
