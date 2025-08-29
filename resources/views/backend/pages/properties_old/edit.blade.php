@extends('backend.layouts.layout')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Markalar</h4>
                <div class="buttons">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-success">Geriyə qayıt</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('admin.products.properties_old.update', ['item_id'=>$item->id,'id'=>$item->product_id])}}" method="POST" id="saveForm" enctype="multipart/form-data">
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
                                    <label for="title_{{$language->code}}" class="form-label">Key ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="key[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Ad daxil edin" value="{{ $item->getTranslation('key',$language->code) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_{{$language->code}}" class="form-label">Value ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                        name="value[{{$language->code}}]"
                                        id="title_{{$language->code}}"
                                        placeholder="Ad daxil edin" value="{{ $item->getTranslation('value',$language->code) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
