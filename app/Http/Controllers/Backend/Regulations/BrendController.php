<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\BrendsDataTable;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Brend;

class BrendController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Brend::class;
    }

    public function index(BrendsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.brends.index');
    }

    public function create(){
        $subcategories=SubCategory::all();
        return view('backend.pages.brends.create',compact('subcategories'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
            'image'=>"required",
        ]);
        try {
            $item = new Brend();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), 'brends');
            }
            $data['order']=Brend::max('order') + 1;
            $item = $this->mainService->save($item, $data);
             if ($request->has('subcategory_id')) {
                $item->subcategories()->sync($request->subcategory_id);
            }
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.brends.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Brend $item){
        $subcategories=SubCategory::all();
        return view('backend.pages.brends.edit', compact('item','subcategories'));
    }

    public function update(Request $request, Brend $item){
        $this->validate($request,[
            'title.*'=>"required",
            // "subcategory_id"=>"required"
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($item->image) {
                    FileUploadHelper::deleteFile($item->image);
                }
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), 'brends');
            }

            if ($request->has('subcategory_id')) {
                $item->subcategories()->sync($request->subcategory_id);
            }else{
                 $item->subcategories()->sync([]);
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.brends.index', ['subcategory_id'=>$item->subcategory_id]));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Brend $item){
        try {
            DB::beginTransaction();
            
            // Delete image if exists
            if ($item->image) {
                FileUploadHelper::deleteFile($item->image);
            }
            
            $item->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}