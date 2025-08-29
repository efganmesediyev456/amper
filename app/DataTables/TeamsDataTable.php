<?php

namespace App\DataTables;

use App\Models\Language;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TeamsDataTable extends DataTable
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
                $edit = '<a href="'.route('admin.teams.edit', $user->id).'" class="btn  btn-sm fs-5"><i class="fas fa-edit"></i></a>';
                $delete = '<form action="' . route('admin.teams.destroy', $user->id) . '" method="POST" class="delete-form">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm fs-5 text-danger delete-btn"><i class="fas fa-trash"></i></button>
                            </form>';
                $html.= $edit.$delete;
                $html.='</div>';
                return $html;
            })
            ->addColumn('title', fn($item)=>$item->title)
            ->addColumn('position', fn($item)=>$item->position)
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
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Team $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('teams-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Blfrtip')
                    ->rowReorder([
                        'dataSrc' => 'id'
                    ])
                    ->orderBy([0, 'asc']) 
                    ->selectStyleSingle()
                    ->parameters([
                        'pageLength' => 25, // 1 səhifədə 25 nəticə
                        'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Hamısı"]],
                        'rowReorder' => [
                            'dataSrc' => 'id',
                        ],
                        'initComplete' => "function(settings, json) {
                            var table = this.api();
                            
                            // Row reorder event handler
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
                                            model:'".addslashes(Team::class)."'
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
                            
                            // Status switch handler
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
                                        model:'".addslashes(Team::class)."'
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
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('order')->title('Sıra')->visible(false),
            Column::make('id'),
            Column::make('title')->title('Ad'),
            Column::make('position')->title('Vəzifə'),
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

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Teams_' . date('YmdHis');
    }
}