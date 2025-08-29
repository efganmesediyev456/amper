@extends('backend.layouts.layout')

@section('content')


    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Qiymət təklifi al!</h4>
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
   @if(session('success'))
    <script>
        Swal.fire({
            title: 'Uğurlu əməliyyat!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Bağla'
        });
        </script>
    @endif

@endpush
