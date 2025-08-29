@extends('backend.layouts.layout')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Həftənin seçimi</h4>
                    <div class="buttons">
                        <a href="{{ route('admin.weekly_selections.index') }}" class="btn btn-success">Geriyə qayıt</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.weekly_selections.store') }}" method="POST" id="saveForm" enctype="multipart/form-data">
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

                                <div class="mb-3">
                                    <label for="title_{{$language->code}}" class="form-label">Başlıq ({{$language->code}})</label>
                                    <input type="text" class="form-control"
                                           name="title[{{$language->code}}]"
                                           id="title_{{$language->code}}"
                                           placeholder="Başlıq daxil edin">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Başlama tarixi</label>
                                <input type="datetime-local" class="form-control" name="start_date" id="start_date" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Bitmə tarixi</label>
                                <input type="datetime-local" class="form-control" name="end_date" id="end_date" required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="products" class="form-label">Məhsullar</label>
                                <select class="select2 form-control" name="products[]" id="products" multiple required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>
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

@push('js')
<script>
    $(document).ready(function() {
        $('#products').select2({
            placeholder: "Məhsulları seçin",
            allowClear: true
        });
    });
</script>
@endpush