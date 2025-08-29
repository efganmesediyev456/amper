<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\SubPropertiesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\SubProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubPropertyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = SubProperty::class;
    }

    public function index(SubPropertiesDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.sub-properties.index');
    }

    public function create()
    {
        $properties = Property::all();
        return view('backend.pages.sub-properties.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title.*'=>"required",
            "property_id"=>"required"
        ]);
        try {
            $item = new SubProperty();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.sub-properties.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(SubProperty $item)
    {
        $properties = Property::all();
        return view('backend.pages.sub-properties.edit', compact('item', 'properties'));
    }

    public function update(Request $request, SubProperty $item)
    {
        $this->validate($request,[
            'title.*'=>"required",
            "property_id"=>"required"
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.sub-properties.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(SubProperty $item)
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
