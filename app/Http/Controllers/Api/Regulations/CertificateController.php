<?php

namespace App\Http\Controllers\Api\Regulations;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Regulations\CertificateResource;
use App\Http\Resources\Regulations\TeamResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Certificate;
use App\Models\Language;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CertificateController extends Controller
{
    public function index()
    {
        
        $certificates = Cache::remember("certificates", 3600, function () {
            return Certificate::status()
                ->orderBy('order')
                ->get();
        });
        
        return CertificateResource::collection($certificates);
    }
}
