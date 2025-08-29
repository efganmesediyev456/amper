<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\VacancyShareSocialsDataTable;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VacancyShareSocial;

class VacancyShareSocialController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = VacancyShareSocial::class;
    }

    public function index(VacancyShareSocialsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.vacancy-share-socials.index');
    }

    public function create(){
        return view('backend.pages.vacancy-share-socials.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            $item = new VacancyShareSocial();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), 'vacancy-share-socials');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.vacancy-share-socials.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(VacancyShareSocial $item){
        return view('backend.pages.vacancy-share-socials.edit', compact('item'));
    }

    public function update(Request $request, VacancyShareSocial $item){
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
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), 'vacancy-share-socials');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.vacancy-share-socials.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(VacancyShareSocial $item){
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