<?php

namespace App\DataTables;

use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class LanguagesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($user) {
                $html = '<div class="d-flex gap-1">';
                $edit = '<a href="'.route('admin.languages.edit', $user->id).'" class="btn  btn-sm fs-5"><i class="fas fa-edit"></i></a>';
                $delete = '<a href="'.route('admin.languages.destroy', $user->id).'" class="btn  btn-sm fs-5"><i class="fas fa-trash"></i></a>';
                $html.= $edit.$delete;
                $html.='</div>';
                return $html;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Language $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('languages-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrltip')
                    ->orderBy([0, 'asc']) 
                    ->selectStyleSingle()
                    ->parameters([
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
                                            model:'".addslashes(Language::class)."'
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
                        }",
                    ]) 
                    ->buttons(
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('colvis')
                    );
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('order')->title('Sıra')->visible(false),
            Column::make('id'),
            Column::make('title')->title("Ad"),
            Column::make('code')->title("Kod"),
            Column::make('created_at')->title('Yaradılma tarixi'),
            Column::computed('action')
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
        return 'Languages_' . date('YmdHis');
    }
}
