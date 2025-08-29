<?php
namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\SubCategoriesDataTable;
use App\DataTables\TopicsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Topic;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\TopicSaveRequest;

class SubCategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Subcategory::class;
    }

    public function index(Category $category, SubCategoriesDataTable $dataTable)
    {
        return $dataTable->with('category', $category)->render('backend.pages.subcategories.index', compact('category'));
    }

    public function create(Category $category)
    {
        return view('backend.pages.subcategories.create', compact('category'));
    }

    public function store(Request $request, Category $category)
    {
        $this->validate($request,[
            'title.*'=>'required'
        ]);
        try {
            $item = new SubCategory();
            //salam
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $data['category_id'] = $category->id;
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), "subcategories", 'subcategories_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.categories.subcategories.index', $category->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Category $category, SubCategory $item)
    {
        return view('backend.pages.subcategories.edit', compact('category', 'item'));
    }

    public function update(Request $request, Category $category, SubCategory $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), "subcategories", 'subcategories_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.categories.subcategories.index', $category->id));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Category $category, SubCategory $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}