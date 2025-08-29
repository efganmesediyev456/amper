<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\VacancyReceipentsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\VacancyReceipent;

class VacancyReceipentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = VacancyReceipent::class;
    }

    public function index(VacancyReceipentsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.vacancy-receipents.index');
    }

    public function create(){
        return view('backend.pages.vacancy-receipents.create');
    }

    public function store(Request $request){
        try {
            $item = new VacancyReceipent();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.vacancy-receipents.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(VacancyReceipent $item){
        return view('backend.pages.vacancy-receipents.edit', compact('item'));
    }

    public function update(Request $request, VacancyReceipent $item){
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.vacancy-receipents.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(VacancyReceipent $item){
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