@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Kateqoriyalar</h4>
                <div class="buttons">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-success">Geriyə qayıt</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('admin.blognews.update', $item->id)}}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                    <label for="title_{{$language->code}}" class="form-label">Ad ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="title[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Ad daxil edin" value="{{$item->getTranslation('title', $language->code)}}">
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label for="title_{{$language->code}}" class="form-label">Subtitle ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="subtitle[{{$language->code}}]"
                                        id="subtitle{{$language->code}}"
                                        placeholder="Ad daxil edin" value="{{$item->getTranslation('subtitle', $language->code)}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug {{$language->code}}</label>
                                    <input type="text" class="form-control" name="slug[{{$language->code}}]"
                                        id="name[{{ $language->code }}]"
                                        placeholder="Slug daxil edin"
                                        value="{{$item->getTranslation('slug', $language->code)}}">
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name_az" class="form-label">Seo Haqqında {{$language->code}}</label>
                                    <textarea class="form-control" name="seo_description[{{$language->code}}]"
                                        id="seo_description[{{ $language->code }}]"
                                        placeholder="Haqqında daxil edin">{{$item->getTranslation('seo_description', $language->code)}}</textarea>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="seo_keywords" class="form-label">Açar sözlər {{$language->code}}</label>
                                    <input type="text" class="form-control tagsview" name="seo_keywords[{{$language->code}}]"
                                        id="seo_keywords[{{ $language->code }}]"
                                        placeholder="Açar sözlər daxil edin"
                                        value="{{$item->getTranslation('seo_keywords', $language->code)}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Haqqında {{$language->code}}</label>
                                    <textarea class="form-control ckeditor" name="description[{{$language->code}}]"
                                        id="description[{{ $language->code }}]"
                                        placeholder=""
                                        value="">{{$item->getTranslation('description', $language->code)}}</textarea>
                                </div>
                            </div>

                        </div>


                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Şəkil</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted d-block my-2">Tövsiyə olunan ölçü: 1440x520 piksel</small>
                            <img width="300" src="/storage/{{$item->image}}" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="">Tarix</label>
                            <input type="date" name="date" class="form-control" value="{{$item->date}}">
                        </div>
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
