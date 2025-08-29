<?php

namespace App\Http\Controllers\Backend\BlogNew;

use App\DataTables\BlogNewsDataTable;
use App\DataTables\CertificatesDataTable;
use App\DataTables\LanguagesDataTable;
use App\DataTables\ProductsDataTable;
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
use App\Http\Requests\Backend\BlogNew\BlogNewSaveRequest;
use App\Http\Requests\Backend\Products\ProductSaveRequest;
use App\Models\BlogNew;
use App\Models\Category;
use App\Models\Product;
use App\Models\Team;

class BlogNewController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = BlogNew::class;
    }

    public function index(BlogNewsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.blognews.index');
    }

    public function create()
    {
        return view('backend.pages.blognews.create');
    }

    public function store(BlogNewSaveRequest $request)
    {
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            $item = new BlogNew();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "blognews", 'blognews_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.blognews.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function edit(BlogNew $item)
    {
        return view('backend.pages.blognews.edit', compact('item'));
    }

    public function update(BlogNewSaveRequest $request, BlogNew $item)
    {
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "blognews", 'blognews_' . uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.blognews.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }


    public function delete(BlogNew $item)
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
