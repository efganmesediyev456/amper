<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\SocialLinksDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Models\SocialLink;

class SocialLinkController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = SocialLink::class;
    }

    public function index(SocialLinksDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.social_links.index');
    }

    public function create(){
        return view('backend.pages.social_links.create');
    }

    public function store(Request $request){
        try {
            $this->validate($request,[
                'title.*'=>"required",
            ]);
            $item = new SocialLink();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), 'social_links');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.social_links.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(SocialLink $item){
        return view('backend.pages.social_links.edit', compact('item'));
    }

    public function update(Request $request, SocialLink $item){
        try {
            $this->validate($request,[
                'title.*'=>"required",
            ]);
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($item->image) {
                    FileUploadHelper::deleteFile($item->image);
                }
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), 'social_links');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.social_links.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(SocialLink $item){
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