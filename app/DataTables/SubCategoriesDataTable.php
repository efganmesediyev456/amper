<?php

namespace App\DataTables;

use App\Models\SubCategory;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubCategoriesDataTable extends DataTable
{
    protected $subcategory;

    public function with(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->$k = $v;
            }
            return $this;
        }
        
        $this->$key = $value;
        return $this;
    }

    
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($item) {
                return view('backend.pages.subcategories.actions', compact('item'), [
                    'editRoute' => route('admin.categories.subcategories.edit', [$this->category->id, $item->id]),
                    'deleteRoute' => route('admin.categories.subcategories.destroy', [$this->category->id, $item->id]),
                    'categoriesRoute' => route('admin.categories.subcategories.index', [$this->category->id, $item->id]),
                    'showBrends' => route('admin.brends.index', ['subcategory_id'=>$item->id]),
                ]);
            })
            ->addColumn('title2', function ($item) {
                    $item->title;
            })
            ->editColumn('title2', function ($row) {
                return $row->title;
            })
            ->editColumn('icon', function ($item) {
                if ($item->icon) {
                    return '<img src="' . asset("storage/{$item->icon}") . '" width="50">';
                }
                return 'N/A';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->rawColumns(['action', 'icon'])
            ->setRowId('id');
    }

    public function query(SubCategory $model): QueryBuilder
    {
        return $model->newQuery()->where('category_id', $this->category->id);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subcategories-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0,'desc')
            ->selectStyleSingle()
            ->buttons([]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id')->title('ID'),
            Column::make('title2')->title('Başlıq'),
            Column::make('icon')->title('İkon'),
            Column::make('created_at')->title('Tarix'),
            Column::computed('action')->title('Əməliyyatlar')
                ->exportable(false)
                ->printable(false)
                ->width(150)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Subcategories_' . date('YmdHis');
    }
}