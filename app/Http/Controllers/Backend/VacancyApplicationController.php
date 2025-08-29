<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VacancyApplicationsDataTable;
use App\Http\Controllers\Controller;
use App\Models\VacancyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;

class VacancyApplicationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = VacancyApplication::class;
    }

    public function index(VacancyApplicationsDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.vacancy_applications.index');
    }


    public function delete($id){
        $vacancy = VacancyApplication::find($id);
        $vacancy->delete();
        return redirect()->back()->withSuccess("Uğurla silindi!");
    }
}
