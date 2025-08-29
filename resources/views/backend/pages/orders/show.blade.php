@extends('backend.layouts.layout')

@section('content')
    <div class="container">
        <!-- Order Details Card -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Sifariş #{{ $order->order_number }} - Ətraflı məlumat</h4>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Sifarişlər siyahısına qayıt
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Customer Information Column -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Müştəri məlumatları</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Ad Soyad:</th>
                                <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $order->email }}</td>
                            </tr>
                            <tr>
                                <th>Telefon:</th>
                                <td>{{ $order->phone }}</td>
                            </tr>
                            <tr>
                                <th>Sifariş tarixi:</th>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @php
                                        $status = \App\Enums\OrderStatusEnum::from($order->status->status);
                                        $color = $status->toColor();
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($status->toString()) }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Delivery Information Column -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Çatdırılma məlumatları</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Çatdırılma növü:</th>
                                <td>{{ $order->delivery_type == 'home_delivery' ? 'Ünvana çatdırılma' : 'Mağazadan alma' }}</td>
                            </tr>
                            @if($order->delivery_type == 'home_delivery' && $order->deliveryAddress)
                                <tr>
                                    <th>Şəhər:</th>
                                    <td>{{ $order->deliveryAddress->city->title }}</td>
                                </tr>
                                <tr>
                                    <th>Ünvan:</th>
                                    <td>{{ $order->deliveryAddress->address }}</td>
                                </tr>
                                @if($order->deliveryAddress->additional_info)
                                    <tr>
                                        <th>Əlavə məlumat:</th>
                                        <td>{{ $order->deliveryAddress->additional_info }}</td>
                                    </tr>
                                @endif
                            @elseif($order->delivery_type == 'store_pickup' && $order->store)
                                <tr>
                                    <th>Mağaza:</th>
                                    <td>{{ $order->store->name ?? 'Mağaza #'.$order->store->id }}</td>
                                </tr>
                                <tr>
                                    <th>Mağaza ünvanı:</th>
                                    <td>{{ $order->store->address }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Ümumi məbləğ:</th>
                                <td><strong>{{ number_format($order->total_amount, 2) }} AZN</strong></td>
                            </tr>
                        </table>
                    </div>
                    @if($order->status?->status==\App\Enums\OrderStatusEnum::CANCELED->value)
                    <div class="col-md-6">
                        <h5 class="mb-3">Sifariş ləğvi</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Sifariş ləğv seçimi:</th>
                                <td>{{ $order->status?->cancelReason?->title ? $order->status?->cancelReason?->title : 'Digər' }}</td>
                            </tr>
                           
                           @if($order->status?->reason)
                            <tr>
                                <th>Sifariş ləğv səbəbi:</th>
                                <td>{{ $order->status?->reason }}</td>
                            </tr>
                           @endif
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="card">
            <div class="card-header">
                <h4>Səbətdəki məhsullar</h4>
            </div>
            <div class="card-body">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
