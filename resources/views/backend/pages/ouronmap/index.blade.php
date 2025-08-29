@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Biz xəritədə</h4>

            </div>
        </div>
        <div class="card-body">
            <form action="{{route('admin.ouronmap.update', $item->id)}}" method="POST" id="saveForm" enctype="multipart/form-data">
                @method('PUT')

                <ul class="nav nav-tabs" id="languageTabs" role="tablist">

                    @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->iteration==1) active @endif" id="{{$language->code}}-tab" data-bs-toggle="tab" data-bs-target="#{{$language->code}}" type="button"
                            role="tab" aria-controls="{{$language->code}}" aria-selected="true">{{$language->title}}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content mt-3" id="languageTabsContent">
                    @foreach($languages as $language)
                    <div class="tab-pane fade show @if($loop->iteration==1) active @endif" id="{{$language->code}}"
                        role="tabpanel" aria-labelledby="{{$language->code}}-tab">

                        <div class="mb-3">
                            <label for="title" class="form-label">Başlıq {{$language->code}}</label>
                            <input type="text" class="form-control" name="title[{{$language->code}}]"
                                id="name[{{ $language->code }}]"
                                placeholder="Ad daxil edin"
                                value="{{$item->getTranslation('title', $language->code, true)}}">
                        </div>


                        <div class="mb-3">
                            <label for="title" class="form-label">Ünvan {{$language->code}}</label>
                            <input type="text" class="form-control" name="address[{{$language->code}}]"
                                id="address[{{ $language->code }}]"
                                placeholder="Daxil edin"
                                value="{{$item->getTranslation('address', $language->code, true)}}">
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">İş saatları {{$language->code}}</label>
                            <input type="text" class="form-control" name="work_hours[{{$language->code}}]"
                                id="work_hours[{{ $language->code }}]"
                                placeholder="Daxil edin"
                                value="{{$item->getTranslation('work_hours', $language->code, true)}}">
                        </div>

                        <div class="mb-3">
                            <label for="full_name_az" class="form-label">Seo Haqqında {{$language->code}}</label>
                            <textarea class="form-control" name="seo_description[{{$language->code}}]"
                                id="seo_description[{{ $language->code }}]"
                                placeholder="Haqqında daxil edin">{{$item->getTranslation('seo_description', $language->code, true)}}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="seo_keywords" class="form-label">Açar sözlər {{$language->code}}</label>
                            <input type="text" class="form-control tagsview" name="seo_keywords[{{$language->code}}]"
                                id="seo_keywords[{{ $language->code }}]"
                                placeholder="Açar sözlər daxil edin"
                                value="{{$item->getTranslation('seo_keywords', $language->code, true)}}">
                        </div>


                    </div>
                    @endforeach
                </div>


                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group ">
                            <label for="">Şəkil</label>
                            <input type="file" name="image" class="form-control">
                            <small class="text-muted d-block my-2">Tövsiyə olunan ölçü: 861x400 piksel</small>

                            <img width="300" src="{{'/storage/'.$item->image}}" alt="" class="my-3">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="email" name="email" class="form-control" value="{{$item->email}}">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Xəritə</label>
                            <input type="text" name="map" class="form-control" value="{{$item->map}}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Telefon</label>
                            <input type="text" name="mobile" class="form-control" value="{{$item->mobile}}">
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