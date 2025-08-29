<?php

namespace App\Http\Controllers\Api\About;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Users\UserResource;
use App\Models\About;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    public function index()
    {
        
        $about = Cache::remember("about", 3600, function () {
            return About::first();
        });
        
        return new AboutResource($about);
    }
}
