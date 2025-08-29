<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\LanguagesDataTable;
use App\DataTables\TranslationsDataTable;
use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\LangTranslation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\EloquentDataTable;

class TranslationController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = LangTranslation::class;
    }

    public function index(TranslationsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.translations.index');
    }

    public function create()
    {
        return view('backend.pages.translations.create');
    }


    public function store(Request $request){

        $this->validate($request,[
            'value.*'=>"required",
            'key'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token');

            $item = new LangTranslation();
            // $filename     = $data['filename'];
            $filename     = '';

            $key          = $data['key'];
            $value        = $data['value'];
            foreach($value as $locale=>$val){
                $item    = new LangTranslation();
                $item->value = $val;
                $item->key = $key;
                $item->filename = $filename;
                $item->locale   = $locale;
                $item->save();
            }


            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı',[], 200,route('admin.translations.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function edit(LangTranslation $item)
    {
        return view('backend.pages.translations.edit',compact('item'));
    }

    public function update(Request $request,LangTranslation $item){
        $this->validate($request,[
            'value.*'=>"required",
            'key'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except(['_token']);
            if($item['id']){
                LangTranslation::where('key', $item->key)->where('filename', $item->filename)->delete();
            }
            // $filename     = $data['filename'];
            $filename     = '';

            $key          = $data['key'];
            $value        = $data['value'];
            foreach($value as $locale=>$val){
                $item    = new LangTranslation();
                $item->value = $val;
                $item->key = $key;
                $item->filename = $filename;
                $item->locale   = $locale;
                $item->save();
            }
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.translations.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function delete (LangTranslation $item){
        try {
            DB::beginTransaction();
            LangTranslation::where('key', $item->key)
                ->where('filename', $item->filename)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Uğurla silindi');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }
}
