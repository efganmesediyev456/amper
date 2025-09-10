<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralController extends Controller
{

    // public function updateOrder(Request $request)
    // {
    //     $model = $request->model;
    //     $model = app($model);

    //     foreach ($request->items as $index => $order) {
    //         $model::where('id', $order['id'])->update(['order' => $index + 1]);
    //     }

    //     return response()->json(['message' => 'Sıralama uğurla yeniləndi!']);
    // }

  
    public function updateOrder(Request $request)
    {
        
        $model = app($request->model);

        foreach ($request->items as $order) {
            $model::where('id', $order['id'])->update([
                'order' => $order['position']
            ]);
        }

        return response()->json([
            'message' => 'Sıralama uğurla yeniləndi!'
        ]);
    }



    public function updateStatus(Request $request)
    {
        try {
            $model = app($request->model);
            $model = $model::findOrFail($request->id);
            $model->status = $request->status;
            $model->save();

            return response()->json([
                'success' => true,
                'message' => 'Status uğurla yeniləndi!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xəta baş verdi: ' . $e->getMessage()
            ], 500);
        }
    }


}