@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Yeni İstifadəçi</h4>
                <div class="buttons">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-success">Geriyə qayıt</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" id="saveForm" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" class="form-control" name="name" id="name" required placeholder="Ad Soyad daxil edin">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email ünvanı</label>
                            <input type="email" class="form-control" name="email" id="email" required placeholder="Email daxil edin">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Şifrə</label>
                            <input type="password" class="form-control" name="password" id="password" required placeholder="Şifrə daxil edin">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Şifrə təkrarı</label>
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required placeholder="Şifrəni təkrar daxil edin">
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