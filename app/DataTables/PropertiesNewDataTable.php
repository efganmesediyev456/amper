<?php

namespace App\DataTables;

use App\Models\Property;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PropertiesNewDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($row) {
                return '
                <div class="btn-group">
                    <a href="' . route('admin.properties.edit', $row->id) . '" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="' . route('admin.properties.destroy', $row->id) . '" method="POST" style="margin-left:5px;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger delete-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                ';
            })->addColumn("title", fn($row)=>$row->title)
            ->addColumn('status', function($item) {
                $checked = $item->status ? 'checked' : '';
                return '<div class="form-check form-switch">
                    <input class="form-check-input status-switch" type="checkbox" data-id="'.$item->id.'" '.$checked.'>
                </div>';
            })
            ->filterColumn('title', function($query, $keyword) {
                $query->whereHas('translations', function($q) use($keyword){
                    $q->where('key','title')->where('value','like','%'.$keyword.'%')->where('locale',app()->getLocale());
                });
            })
            ->rawColumns(['action','status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Property $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Property $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('properties_old-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip')
            ->orderBy([0, 'asc'])
            ->parameters([
                'pageLength' => 25, // 1 səhifədə 25 nəticə
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Hamısı"]],
                'rowReorder' => [
                    'dataSrc' => 'id',
                ],
                'initComplete' => "function(settings, json) {
                    var table = this.api();
                    table.on('row-reorder', function (e, diff, edit) {
                        let data = [];
                        for (let i = 0; i < diff.length; i++) {
                            data.push({
                                id: table.row(diff[i].node).id(),
                                newPosition: diff[i].newData
                            });
                        }
                        if (data.length) {
                            $.ajax({
                                url: '".route('admin.all.update-order')."',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name=\"csrf-token\"]').attr('content'),
                                    items: data,
                                    model:'".addslashes(Property::class)."'
                                },
                                success: function (response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Uğurlu!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                },
                                error: function (xhr) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Xəta!',
                                        text: 'Sıralama yenilənmədi'
                                    });
                                }
                            });
                        }
                    });

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
                                        model:'".addslashes(Property::class)."'
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
            ])  ->buttons(
                Button::make('excel')->text('Excel-ə ixrac et'),
                Button::make('csv')->text('CSV-ə ixrac et'),
                Button::make('pdf')->text('PDF-ə ixrac et'),
                Button::make('print')->text('Çap et'),
                Button::make('colvis')->text('Sütunları göstər/gizlət'),
                
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::make('order')->title('Sıra')->visible(false),
            Column::make('id'),
            Column::make('title')->title('Başlıq'),
            Column::make('status')->title('Status')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),
            Column::make('created_at')->title('Yaradılma tarixi'),
            Column::computed('action')->title('Əməliyyatlar')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Properties_' . date('YmdHis');
    }
}
