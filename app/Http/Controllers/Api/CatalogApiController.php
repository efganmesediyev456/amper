<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CatalogCollection;
use App\Http\Resources\CatalogResource;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CatalogApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $catalogs = Cache::remember('catalogs', 3600, function () {
            return Catalog::status()->orderBy('id','desc')
                ->paginate(15);
        });
    
        return CatalogResource::collection($catalogs);
    }
}
