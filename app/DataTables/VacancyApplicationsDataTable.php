<?php

namespace App\DataTables;

use App\Models\VacancyApplication;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VacancyApplicationsDataTable extends DataTable
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
        
        ->addColumn('action', function ($user) {
            $html = '<div class="d-flex gap-1">';
            $delete = '<form action="' . route('admin.vacancy_applications.destroy', $user->id) . '" method="POST" class="delete-form" onsubmit="return confirm(\'Razısınız silinməyə?\')">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm fs-5 text-danger delete-btn"><i class="fas fa-trash"></i></button>
                    </form>';
            $html .= $delete;
            $html .= '</div>';
            return $html;
        })
        ->addColumn('cv', function ($row) {
                if ($row->cv_path) {
                    return '<a class="btn btn-success btn-sm" href="' . asset('/storage/'.$row->cv_path) . '" alt="CV File">CV-ni Yüklə</a>';
                }
                return 'CV yoxdur';
            })
            ->addColumn('vacancy', function ($row) {
                return $row->vacancy ? $row->vacancy->vacancy_title : 'N/A';
            })
            ->rawColumns(['cv','action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\VacancyApplication $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VacancyApplication $model): QueryBuilder
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
            ->setTableId('vacancy-applications-table')
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
            Column::make('vacancy', 'vacancy.title')->title('Vakansiya'),
            Column::make('first_name')->title('Ad'),
            Column::make('last_name')->title('Soyad'),
            Column::make('email')->title('Email'),
            Column::make('phone')->title('Telefon'),
            Column::make('cv')->title('CV'),
            Column::make('created_at')->title('Yaradılma tarixi'),
            Column::make('action')->title('Əməliyyat'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'VacancyApplications_' . date('YmdHis');
    }
}
