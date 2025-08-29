<?php

namespace App\DataTables;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Language;
use App\Models\ProductProperty;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PropertiesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($item) {
                $html = '<div class="d-flex gap-1">';
                $edit = '<a href="' . route('admin.products.properties_old.edit', ['id' => $item->product_id, 'item_id' => $item->id]) . '" class="btn  btn-sm fs-5"><i class="fas fa-edit"></i></a>';
                $delete = '<form action="' . route('admin.products.properties_old.destroy', ['id' => $item->product_id, 'item_id' => $item->id]) . '" method="POST" class="delete-form">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm fs-5 text-danger delete-btn"><i class="fas fa-trash"></i></button>
                            </form>';
                $html .= $edit . $delete;
                $html .= '</div>';
                return $html;
            })
            ->addColumn('key', fn($item) => $item->key)
            ->addColumn('value', fn($item) => $item->value)
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */

    public function query(ProductProperty $model): QueryBuilder
    {
        $query = $model->newQuery();

        if (request()->has('id') && request()->id) {
            $query->where('product_id', request()->id);
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('properties_old-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->selectStyleSingle()
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
                                    model:'".addslashes(ProductProperty::class)."'
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
            ])  ->buttons(
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('colvis'),
                
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
            Column::make('key')->title('Key'),
            Column::make('value')->title('Value'),
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
        return 'properties_' . date('YmdHis');
    }
}
