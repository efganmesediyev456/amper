<?php

namespace App\DataTables;

use App\Models\WeeklySelection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WeeklySelectionsDataTable extends DataTable
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
                return view('backend.pages.weekly_selections.action', compact('row'))->render();
            })
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->addColumn('product_count', function ($row) {
                return $row->products->count();
            })
            ->addColumn('date_range', function ($row) {
                return $row->start_date->format('Y-m-d H:i') . ' - ' . $row->end_date->format('Y-m-d H:i');
            })
            ->addColumn('status', function($item) {
                $checked = $item->status ? 'checked' : '';
                return '<div class="form-check form-switch">
                    <input class="form-check-input status-switch" type="checkbox" data-id="'.$item->id.'" '.$checked.'>
                </div>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->filterColumn('title', function($query, $keyword) {
                $query->whereHas('translations', function($q) use($keyword){
                    $q->where('key','title')->where('value','like','%'.$keyword.'%')->where('locale',app()->getLocale());
                });
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\WeeklySelection $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(WeeklySelection $model): QueryBuilder
    {
        return $model->newQuery()->with('products');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('weekly-selections-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrltip')
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'rowReorder' => [
                    'dataSrc' => 'id',
                ],
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
                                        model:'".addslashes(WeeklySelection::class)."'
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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::computed('title', 'Başlıq'),
            Column::computed('date_range', 'Tarix aralığı'),
            Column::computed('product_count', 'Məhsul sayı'),
            Column::make('status', 'Status'),
            Column::computed('created_at', 'Yaradılma tarixi'),
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
        return 'WeeklySelections_' . date('YmdHis');
    }
}