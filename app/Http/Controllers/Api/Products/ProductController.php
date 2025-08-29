<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\BrendResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\SubCategoryResource;
use App\Http\Resources\Users\UserResource;
use App\Models\About;
use App\Models\Brend;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{



    function generateUniqueRandom(&$usedRandoms) {
        do {
            $random = mt_rand(10000, 999999);
        } while (in_array($random, $usedRandoms));
        $usedRandoms[] = $random;
        return $random;
    }



     private function getTranslatedSlugs($item): array
    {
        $languages = Language::pluck('code')->toArray();
        $slugs = [];
        foreach ($languages as $lang) {
            $slugs[$lang] = $item->getTranslation('slug', $lang) ?? null;
        }
        return $slugs;
    }


     public function index(Request $request){

        $lang = app()->getLocale();
        $products = Product::with(['subProperties', 'category', 'orderItems'])->status()->select('products.*');

        if ($request->has('randomOrder') && $request->randomOrder == 1) {
            $products = $products->inRandomOrder();
        }

        if($request->category_id and  !is_array($request->category_id) ){
            $products = $products->where('category_id', $request->category_id);
        }

        if($request->property_id and is_array($request->property_id) and count($request->property_id)){
            $products = $products->whereHas('subProperties', function($query) use($request){
                $query->whereIn('sub_property_id', $request->property_id);
            });
        }

        if($request->brend_id and is_array($request->brend_id) and count($request->brend_id)){
            $products = $products->whereIn('brend_id', $request->brend_id);
        }

        if($request->subcategory_id and is_array($request->subcategory_id) and count($request->subcategory_id)){
            $products = $products->whereIn('subcategory_id', $request->subcategory_id);
        }

        if($request->category_id and is_array($request->category_id) and count($request->category_id)){
            $products = $products->whereHas('category', function($query) use($request){
                $query->whereIn('category_id', $request->category_id);
            });
        }

        if($request->min and $request->min > 0){
            $products = $products->where('price','>=',$request->min);
        }
        if($request->max and $request->max > 0){
            $products = $products->where('price','<=',$request->max);
        }

        if($request->type && $request->type === 'bestselling') {
            $products = $products->withSum(['orderItems' => function($query) {
                    $query->whereHas('order', function($qq){
                        $qq->whereHas('status', function($q){
                           $q->where('status', 4); 
                        });
                    });
                }], 'quantity')
                ->orderBy('order_items_sum_quantity', 'desc');
        } else {
            $sortBy = $request->input('sort_by');
            switch ($sortBy) {
                case 'price_asc':
                    $products=$products->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $products= $products->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $products=$products->join('field_translations', function ($join) use ($lang) {
                        $join->on('products.id', '=', 'field_translations.model_id')
                            ->where('field_translations.locale', '=', $lang)
                            ->where('field_translations.key', '=', 'title')
                            ->where('field_translations.model_type', '=', Product::class);
                    })
                    ->orderBy('field_translations.value', 'asc')
                    ->select('products.*');
                    break;
                case 'name_desc':
                    $products=$products->join('field_translations', function ($join) use ($lang) {
                        $join->on('products.id', '=', 'field_translations.model_id')
                            ->where('field_translations.locale', '=', $lang)
                            ->where('field_translations.key', '=', 'title')
                            ->where('field_translations.model_type', '=', Product::class);
                    })
                    ->orderBy('field_translations.value', 'desc')
                    ->select('products.*');
                    break;
                case 'newest':
                default:
                     $products =  $products->orderBy('products.id','desc');
                    break;
            }
        }
        $productsCount = $products->count();
        $productFilters =  $products->get();
        $subCategoryList = $productFilters->pluck('subcategory_id')->filter()->unique()->values(); 
        $categories = Category::whereHas('subCategories',function($subcategory) use($subCategoryList){
            return $subcategory->whereIn('id',$subCategoryList);
        })->get();
        $allSubProperties = $productFilters->flatMap(function ($product) {
            return $product->subProperties;
        })->unique('id')->pluck('id')->values();
        $properties = $productFilters->pluck('property_id')->filter()->unique()->values(); 
        $properties = Property::whereHas('subProperties',function($subPropery) use($allSubProperties){
            return $subPropery->whereIn('id',$allSubProperties);
        })->get();
        $allSubProperties = $properties->map(function ($property) use (&$usedRandoms) {

            $subList = $property->subProperties?->where('status', 1)?->map(function ($sub) use (&$usedRandoms) {
                return [
                    'id' => $sub->id,
                    'title' => $sub->title,
                ];
            }) ?? [];

            return [
                'id' => $property->id,
                'title' => $property->title,
                'property_list' => $subList,
            ];
        });

  
       


        
       $categories=$categories->map(function($category) use($subCategoryList, $allSubProperties){
            return [
                'id'=> $category->id,
                'title' => $category->title,
                'slug' => $this->getTranslatedSlugs($category),
                'image' => url('storage/'.$category->image),
                'subCategories'=>SubCategoryResource::collection($category->subCategories->whereIn('id',$subCategoryList)),
            ];
        });


        $brands = $productFilters->pluck('brend_id')->filter()->unique()->values();
        $brands=Brend::whereIn('id',$brands)->get();
        $brands=BrendResource::collection($brands);


      

        $products = $products->paginate(12);



        
        if($request->type && $request->type === 'bestselling') {
            $products->each(function($product) {
                $product->total_sales = $product->order_items_sum_quantity;
            });
        }

        return ProductResource::collection($products) ->additional(['products_count' =>$productsCount])->additional([
            'categories'=>$categories,
            'allSubProperties'=>$allSubProperties,
            'brands'=>$brands
        ]);
    }
    public function allProducts(){
        // $products = Product::orderBy('id','desc')->get();
        return ProductResource::collection(Product::orderBy('id','desc')->status()->get());
    }

    public function product($slug){
        try {
            $item = Product::get()->filter(function($q) use($slug){
                return $q->slug == $slug;
            })->first();
            if(!$item){
                return $this->responseMessage('error', __('api.Value not found'),[], 400,null);
            }
            return new ProductResource($item);

        }catch (\Exception $exception){
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }

    public function single(Request $request, $slug){
       
        $product = Product::status()->whereHas('translations', function($query)use($slug){
            return $query->where('value', $slug)->where('locale',app()->getLocale())->where('key','slug');
        })->first();
        if(is_null($product)){
            return $this->responseMessage("error",__('api.Value not found'),null, 400);
        }
        return new ProductResource($product);
   
    }


    public function productSimilary(Request $request, $product_id){
        
        $product = Product::status()->find($product_id);
        if(is_null($product)){
            return $this->responseMessage('error',__('api.Value not found'),null,404, null );
        }
        $products = Product::status()->where('category_id', $product->category_id)->whereNot('id', $product->id)->orderBy("id")->paginate(15);

        return ProductResource::collection($products);
    }



    public function search(Request $request)
    {
        $request->validate( [
            'query' => 'nullable|string|max:255',
        ]);

        $lang = $request->input('lang', default: app()->getLocale());
        
        $query = Product::status()->with(['translations' => function ($query) use ($lang) {
            $query->where('locale', $lang);
        }]);

        if ($request->has('query') && $request->query !== '') {
            $searchTerm = $request->input('query');
            $query->where(function (Builder $q) use ($searchTerm, $lang) {
                $q->orWhereHas('translations', function (Builder $subQuery) use ($searchTerm, $lang) {
                    $subQuery->where('locale', $lang)->where('key', 'title')
                        ->where(function ($translationQuery) use ($searchTerm) {
                            $translationQuery->where('value', 'like', "%{$searchTerm}%");
                        });
                });
            })->orWhere('product_code','like',"%".$searchTerm."%");
        }

        $perPage =  15;
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }


}