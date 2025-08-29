@extends('backend.layouts.layout')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Tərcümələr</h4>

                </div>
            </div>
            <div class="card-body">
                <form action="{{route('admin.translations.update', $item->id)}}" method="POST" id="saveForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach($languages as $lang)
                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link {{ $loop->first ? 'active' : '' }}"
                                    id="{{$lang->code}}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{$lang->code}}"
                                    type="button"
                                    role="tab"
                                    aria-controls="{{$lang->code}}"
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    {{$lang->code}}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mb-3 mt-3">
                        <label for="key" class="form-label">Açar</label>
                        <input type="text" class="form-control" id="key" name="key" value="{{$item->key}}" placeholder="">
                    </div>

                    <div class="tab-content mt-4" id="myTabContent">
                        @foreach($languages as $lang)
                            <div
                                class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="{{$lang->code}}"
                                role="tabpanel"
                                aria-labelledby="{{$lang->code}}-tab">

                                <div class="mb-3">
                                    <label for="value-{{$lang->code}}" class="form-label">Dəyər ({{$lang->code}})</label>
                                    <input type="text" class="form-control" id="category-name" name="value[{{$lang->code}}]" value="{{ $item->getValue($lang->code) }}" placeholder="">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!--<div class="mb-3">-->
                    <!--    <label for="filename" class="form-label">Qrup</label>-->
                    <!--    <input type="text" class="form-control" id="filename" name="filename" placeholder="" value="{{ $item->filename }}">-->
                    <!--</div>-->


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
