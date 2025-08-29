<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\BannersDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Models\Banner;

class BannerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Banner::class;
    }

    public function index(BannersDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.banners.index');
    }

    public function create(){
        return view('backend.pages.banners.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
            'image'=>"required",
        ]);
        try {
            $item = new Banner();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), 'banners');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.banners.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Banner $item){
        return view('backend.pages.banners.edit', compact('item'));
    }

    public function update(Request $request, Banner $item){
        $this->validate($request,[
            'title.*'=>"required",
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
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), 'banners');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.banners.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Banner $item){
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