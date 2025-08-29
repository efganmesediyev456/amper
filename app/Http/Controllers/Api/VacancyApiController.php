<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VacancyBannerResource;
use App\Http\Resources\VacancyCollection;
use App\Http\Resources\VacancyReceipentResource;
use App\Http\Resources\VacancyResource;
use App\Http\Resources\VacancyShareLinkResource;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use App\Models\VacancyBanner;
use App\Models\VacancyReceipent;
use App\Models\VacancyShareSocial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VacancyApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Vacancy::status();

        $today = now()->format('Y-m-d');
        $query->where('vacany_start_at', '<=', $today)
                  ->where('vacany_expired_at', '>=', $today);
        
        $vacancies = $query->orderBy('id','desc')->get();

        return VacancyResource::collection($vacancies);
    }


    public function apply(Request $request)
    {
        $this->validate($request,[
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'vacancy_id' => 'required|integer|exists:vacancies,id',
        ]);

        $vacancy= Vacancy::status()->where('id', $request->vacancy_id)->first();
        if(is_null($vacancy)){
           return $this->responseMessage('error','Vakansiya tap覺lmad覺', null, 200, null);   
        }

        $cvPath = null;
        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            $file = $request->file('cv');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $cvPath = $file->storeAs('job_applications/cvs', $fileName, 'public');
        }

        $jobApplication = VacancyApplication::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cv_path' => $cvPath,
            'vacancy_id' => $request->vacancy_id,
        ]);
        $jobApplication->cv_path=url('storage/'.$jobApplication->cv_path);
        return $this->responseMessage('success','Vacancy application submitted successfully', $jobApplication, 200 );
    }


    public function show($id)
    {
        $vacancy = Vacancy::where('status', true)->findOrFail($id);
        $vacancy->incrementViews();

        return new VacancyResource($vacancy);
    }

    public function single(Request $request, $slug){
        $vacancy = Vacancy::status()->whereHas('translations', function($query)use($slug){
            return $query->where('value', $slug)->where('locale',app()->getLocale())->where('key','slug');
        })->first();
        if(is_null($vacancy)){
            return $this->responseMessage("error",'No found vacancy',null, 400);
        }
        $vacancy->incrementViews();
        return new VacancyResource($vacancy);
    }


    public function vacancyReceipents(){
        $vacancyReceipents = VacancyReceipent::get();
        return VacancyReceipentResource::collection($vacancyReceipents);
    }

    public function vacancyShareLinks(){
        return VacancyShareLinkResource::collection(VacancyShareSocial::status()->orderBy('order','asc')->get());
    }


    public function banner()
    {
        $banner = VacancyBanner::first();
        
        if (!$banner) {
            return $this->responseMessage("error",'No found vacancy',null, 400);
        }
        
        return new VacancyBannerResource($banner);
    }
}
