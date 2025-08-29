<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\WeeklySelectionsDataTable;
use App\Http\Controllers\Controller;
use App\Models\WeeklySelection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeeklySelectionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = WeeklySelection::class;
    }

    public function index(WeeklySelectionsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.weekly_selections.index');
    }

    public function create(){
        $products = Product::get();
        return view('backend.pages.weekly_selections.create', compact('products'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            $item = new WeeklySelection();
            DB::beginTransaction();
            $data = $request->except('_token', '_method', 'products');

            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            // Attach selected products
            if ($request->has('products')) {
                $item->products()->sync($request->products);
            }

            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.weekly_selections.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(WeeklySelection $item){
        $products = Product::get();
        $selectedProducts = $item->products->pluck('id')->toArray();
        return view('backend.pages.weekly_selections.edit', compact('item', 'products', 'selectedProducts'));
    }

    public function update(Request $request, WeeklySelection $item){
        try {
            $this->validate($request,[
                'title.*'=>"required",
            ]);
            DB::beginTransaction();
            $data = $request->except('_token', '_method', 'products');

            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            
            // Update selected products
            if ($request->has('products')) {
                $item->products()->sync($request->products);
            } else {
                $item->products()->sync([]);
            }
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.weekly_selections.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(WeeklySelection $item){
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