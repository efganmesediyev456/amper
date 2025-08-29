<?php

namespace App\Http\Controllers\Backend\Vacancy;

use App\Http\Controllers\Controller;
use App\Models\VacancyBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;

class VacancyBannerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = VacancyBanner::class;
    }

    public function index()
    {
        $item = VacancyBanner::first();
        return view('backend.pages.vacancy-banner.index', compact('item'));
    }

    public function update(Request $request, VacancyBanner $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($item->image) {
                    FileUploadHelper::deleteFile($item->image);
                }
                $data['image'] = FileUploadHelper::uploadFile($request->file('image'), "vacancy-banner", 'vacancy-banner');
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item, $request);
            
            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.vacancy-banner.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}