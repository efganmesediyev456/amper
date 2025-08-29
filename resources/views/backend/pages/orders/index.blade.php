@extends('backend.layouts.layout')

@section('content')

    <!-- Status Yeniləmə Modal -->
    <div class="modal fade" id="statusUpdateModal" tabindex="-1" aria-labelledby="statusUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusUpdateModalLabel">Sifariş statusunu yenilə</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusUpdateForm" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Sifarişin cari statusu:</label>
                            <div id="currentStatusBadge" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Yeni status seçin:</label>
                            <select class="form-select" id="statusSelect" name="status">
                                <!-- Status seçimləri JavaScript ilə doldurulacaq -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ləğv et</button>
                        <button type="submit" class="btn btn-primary">Yenilə</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Sifarişlərin siyahısı</h4>
                </div>
            </div>
            <div class="card-body">
                    {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Sifarişi silmək</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bu sifarişi silmək istədiyinizə əminsiniz?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ləğv et</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Sil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function() {
            const orderStatusEnum = {
                1: { name: 'Sifariş verildi', color: 'warning' },
                2: { name: 'Sifariş hazırlanır', color: 'info' },
                3: { name: 'Kuryerə verildi', color: 'success' },
                4: { name: 'Çatdırıldı', color: 'success' },
                5: { name: 'Ləğv edildi', color: 'danger' },
            };

            $(document).on('click', '.status-badge', function() {
                const orderId = $(this).data('order-id');
                const currentStatus = parseInt($(this).data('current-status'));

                $('#statusUpdateModalLabel').text('Sifariş #' + orderId + ' - Status yeniləmə');

                const currentStatusInfo = orderStatusEnum[currentStatus];
                $('#currentStatusBadge').html('<span class="badge bg-' + currentStatusInfo.color + '">' + currentStatusInfo.name + '</span>');

                $('#statusSelect').empty();

                for (const key in orderStatusEnum) {
                    if (orderStatusEnum.hasOwnProperty(key)) {
                        const selected = (parseInt(key) === currentStatus) ? 'selected' : '';
                        $('#statusSelect').append('<option value="' + key + '" ' + selected + '>' + orderStatusEnum[key].name + '</option>');
                    }
                }

                $('#statusUpdateForm').attr('action', '{{ route("admin.orders.update-status", "") }}/' + orderId);
            });

            $('#statusUpdateForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const url = form.attr('action');
                const formData = form.serialize();
                const selectedStatus = $('#statusSelect').val();
                const selectedStatusText = $('#statusSelect option:selected').text();

                $('#statusUpdateModal').modal('hide');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        showBootstrapAlert('Sifariş statusu "' + selectedStatusText + '" olaraq yeniləndi', 'success');

                        $('#orders-table').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr, status, error) {
                        showBootstrapAlert('Status yeniləmə xətası: ' + error, 'danger');
                    }
                });
            });

            function showBootstrapAlert(message, type) {
                $('#status-alert-container').remove();

                if (!$('#status-alert-container').length) {
                    $('body').append('<div id="status-alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
                }

                const alertId = 'alert-' + Date.now();
                const alertHtml = `
            <div id="${alertId}" class="alert mt-4 alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

                $('#status-alert-container').append(alertHtml);

                $(`#${alertId}`).hide().fadeIn(300);

                setTimeout(function() {
                    $(`#${alertId}`).fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    </script>
@endpush
