@extends('backend.layouts.layout')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Adminlər</h4>
                <div class="buttons">
                    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Yeni admin
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush