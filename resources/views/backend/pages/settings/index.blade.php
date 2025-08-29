@extends('backend.layouts.layout')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Sayt Parametrləri</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update', $item->id) }}" method="POST" id="saveForm"
                    enctype="multipart/form-data">
                    @method('PUT')



                    <ul class="nav nav-tabs" id="languageTabs" role="tablist">

                        @foreach ($languages as $language)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($loop->iteration == 1) active @endif"
                                    id="{{ $language->code }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#{{ $language->code }}" type="button" role="tab"
                                    aria-controls="{{ $language->code }}" aria-selected="true">{{ $language->title }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content mt-3" id="languageTabsContent">
                        @foreach ($languages as $language)
                            <div class="tab-pane fade show @if ($loop->iteration == 1) active @endif"
                                id="{{ $language->code }}" role="tabpanel" aria-labelledby="{{ $language->code }}-tab">

                                <div class="mb-3">
                                    <label for="header_offer" class="form-label">Header təklif text
                                        {{ $language->code }}</label>
                                    <input type="text" class="form-control" name="header_offer[{{ $language->code }}]"
                                        id="header_offer[{{ $language->code }}]" placeholder="Daxil edin"
                                        value="{{ $item->getTranslation('header_offer', $language->code, true) }}">
                                </div>

                            </div>
                        @endforeach
                    </div>


                    <div class="mb-3">
                        <label for="header_logo" class="form-label">Header Logo</label>
                        <input type="file" name="header_logo" class="form-control">
                        <small class="text-muted d-block my-2">Tövsiyə olunan ölçü: 160x50 piksel</small>

                        @if ($item->header_logo)
                            <img width="300" src="{{ '/storage/' . $item->header_logo }}" alt="Header Logo">
                        @endif


                    </div>

                    <div class="mb-3">
                        <label for="footer_logo" class="form-label">Footer Logo</label>
                        <input type="file" name="footer_logo" class="form-control">
                        <small class="text-muted d-block my-2">Tövsiyə olunan ölçü: 160x50 piksel</small>

                        @if ($item->footer_logo)
                            <img width="300" src="{{ '/storage/' . $item->footer_logo }}" alt="Footer Logo">
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="favicon" class="form-label">Favicon</label>
                                <input type="file" name="favicon" class="form-control">
                                @if ($item->favicon)
                                    <img width="64" src="{{ '/storage/' . $item->favicon }}" alt="Favicon">
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                               <div class="mb-3">
                                <label for="favicon" class="form-label">Chat whatsapp nömrəsi</label>
                                <input type="text" name="chat_whatsapp_number" class="form-control" value="{{ $item->chat_whatsapp_number }}">
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
