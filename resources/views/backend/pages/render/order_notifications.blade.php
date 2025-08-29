Ad Soyad: {{ $order->first_name }} {{ $order->last_name }}<br>
Email: {{ $order->email }}<br>
Telefon: {{ $order->phone }}<br>
Sifariş tarixi: {{ $order->created_at->format('d.m.Y H:i') }}<br>
@php
                                        $status = \App\Enums\OrderStatusEnum::from($order->status->status);
                                        $color = $status->toColor();
@endphp
Status: {{ ucfirst($status->toString()) }}<br>
Çatdırılma məlumatları <br>
Çatdırılma növü: {{ $order->delivery_type == 'home_delivery' ? 'Ünvana çatdırılma' : 'Mağazadan alma' }}<br>
@if($order->delivery_type == 'home_delivery' && $order->deliveryAddress)
Şəhər: {{ $order->deliveryAddress->city->title }}<br>
Ünvan: {{ $order->deliveryAddress->address }}<br>
@if($order->deliveryAddress->additional_info)
Əlavə məlumat: {{ $order->deliveryAddress->additional_info }}
@endif
@elseif($order->delivery_type == 'store_pickup' && $order->store)
Mağaza: {{ $order->store->name ?? 'Mağaza #'.$order->store->id }}<br>
Mağaza ünvanı: {{ $order->store->address }}<br>
@endif<br>
Status: {{ \App\Enums\OrderStatusEnum::from($order->status->status)->toString() }}<br>


Ümumi məbləğ: {{ number_format($order->total_amount, 2) }} AZN
