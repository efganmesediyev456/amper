<?php

namespace App\DataTables;

use App\Models\Brend;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BrendsDataTable extends DataTable
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
                return view('backend.pages.brends.action', compact('row'))->render();
            })
            ->addColumn('image', function ($row) {
                if ($row->image) {
                    return '<img src="' . asset('/storage/'.$row->image) . '" alt="Brend Image" style="max-height: 50px;">';
                }
                return 'No Image';
            })
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->addColumn('subcategory_id', function ($row) {
                return $row->subCategories?->map(fn($tt)=>$tt->title)->implode(',');
            })
            ->editColumn('title', function ($row) {
                return $row->title;
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
            ->rawColumns(['action', 'image', 'status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Brend $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Brend $model): QueryBuilder
    {
        $query=$model->newQuery()->orderBy('order','asc');

        if(request('subcategory_id')){
            $query->whereHas('subcategories',fn($q)=>$q->where('sub_category_id', request('subcategory_id')));
        }
        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('brends-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip')
            // ->orderBy([1, 'desc'])
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

                        
                    
                   
                        $(table.table().body()).find('tr').each(function (i, row) {
                            data.push({
                                id: row.id,        
                                position: i + 1     
                            });
                        });


                        if (data.length) {
                            $.ajax({
                                url: '".route('admin.all.update-order')."',
                                type: 'POST',
                                data: {
                                    _token: $('meta[name=\"csrf-token\"]').attr('content'),
                                    items: data,
                                    model:'".addslashes(Brend::class)."'
                                },
                                success: function (response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Uğurlu!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });

                                        table.ajax.reload(null, false);

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
                                        model:'".addslashes(Brend::class)."'
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
            Column::make('image')->title('Şəkil'),
            Column::make('subcategory_id')->title('Alt kateqoriyalar'),
            Column::make('status')->title('Status'),
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
        return 'Brends_' . date('YmdHis');
    }
}