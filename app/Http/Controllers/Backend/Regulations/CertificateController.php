<?php

namespace App\Http\Controllers\Backend\Regulations;

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
use App\Models\Team;

class CertificateController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Team::class;
    }

    public function index(CertificatesDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.certificates.index');
    }

    public function create(){
        return view('backend.pages.certificates.create');
    }

    public function store(Request $request){
        try {
            $this->validate($request,[
                'title.*'=>"required",
            ]);
            $item = new Certificate();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            if ($request->hasFile('file')) {
                $data['file'] = FileUploadHelper::uploadFile($request->file('file'), "certificates", 'certificates');
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı',[], 200,route('admin.certificates.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function edit(Certificate $item){
        return view('backend.pages.certificates.edit', compact('item'));
    }

    public function update(Request $request,Certificate $item){
        try {
            $this->validate($request,[
                'title.*'=>"required",
            ]);
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            if ($request->hasFile('file')) {
                $data['file'] = FileUploadHelper::uploadFile($request->file('file'), "certificates", 'certificates');
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.certificates.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function delete (Certificate $item){
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
