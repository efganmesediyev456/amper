<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\MenuCategoryResource;
use App\Http\Resources\Products\CategoryResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Users\UserResource;
use App\Models\About;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\CategoryMobileResource;

class CategoryController extends Controller
{
    public function index()
    {
        
        $categories = Cache::remember("categories", 3600, function () {
            return Category::status()
                ->orderBy('order', 'asc')
                ->get();
        });
        
        return CategoryResource::collection($categories);
    }


    public function mobileCategories(){
        $categories = Cache::remember("categories", 3600, function () {
            return Category::status()
                ->orderBy('order', 'asc')
                ->get();
        });

        return CategoryMobileResource::collection($categories);

    }

    public function category($slug){
        try {
            $item = Category::status()->get()->filter(function($q) use($slug){
                return $q->slug == $slug;
            })->first();
            if(!$item){
                return $this->responseMessage('error', __('api.Value not found'),[], 400,null);
            }
            return new CategoryResource($item);
        }catch (\Exception $exception){
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }

    public function products($slug){

        try {
            $category = Category::status()->get()->filter(function($q) use($slug){
                return $q->slug == $slug;
            })->first();
            if(!$category){
                return $this->responseMessage('error', __('api.Value not found'),[], 400,null);
            }
            $items = Product::status()->where('category_id', $category->id)->paginate(10);
            return ProductResource::collection($items);
        }catch (\Exception $exception){
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }
    public function menuCategories(){
        try {
            $categories = Category::get();
            $items = MenuCategoryResource::collection($categories);
            return $items;
        }catch (\Exception $exception){
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }

    public function getMenu($item){
        try {
            $category = Category::find($item);
            $items = new MenuCategoryResource($category);
            return $items;
        }catch (\Exception $exception){
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }
    
}
