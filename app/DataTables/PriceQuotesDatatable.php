<?php

namespace App\DataTables;

use App\Models\PriceQuote;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PriceQuotesDatatable extends DataTable
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
            ->addColumn('file', function ($row) {
                if ($row->file_path) {
                    return '<a class="btn btn-success btn-sm" href="' . asset('/storage/'.$row->file_path) . '" alt="Brend Image" style="max-height: 50px;">Fayla Bax</a>';
                }
                return 'No fayl';
            })
           ->addColumn('delete', function ($row) {
                return '<a href="'.route('admin.price-quotes.delete',['price'=>$row->id]).'" onclick="return confirm(\'Qiymət təklifini silmək istədiyinizə əminsiniz?\')" class="btn btn-danger btn-sm" style="max-height: 50px;">Sil</a>';
            })
            ->rawColumns(['file','delete'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\PriceQuote $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PriceQuote $model): QueryBuilder
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
            ->setTableId('price-quotes-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
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
            Column::make('first_name')->title('Ad'),
            Column::make('last_name')->title('Soyad'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Telefon'),
            Column::make('note')->title('Qeyd'),
            Column::make('file')->title('Fayl'),
            Column::make('created_at')->title('Yaradılma tarixi'),
            Column::make('delete')->title('Sil'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'PriceQuotes_' . date('YmdHis');
    }

   
}
