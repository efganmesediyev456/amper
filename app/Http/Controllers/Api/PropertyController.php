<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OurOnMapResource;
use App\Http\Resources\VacancyCollection;
use App\Models\OurOnMap;
use App\Models\Property;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Resources\PropertyResource;
use Illuminate\Support\Facades\Cache;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
    
        $properties = Cache::remember("properties", 3600, function () {
            return Property::where('status', 1)
                ->orderBy('order', 'asc')
                ->get();
        });
        
        return PropertyResource::collection($properties);
    }
}