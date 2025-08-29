<?php

namespace App\DataTables;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderItemsDataTable extends DataTable
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
            ->addColumn('product_name', function ($row) {
                return $row->product->title;
            })
            ->addColumn('product_code', function ($row) {
                return $row->product ? $row->product->product_code ?? 'N/A' : 'N/A';
            })
            ->addColumn('product_image', function ($row) {
                if ($row->product && $row->product->image) {
                    return '<img src="/storage/' . $row->product->image. '" alt="Product Image" style="max-height: 50px;">';
                }
                return 'Şəkil yoxdur';
            })
            ->addColumn('subtotal', function ($row) {
                return number_format($row->price * $row->quantity, 2) . ' AZN';
            })
            ->rawColumns(['product_image'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\OrderItem $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(OrderItem $model): QueryBuilder
    {
        $orderId = $this->order->id ?? null;

        $query = $model->newQuery()
            ->with('product');

        if ($orderId) {
            $query->where('order_id', $orderId);
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
            ->setTableId('order-items-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'asc')
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
            Column::make('id')->title('Sıra №'),
            Column::computed('product_image', 'Şəkil')
                ->exportable(false)
                ->printable(false)
                ->width(80)
                ->addClass('text-center'),
            Column::computed('product_code', 'Məhsul kodu'),
            Column::computed('product_name', 'Məhsul adı'),
            Column::make('quantity')->title('Miqdar'),
            Column::make('price', 'Qiymət')
                ->render('function() { return parseFloat(data).toFixed(2) + " AZN"; }'),
            Column::computed('subtotal', 'Cəmi')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        $orderId = $this->order->id ?? 'all';
        return 'OrderItems_' . $orderId . '_' . date('YmdHis');
    }
}
