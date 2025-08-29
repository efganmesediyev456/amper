<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\BannerDetailsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Models\BannerDetail;

class BannerDetailController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = BannerDetail::class;
    }

    public function index(BannerDetailsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.banner_details.index');
    }

    public function create(){
        return view('backend.pages.banner_details.create');
    }

    public function store(Request $request){

        $this->validate($request,[
            'title.*'=>"required",
        ]);

        try {
            $item = new BannerDetail();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            // Handle icon upload
            if ($request->hasFile('icon')) {
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), 'banner_details');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.banner_details.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(BannerDetail $item){
        return view('backend.pages.banner_details.edit', compact('item'));
    }

    public function update(Request $request, BannerDetail $item){
        try {
            $this->validate($request,[
                'title.*'=>"required",
            ]);
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            if ($request->hasFile('icon')) {
                if ($item->icon) {
                    FileUploadHelper::deleteFile($item->icon);
                }
                $data['icon'] = FileUploadHelper::uploadFile($request->file('icon'), 'banner_details');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.banner_details.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(BannerDetail $item){
        try {
            DB::beginTransaction();
            
            if ($item->icon) {
                FileUploadHelper::deleteFile($item->icon);
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