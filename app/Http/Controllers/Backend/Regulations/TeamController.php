<?php

namespace App\Http\Controllers\Backend\Regulations;

use App\DataTables\LanguagesDataTable;
use App\DataTables\TeamsDataTable;
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
use App\Models\Team;

class TeamController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Team::class;
    }

    public function index(TeamsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.teams.index');
    }

    public function create(){
        return view('backend.pages.teams.create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            $item = new Team();
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "teams", 'teams'.uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı',[], 200,route('admin.teams.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function edit(Team $item){
        return view('backend.pages.teams.edit', compact('item'));
    }

    public function update(Request $request,Team $item){
        $this->validate($request,[
            'title.*'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token','_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "teams", 'teams_'.uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi',[], 200,route('admin.teams.index'));
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }


    public function delete (Team $item){
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
