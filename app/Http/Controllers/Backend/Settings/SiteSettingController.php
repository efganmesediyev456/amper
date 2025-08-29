<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = SiteSetting::class;
    }

    public function index()
    {
        $item = SiteSetting::first();
        if(is_null($item)){
            $item = SiteSetting::create([

            ]);
        }
        $languages = Language::all();
        return view('backend.pages.settings.index', compact('item', 'languages'));
    }

    public function update(Request $request, SiteSetting $item)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token', '_method');
            
            if ($request->hasFile('header_logo')) {
                $data['header_logo'] = FileUploadHelper::uploadFile($request->file('header_logo'), "settings", 'header_logo', null, false);
            }
            
            if ($request->hasFile('footer_logo')) {
                $data['footer_logo'] = FileUploadHelper::uploadFile($request->file('footer_logo'), "settings", 'footer_logo', null, false);
            }
            
            if ($request->hasFile('favicon')) {
                $data['favicon'] = FileUploadHelper::uploadFile($request->file('favicon'), "settings", 'favicon', null, false);
            }
            
            $item = $this->mainService->save($item, $data);
            $this->mainService->createTranslations($item,$request);

            DB::commit();
            return $this->responseMessage('success', 'Uğurla dəyişdirildi', [], 200, route('admin.settings.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->responseMessage('error', $exception->getMessage(), [], 500);
        }
    }
}