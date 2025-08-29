@extends('backend.layouts.layout')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Dillər</h4>
                    <div class="buttons">
                        <a href="{{ route('admin.languages.create') }}" class="btn btn-success">Geriyə qayıt</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{route('admin.languages.store')}}" method="POST" id = "saveForm" >
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Ad</label>
                                <input type="text" name="title" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Kod</label>
                                <input type="text" name="code" class="form-control">
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


