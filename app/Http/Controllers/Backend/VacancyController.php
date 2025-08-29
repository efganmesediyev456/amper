<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VacanciesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\VacancySaveRequest;
use App\Models\Vacancy;
use App\Services\MainService;
use App\Helpers\FileUploadHelper;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller
{

    public function __construct(MainService $mainService)
    {
        parent::__construct();
        $this->mainService->model = Vacancy::class;
    }

    public function index(VacanciesDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.vacancies.index');
    }

    public function create()
    {
        return view('backend.pages.vacancies.create');
    }

    public function store(VacancySaveRequest $request)
    {
        $this->validate($request,[
            'vacancy_title.*'=>"required",
        ]);
        try {
            $item = new Vacancy();
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "vacancies", 'vacancies_'.uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla yaradıldı', [], 200, route('admin.vacancies.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function edit(Vacancy $item)
    {
        return view('backend.pages.vacancies.edit', compact('item'));
    }

    public function update(VacancySaveRequest $request, Vacancy $item)
    {
        $this->validate($request,[
            'vacancy_title.*'=>"required",
        ]);
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            if ($request->hasFile('image')) {
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "vacancies", 'vacancies_'.uniqid());
            }
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.vacancies.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }

    public function delete(Vacancy $item)
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
