<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\CategoriesDataTable;
use App\DataTables\CertificatesDataTable;
use App\DataTables\LanguagesDataTable;
use App\DataTables\TeamsDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Certificate;
use App\Models\Language;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;
use App\Helpers\FileUploadHelper;
use App\Models\Category;
use App\Models\Team;

class CategoryController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Category::class;
    }

    public function index(CategoriesDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.categories.index');
    }

    public function create(){
        return view('backend.pages.categories.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            $item = new Category();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "categories", 'categories_'.uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı',[], 200,route('admin.categories.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function edit(Category $item){
        return view('backend.pages.categories.edit', compact('item'));
    }

    public function update(Request $request,Category $item){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "categories", 'categories_'.uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.categories.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function delete (Category $item){
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }

    public function getSubCategories(Request $request){
        $value=$request->value;
        $category=Category::find($value);
        $subcategories=$category->subCategories;
        $subcategories = $subcategories->map(function($item){
            return '<option value="'.$item->id.'">'.$item->title.'</option>';
        })->prepend('<option value="">Seçin</option>')->implode('');
        return response()->json([
            'view'=>$subcategories
        ]);

    }


    public function getBrends(Request $request){
        $value=$request->value;
        $subcategory=SubCategory::find($value);
        $brends=$subcategory->brends;
        $brends = $brends->map(function($item){
            return '<option value="'.$item->id.'">'.$item->title.'</option>';
        })->prepend('<option value="">Seçin</option>')->implode('');
        return response()->json([
            'view'=>$brends
        ]);

    }


    
}
