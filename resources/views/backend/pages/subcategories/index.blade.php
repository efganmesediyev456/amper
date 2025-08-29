@extends('backend.layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">

                   
                      

                        <h4>
                            {{ $category->title ?  $category->title : 'N/A' }} - Alt kateqoriyalar
                        </h4>
                        <div class="d-flex">
                            <a href="{{ route('admin.categories.index') }}" class="gap-2 btn btn-info me-2 d-flex align-items-center">
                                <i class="fas fa-arrow-left"></i>
                                <span class="wmax">Geriyə qayıt</span>
                            </a>
                            
                            <a href="{{ route('admin.categories.subcategories.create', $category->id) }}" class="btn btn-success">
                                <i class="fas fa-plus"></i>
                                <span>Yeni Alt Kateqoriya</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{ $dataTable->table() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush