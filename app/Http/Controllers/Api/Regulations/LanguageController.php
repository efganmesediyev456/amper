<?php

namespace App\Http\Controllers\Api\Regulations;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index(){
        return LanguageResource::collection(Language::orderBy('order','asc')->get());
    }
}
