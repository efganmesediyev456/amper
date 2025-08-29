<?php

namespace App\Http\Controllers\Backend\Catalog;

use App\DataTables\CatalogsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\Backend\Catalog\CatalogSaveRequest;

class CatalogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Catalog::class;
    }

    public function index(CatalogsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.catalogs.index');
    }

    public function create()
    {
        return view('backend.pages.catalogs.create');
    }

    public function store(CatalogSaveRequest $request)
    {
        try {
            $item = new Catalog();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "catalogs", 'catalog_' . uniqid());
            }
            
            if ($request->hasFile('file')) {
                $data['file'] = FileUploadHelper::uploadFile($request->file('file'), "catalogs/files", 'catalog_file_' . uniqid());
            }
            
            $data['status'] = $request->has('status') ? 1 : 0;
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.catalogs.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Catalog $item)
    {
        return view('backend.pages.catalogs.edit', compact('item'));
    }

    public function update(CatalogSaveRequest $request, Catalog $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "catalogs", 'catalog_' . uniqid());
            }
            
            if ($request->hasFile('file')) {
                $data['file'] = FileUploadHelper::uploadFile($request->file('file'), "catalogs/files", 'catalog_file_' . uniqid());
            }
            
            $data['status'] = $request->has('status') ? 1 : 0;
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.catalogs.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Catalog $item)
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