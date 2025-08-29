<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\LanguagesDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;

class LanguageController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Language::class;
    }

    public function index(LanguagesDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.languages.index');
    }

    public function create()
    {
        return view('backend.pages.languages.create');
    }


    public function store(Request $request){
        $this->validate($request, [
            "title"=>"required",
            "code"=>"required|unique:languages,code",
        ]);
        try {
           DB::beginTransaction();
           $this->mainService->create($request->except('_token'));
           DB::commit();
           return $this->responseMessage('success', 'Uğurla yaradıldı',[], 200,route('admin.languages.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function edit(Language $item)
    {
        return view('backend.pages.languages.edit',compact('item'));
    }

    public function update(Request $request,Language $item){
        $this->validate($request, [
            "title"=>"required",
            "code"=>"required|unique:languages,code,".$item->id,
        ]);
        try {
            DB::beginTransaction();
            $this->mainService->update($item->id,$request->except('_token'));
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.languages.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }
}
