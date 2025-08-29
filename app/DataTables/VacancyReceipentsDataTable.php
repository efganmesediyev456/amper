<?php

namespace App\DataTables;

use App\Models\VacancyReceipent;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VacancyReceipentsDataTable extends DataTable
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
                return view('backend.pages.vacancy-receipents.action', compact('row'))->render();
            })
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('title', function ($row) {
                return $row->title;
            })
            ->editColumn('email', function ($row) {
                return $row->email;
            })
            ->editColumn('status', function ($row) {
                return $row->status ? '<span class="badge bg-success">Aktiv</span>' : '<span class="badge bg-danger">Deaktiv</span>';
            })
            ->editColumn('order', function ($row) {
                return $row->order;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['action', 'status'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\VacancyReceipent $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VacancyReceipent $model): QueryBuilder
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
            ->setTableId('vacancy-receipents-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
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
            Column::make('title'),
            Column::make('email'),
            Column::make('status'),
            Column::make('order'),
            Column::make('created_at'),
            Column::computed('action')
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
        return 'VacancyReceipents_' . date('YmdHis');
    }
}