<?php

namespace App\DataTables;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrdersDataTable extends DataTable
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
            ->addColumn('customer', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('delivery_info', function ($row) {
                if ($row->delivery_type == 'home_delivery') {
                    return 'Ünvana çatdırılma: ' . ($row->deliveryAddress ? $row->deliveryAddress?->city?->title . ', ' . $row->deliveryAddress->address : 'Ünvan qeyd olunmayıb');
                } else {
                    return 'Mağazadan alma: ' . ($row->store ? $row->store->address : 'Mağaza qeyd olunmayıb');
                }
            })
            ->addColumn('status_label', function ($row) {
                if(is_null($row->status)){
                    $status = OrderStatusEnum::PENDING;
                }else{
                    $status = OrderStatusEnum::from($row->status?->status);
                }
                $color = $status->toColor();
                return '<a href="javascript:void(0)" class="badge bg-' . $color . ' status-badge"
                        data-order-id="' . $row->order_number . '"
                        data-current-status="' . $status->value . '"
                        data-bs-toggle="modal"
                        data-bs-target="#statusUpdateModal">'
                    . ucfirst($status->toString()) . '</a>';
            })->addColumn("order_reason", function($row){
                return $row->status?->cancelReason?->title ? $row->status?->cancelReason?->title : 'Digər';
            })
            ->addColumn("reason_text", function($row){
                return $row->status?->reason;
            })
            ->addColumn('action', function ($row) {
                $viewBtn = '<a href="' . route('admin.orders.show', $row->id) . '" class="btn btn-sm btn-info" title="Ətraflı"><i class="fas fa-eye"></i></a>';
//                $deleteBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-sm btn-danger delete-btn" title="Sil"><i class="fas fa-trash"></i></a>';
                return '<div class="btn-group">' . $viewBtn  . '</div>';
            })
            ->filterColumn('customer', function($query, $keyword) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$keyword}%"]);
            })->addColumn("delivery_type", function($item){
                return $item->delivery_type == 'delivery_type' ? 'Ünvana çatdırılma' : 'Mağazadan alınma';
            })
            ->rawColumns(['status_label', 'action'])
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Order $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['deliveryAddress', 'store', 'status']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('orders-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0, 'desc')
            ->parameters([
                'scrollX' => true,
                'autoWidth' => false,
                'fixedColumns' => true,
                'columnDefs' => [
                    [
                        'targets' => [4, 5, 10],
                        'width' => '300px',
                    ],
                    [
                        'targets' => [9],
                        'width' => '150px',
                    ],
                ],
            ])
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
            Column::computed('customer', 'Müştəri')
                ->exportable(false)
                ->printable(false)
                ->searchable(true),
            Column::make('email'),
            Column::make('phone')->title('Telefon'),
            Column::make('delivery_type')->title('Çatdırılma növü')->visible(false),
            Column::computed('delivery_info', 'Çatdırılma məlumatı')
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->visible(false),
            Column::make('total_amount', )->title('Ümumi məbləğ'),
            Column::computed('order_number', 'Sifarişin nömrəsi'),
            Column::computed('status_label', 'Status')
                ->exportable(false)
                ->printable(false),
            Column::computed('order_reason', 'Sifariş ləğv seçimi')->visible(false),
            Column::computed('reason_text', 'Sifariş ləğvi səbəbi')->visible(false),
            Column::make('created_at', )->title('Yaradılma tarixi'),
            Column::computed('action', 'Əməliyyatlar')
                ->exportable(false)
                ->printable(false)
                ->width(120)
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
        return 'Orders_' . date('YmdHis');
    }
}
