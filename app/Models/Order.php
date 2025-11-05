<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = [
        'number', 'name', 'phone', 'address', 'comment', 'status', 'total_price',
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    // Автогенерация номера заказа
    protected static function booted(): void
    {
        static::creating(function (self $order) {
            if (empty($order->number)) {
                $order->number = static::nextNumber();
            }
        });
    }

    public static function nextNumber(): string
    {
        // Формат: GV-YYYYMMDD-XXXX (сквозная нумерация по дате)
        $date = now()->format('Ymd');
        $prefix = "GV-$date-";

        // Находим последний номер за сегодня
        $last = static::query()
            ->where('number', 'like', "$prefix%")
            ->orderByDesc('id')
            ->value('number');

        $seq = 1;
        if ($last && preg_match('~^GV-\d{8}-(\d{4})$~', $last, $m)) {
            $seq = (int)$m[1] + 1;
        }

        return $prefix . str_pad((string)$seq, 4, '0', STR_PAD_LEFT);
    }
}
