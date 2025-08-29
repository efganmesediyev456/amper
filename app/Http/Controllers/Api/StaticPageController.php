<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OurOnMapResource;
use App\Http\Resources\StaticPageResource;
use App\Http\Resources\VacancyCollection;
use App\Models\ComplaintManagement;
use App\Models\DeliveryPayment;
use App\Models\OurOnMap;
use App\Models\Rating;
use App\Models\ReturnPolicy;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function index(Request $request, $slug)
    {
        $item = null;
        switch ($slug) {
            case "delivery-and-payment":
                $item = DeliveryPayment::first();
                break;
            case "complain-management":
                $item = ComplaintManagement::first();
                break;
            case "return-policy":
                $item = ReturnPolicy::first();
                break;
            case "rating":
                    $item = Rating::first();
                    break;
            default:
                $item = null;
                break;
        }
        if (is_null($item)) {
            return $this->responseMessage('error', 'Not Found', null, 404);
        }

        return new StaticPageResource($item);
    }
}
