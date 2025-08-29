<?php

namespace App\Services;

use App\Models\FieldTranslation;
use App\Models\Language;
use App\Models\RoadPass;
use App\Notifications\RoadPasses\RoadPassExpiredNotification;
use Flasher\Prime\Notification\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;


class MainService
{
    public $model;

    public function getAll()
    {
        return $this->model::orderBy('created_at', 'desc')->get();
    }

    public function save($item, array $data)
    {
        foreach ($data as $key => $value) {
            if (!is_array($value))
                $item->$key = $value;
        }

        $item->save();
        return $item->fresh();
    }

    public function getById(int $id)
    {
        return $this->model::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $model = $this->getById($id);
        $model->update($data);
        return $model->fresh();
    }

    public function delete(int $id)
    {
        $this->model::destroy($id);
    }


   
    public function createTranslations($item, Request $request)
    {
        foreach (Language::all() as $lang) {
            foreach ($item->translatedAttributes as $key => $transAttribute) {
                $value = @$request?->$transAttribute[$lang->code];
                
                if ($transAttribute == 'slug') {
                    if ($value == '' or $value == null) {
                        $value = @$request?->title[$lang->code] ?? @$request?->vacancy_title[$lang->code];
                    }
                    $value = Str::slug($value);

                    // İndi slugun unikal olmasını təmin edən kod
                    $originalSlug = $value;
                    $counter = 1;

                    // Slug artıq varsa, sonuna -1, -2, ... əlavə et
                    while (
                        FieldTranslation::where('locale', $lang->code)
                            ->where('model_type', get_class($item))
                            ->where('key', 'slug')
                            ->whereNot('model_id', $item->id)
                            ->whereNotNull('value')
                            ->where('value', '!=', '')
                            ->where('value', $value)
                            ->exists()
                    ) {
                        $value = $originalSlug . '-' . $counter;
                        $counter++;
                    }
                }

                $item->translations()->updateOrCreate(
                    ['locale' => $lang->code, 'key' => $transAttribute],
                    ['value' => $value]
                );
            }
        }
    }

}
