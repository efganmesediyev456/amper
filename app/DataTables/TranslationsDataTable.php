<?php

namespace App\DataTables;

use App\Models\LangTranslation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TranslationsDataTable extends DataTable
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
                $edit = '<a href="' . route('admin.translations.edit', $user->id) . '" class="btn  btn-sm fs-5"><i class="fas fa-edit"></i></a>';
                $delete = '<form action="' . route('admin.translations.destroy', $user->id) . '" method="POST" class="delete-form">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm fs-5 text-danger delete-btn"><i class="fas fa-trash"></i></button>
                            </form>';
                $html .= $edit . $delete;
                $html .= '</div>';
                return $html;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(LangTranslation $model): QueryBuilder
    {
        return $model->where('locale', app()->getLocale())->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('translations-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy([0,'desc'])
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('key')->title("Key"),
            Column::make('value')->title("Dəyər"),
            Column::make('created_at')->title('Yaradılma tarixi'),
            Column::computed('action')->title('Əməliyyatlar')
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
        return 'Translations_' . date('YmdHis');
    }
}
