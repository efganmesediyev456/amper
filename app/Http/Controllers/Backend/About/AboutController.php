<?php

namespace App\Http\Controllers\Backend\About;

use App\DataTables\LanguagesDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;
use App\Helpers\FileUploadHelper;


class AboutController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = About::class;
    }

    public function index()
    {
        $item = About::first();
        return view('backend.pages.about.index', compact('item'));
    }

    public function update(Request $request,About $item){
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "about", 'about');
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.about.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }
}
