<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CancelOrderResource;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Models\OrderCancellationReason;
use App\Models\SiteSetting;
use App\Http\Resources\SiteSettingResource;

class SiteSettingController extends Controller {
    public function index(){
        $settings = SiteSetting::first();
        return new SiteSettingResource($settings);
    }
}
