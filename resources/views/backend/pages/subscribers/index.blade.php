@extends('backend.layouts.layout')

@section('content')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Sayta abunə olanlar</h4>
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

    <script>
        $(function () {
            @if(session()->has("success"))
                Swal.fire({
                    title: 'Uğurlu!',
                    text: '{{ session("success") }}',
                    icon: 'success',
                    timer: 2000, 
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            @endif
        });
    </script>

@endpush
