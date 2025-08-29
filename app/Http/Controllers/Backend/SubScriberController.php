<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\VacancyApplicationsDataTable;
use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Models\VacancyApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\DataTables\SubScribeDatatable;

class SubScriberController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = Subscribe::class;
    }


    public function index(SubScribeDatatable $dataTable)
    {
        return $dataTable->render('backend.pages.subscribers.index');
    }


    public function delete($id){
        $subscriber = Subscribe::find($id);
        $subscriber->delete();
        return redirect()->back()->withSuccess("Uğurla silindi!");
    }
}
