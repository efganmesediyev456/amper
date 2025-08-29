@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>İstifadəçilər</h4>
                <div class="buttons">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Yeni istifadəçi
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <p class="alert alert-success">{{ session('success') }}</p>
            @endif
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush