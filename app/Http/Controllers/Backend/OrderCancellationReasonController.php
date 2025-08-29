<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\OrderCancellationsDataTable;
use App\Http\Controllers\Controller;
use App\Models\OrderCancellationReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class OrderCancellationReasonController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = OrderCancellationReason::class;
    }

    public function index(OrderCancellationsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.order_cancellation_reasons.index');
    }

    public function create(){
        return view('backend.pages.order_cancellation_reasons.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            $item = new OrderCancellationReason();
            DB::beginTransaction();
            $data = $request->except('_token','_method');

            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.order_cancellation_reasons.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(OrderCancellationReason $item){
        $orders = Order::all();
        return view('backend.pages.order_cancellation_reasons.edit', compact('item', 'orders'));
    }

    public function update(Request $request, OrderCancellationReason $item){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');

            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.order_cancellation_reasons.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(OrderCancellationReason $item){
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
