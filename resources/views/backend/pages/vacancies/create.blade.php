@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Vakansiyalar</h4>
                <div class="buttons">
                    <a href="{{ route('admin.vacancies.index') }}" class="btn btn-success">Geriyə qayıt</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('admin.vacancies.store')}}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf

                <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                    @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif"
                            id="{{$language->code}}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{$language->code}}"
                            type="button" role="tab"
                            aria-controls="{{$language->code}}"
                            aria-selected="true">
                            {{$language->title}}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="languageTabsContent">
                    @foreach($languages as $language)
                    <div class="tab-pane fade show @if($loop->first) active @endif"
                        id="{{$language->code}}" role="tabpanel"
                        aria-labelledby="{{$language->code}}-tab">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vacancy_title_{{$language->code}}" class="form-label">Vakansiya adı ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="vacancy_title[{{$language->code}}]"
                                        id="vacancy_title_{{$language->code}}"
                                        placeholder="Vakansiya adı daxil edin">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vacancy_location_{{$language->code}}" class="form-label">Ünvan ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="vacancy_location[{{$language->code}}]"
                                        id="vacancy_location_{{$language->code}}"
                                        placeholder="Ünvan daxil edin">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description_{{$language->code}}" class="form-label">Haqqinda ({{$language->code}})</label>
                                    <textarea class="form-control ckeditor"
                                        name="description[{{$language->code}}]"
                                        id="description_{{$language->code}}"
                                        rows="5"
                                        placeholder="Təsvir daxil edin"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="slug_{{$language->code}}" class="form-label">Slug ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="slug[{{$language->code}}]"
                                        id="slug_{{$language->code}}"
                                        placeholder="Slug daxil edin">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="seo_keywords_{{$language->code}}" class="form-label">SEO açar sözlər ({{$language->code}})</label>
                                    <input type="text" class="form-control tagsview"
                                        name="seo_keywords[{{$language->code}}]"
                                        id="seo_keywords_{{$language->code}}"
                                        placeholder="SEO açar sözləri daxil edin">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="seo_description_{{$language->code}}" class="form-label">SEO Haqqinda ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="seo_description[{{$language->code}}]"
                                        id="seo_description_{{$language->code}}"
                                        placeholder="SEO təsvir daxil edin">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Başlama tarixi</label>
                            <input type="date" name="vacany_start_at" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Bitmə tarixi</label>
                            <input type="date" name="vacany_expired_at" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image">Şəkil</label>
                        <input type="file" name="image" class="form-control" id="image">
                        <small class="text-muted d-block my-2">Tövsiyə olunan ölçü: 536x500 piksel</small>

                    </div>

                </div>

            

                <div class="row mt-2">
                    <div class="d-flex justify-content-end">
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-success">
                                <i class="fas fa-save"></i>
                                <span>Yadda saxla</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
