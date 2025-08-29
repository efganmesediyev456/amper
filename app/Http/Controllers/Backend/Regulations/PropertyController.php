<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\PropertiesDataTable;
use App\DataTables\PropertiesNewDataTable;
use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Property::class;
    }

    public function index(PropertiesNewDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.properties.index');
    }

    public function create(){
        return view('backend.pages.properties.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            $item = new Property();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı',[], 200,route('admin.properties.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }

    public function edit(Property $item){
        return view('backend.pages.properties.edit', compact('item'));
    }

    public function update(Request $request,Property $item){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.properties.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }

    public function delete(Property $item){
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
