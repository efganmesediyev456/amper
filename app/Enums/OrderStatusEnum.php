<?php

namespace App\Enums;

enum OrderStatusEnum: int
{
    case PENDING = 1;
    case PREPARE = 2;
    case COURIER = 3;
    case DELIVERED = 4;
    case CANCELED = 5;

    public function toString(string $locale = 'az'): string
    {
        $locale = in_array($locale, ['az', 'en', 'ru']) ? $locale : 'az';

        
        return match ($locale) {
            'az' => match ($this) {
                self::PENDING => 'Sifariş verildi',
                self::PREPARE => 'Sifariş hazırlanır',
                self::COURIER => 'Kuryerə verildi',
                self::DELIVERED => 'Çatdırıldı',
                self::CANCELED => 'Ləğv edildi',
            },
            'en' => match ($this) {
                self::PENDING => 'Order placed',
                self::PREPARE => 'Preparing order',
                self::COURIER => 'Sent to courier',
                self::DELIVERED => 'Delivered',
                self::CANCELED => 'Canceled',
            },
            'ru' => match ($this) {
                self::PENDING => 'Заказ размещён',
                self::PREPARE => 'Заказ готовится',
                self::COURIER => 'Передано курьеру',
                self::DELIVERED => 'Доставлено',
                self::CANCELED => 'Отменено',
            },
        };
    }

    public function toColor(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::PREPARE => 'info',
            self::COURIER, self::DELIVERED => 'success',
            self::CANCELED => 'danger',
        };
    }
}
