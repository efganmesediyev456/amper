<?php

namespace App\DataTables;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return view('backend.pages.admins.actions', compact('row'))->render();
            })
            ->addColumn('avatar', function ($row) {
                if ($row->avatar) {
                    return '<img src="' . asset('storage/'.$row->avatar) . '" alt="Admin Avatar" style="max-height: 50px; border-radius: 50%;">';
                }
                return 'No Image';
            })
            ->addColumn('status', function ($row) {
                $checked = $row->status ? 'checked' : '';
                return '<div class="form-check form-switch">
                    <input class="form-check-input status-switch" type="checkbox" data-id="'.$row->id.'" '.$checked.'>
                </div>';
            })
            ->addColumn('role', function ($row) {
                $badge = 'bg-primary';
                if ($row->role == 'superadmin') {
                    $badge = 'bg-danger';
                } elseif ($row->role == 'editor') {
                    $badge = 'bg-warning';
                }
                return '<span class="badge ' . $badge . '">' . ucfirst($row->role) . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['action', 'avatar', 'status', 'role'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Admin $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('admins-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->parameters([
                        'pageLength' => 25, // 1 səhifədə 25 nəticə
                        'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Hamısı"]],
                        'initComplete' => "function(settings, json) {
                            $(document).on('change', '.status-switch', function() {
                                var id = $(this).data('id');
                                var status = $(this).prop('checked') ? 1 : 0;
                                
                                $.ajax({
                                    url: '".route('admin.update-status')."',
                                    type: 'POST',
                                    data: {
                                        _token: $('meta[name=\"csrf-token\"]').attr('content'),
                                        id: id,
                                        status: status,
                                        model:'".addslashes(Admin::class)."'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Uğurlu!',
                                            text: response.message,
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                    },
                                    error: function(xhr) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Xəta!',
                                            text: 'Status yenilənmədi'
                                        });
                                        
                                        // Revert switch to original state if there's an error
                                        $(this).prop('checked', !status);
                                    }
                                });
                            });
                        }",
                    ])
                    ->buttons(
                        Button::make('excel')->text('Excel-ə ixrac et'),
                        Button::make('csv')->text('CSV-ə ixrac et'),
                        Button::make('pdf')->text('PDF-ə ixrac et'),
                        Button::make('print')->text('Çap et'),
                        Button::make('colvis')->text('Sütunları göstər/gizlət'),
                    );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name')->title('Ad Soyad'),
            Column::make('email')->title('Email'),
            Column::make('created_at')->title('Yaradılma tarixi'),
            
            Column::computed('action')->title('Əməliyyatlar')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Admins_' . date('YmdHis');
    }
}