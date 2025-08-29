<?php

namespace App\Http\Controllers\Api\Regulations;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Regulations\TranslationResource;
use App\Http\Resources\Users\UserResource;
use App\Models\LangTranslation;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index(Request $request){
        $query = LangTranslation::query();

        if ($request->hasHeader('X-Accept-Language')) {
            $languageCode = $request->header('X-Accept-Language');
            $query->where('locale', $languageCode);
        }

        return response()->json($query->get()->mapWithKeys(function ($item) {
            return [$item->key => $item->value];
        }));
    }
}
