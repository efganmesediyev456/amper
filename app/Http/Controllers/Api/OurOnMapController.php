<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OurOnMapResource;
use App\Http\Resources\VacancyCollection;
use App\Models\OurOnMap;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class OurOnMapController extends Controller
{
    public function index(Request $request)
    {
        $ouronmap = OurOnMap::first();
        
        return new OurOnMapResource($ouronmap);
    }
}