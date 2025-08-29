@extends('backend.layouts.layout')

@section('content')


<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Məhsullar</h4>
                <div class="buttons">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-success">Yenisini yarat</a>
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


<script>
    $(document).ready(function() {
    $('#products-table').on('draw.dt', function() {
        initToggleListeners();
    });

    initToggleListeners();

    function initToggleListeners() {
        $('.toggle-seasonal').on('change', function() {
            const productId = $(this).data('id');
            const toggleSwitch = $(this);

            $.ajax({
                url: '/admin/products/toggle-seasonal',
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'product_id': productId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Uğurlu!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Xəta!',
                            text: response.message
                        });
                        toggleSwitch.prop('checked', !toggleSwitch.prop('checked'));
                    }
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta!',
                        text: 'Xəta baş verdi'
                    });
                    toggleSwitch.prop('checked', !toggleSwitch.prop('checked'));
                    console.error(error);
                }
            });
        });

        $('.toggle-special').on('change', function() {
            const productId = $(this).data('id');
            const toggleSwitch = $(this);

            $.ajax({
                url: '/admin/products/toggle-special-offer',
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'product_id': productId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Uğurlu!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Xəta!',
                            text: response.message
                        });
                        toggleSwitch.prop('checked', !toggleSwitch.prop('checked'));
                    }
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta!',
                        text: 'Xəta baş verdi'
                    });
                    toggleSwitch.prop('checked', !toggleSwitch.prop('checked'));
                    console.error(error);
                }
            });
        });

        $('.toggle-bundle').on('change', function() {
            const productId = $(this).data('id');
            const toggleSwitch = $(this);

            $.ajax({
                url: '/admin/products/toggle-bundle',
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'product_id': productId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Uğurlu!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Xəta!',
                            text: response.message
                        });
                        toggleSwitch.prop('checked', !toggleSwitch.prop('checked'));
                    }
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta!',
                        text: 'Xəta baş verdi'
                    });
                    toggleSwitch.prop('checked', !toggleSwitch.prop('checked'));
                    console.error(error);
                }
            });
        });


        $('.toggle-weekly').on('change', function() {
            const productId = $(this).data('id');
            const toggleSwitch = $(this);

            $.ajax({
                url: '/admin/products/toggle-weekly-offer',
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'product_id': productId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Uğurlu!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Xəta!',
                            text: response.message
                        });
                        // Hata durumunda switch'i eski konumuna getir
                        toggleSwitch.prop('checked', !toggleSwitch.prop('checked'));
                    }
                },
                error: function(error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Xəta!',
                        text: 'Xəta baş verdi'
                    });
                    // Hata durumunda switch'i eski konumuna getir
                    toggleSwitch.prop('checked', !toggleSwitch.prop('checked'));
                    console.error(error);
                }
            });
        });


        

    }
});



</script>
@endpush