<?php

namespace App\Http\Controllers\Api\Regulations;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Regulations\TeamResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Language;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TeamController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();

        $teams = Cache::remember("teams", 3600, function () {
            return Team::status()
                ->orderBy('order')
                ->get();
        });

        return TeamResource::collection($teams);
    }
}
