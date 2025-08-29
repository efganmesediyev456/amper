<?php

namespace App\DataTables;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VacanciesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($item) {
                $html = '<div class="d-flex justify-content-center">';
                $edit = '<a href="' . route('admin.vacancies.edit', $item->id) . '" class="btn btn-sm btn-primary mx-1"><i class="fas fa-edit"></i></a>';
                $delete = '<form action="' . route('admin.vacancies.destroy', $item->id) . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Silmək istədiyinizə əminsiniz?\')"><i class="fas fa-trash"></i></button>
                            </form>';
                $html .= $edit . $delete;
                $html .= '</div>';
                return $html;
            })
            ->addColumn('status', function($item) {
                $checked = $item->status ? 'checked' : '';
                return '<div class="form-check form-switch">
                    <input class="form-check-input status-switch" type="checkbox" data-id="'.$item->id.'" '.$checked.'>
                </div>';
            })
            ->filterColumn('vacancy_title', function($query, $keyword) {
                $query->whereHas('translations', function($q) use($keyword){
                    $q->where('key','vacancy_title')->where('value','like','%'.$keyword.'%')->where('locale',app()->getLocale());
                });
            })
            ->addColumn('vacancy_title', fn($item) => $item->vacancy_title)
            ->addColumn('vacancy_location', fn($item) => $item->vacancy_location)
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    public function query(Vacancy $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('vacancies-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->selectStyleSingle()
            ->dom('Blfrtip')
            ->orderBy([0, 'desc'])
            ->parameters([
                'pageLength' => 25, // 1 səhifədə 25 nəticə
                'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Hamısı"]],
                // 'rowReorder' => [
                //     'dataSrc' => 'id',
                // ],
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
                                    model:'".addslashes(Vacancy::class)."'
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
                                        model:'".addslashes(Vacancy::class)."'
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

    public function getColumns(): array
    {
        return [
            // Column::make('order')->title('Sıra')->visible(false),
            Column::make('id'),
            Column::make('vacancy_title')->title('Vakansiya adı'),
            Column::make('vacancy_location')->title('Ünvan'),
            Column::make('vacany_start_at')->title('Başlama tarixi'),
            Column::make('vacany_expired_at')->title('Bitmə tarixi'),
            Column::make('status')->title('Status')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),
            Column::make('created_at')->title('Yaradılma tarixi'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'vacancies_' . date('YmdHis');
    }
}