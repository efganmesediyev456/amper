<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\CitiesDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\City;

class CityController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = City::class;
    }

    public function index(CitiesDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.cities.index');
    }

    public function create(){
        return view('backend.pages.cities.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            $item = new City();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.cities.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(City $item){
        return view('backend.pages.cities.edit', compact('item'));
    }

    public function update(Request $request, City $item){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.cities.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(City $item){
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