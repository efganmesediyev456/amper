<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\BlogAndNewsResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Users\UserResource;
use App\Models\About;
use App\Models\BlogNew;
use App\Models\Language;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class BlogAndNewsController extends Controller
{
    public function index(){
        return BlogAndNewsResource::collection(BlogNew::status()->orderBy('id','desc')->paginate(12));
    }

    public function getAllBlogs(){
        return BlogAndNewsResource::collection(BlogNew::get());
    }

    public function single(Request $request, $slug){
       
            $blog = BlogNew::status()->whereHas('translations', function($query)use($slug){
                return $query->where('value', $slug)->where('locale',app()->getLocale())->where('key','slug');
            })->first();
            if(is_null($blog)){
                return $this->responseMessage("error",'No found blog',null, 400);
            }
            return new BlogAndNewsResource($blog);
       
    }


    public function others(Request $request){
        $blogs = BlogNew::where('id','!=',$request->blog_id)->get()->take(5);
        return BlogAndNewsResource::collection($blogs);

    }


}
