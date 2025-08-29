<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\BrandsDataTable;
use App\DataTables\CertificatesDataTable;
use App\DataTables\LanguagesDataTable;
use App\DataTables\TeamsDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Certificate;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;
use App\Helpers\FileUploadHelper;
use App\Models\Brand;
use App\Models\Team;

class BrandController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Brand::class;
    }

    public function index(BrandsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.brands.index');
    }

    public function create(){
        return view('backend.pages.brands.create');
    }

    public function store(Request $request){
        try {
            $item = new Brand();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı',[], 200,route('admin.brands.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function edit(Brand $item){
        return view('backend.pages.brands.edit', compact('item'));
    }

    public function update(Request $request,Brand $item){
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.brands.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function delete (Brand $item){
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
}
