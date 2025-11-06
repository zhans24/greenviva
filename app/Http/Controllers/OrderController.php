<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required','string','max:255'],
            'phone'   => ['required','string','max:32'],
            'address' => ['required','string','max:500'],
            'comment' => ['nullable','string','max:5000'],
            'items'   => ['required','array','min:1'],
            'items.*.id'       => ['required','integer','exists:products,id'],
            'items.*.quantity' => ['required','integer','min:1','max:999'],
        ]);

        // Забираем товары, подтверждаем цены на бэке (чтобы не доверять фронту)
        $productIds = collect($data['items'])->pluck('id')->all();
        $products   = Product::query()->whereIn('id', $productIds)
            ->get()->keyBy('id');

        if ($products->isEmpty()) {
            return response()->json(['ok' => false, 'message' => 'Товары не найдены'], 422);
        }

        $order = DB::transaction(function () use ($data, $products) {
            $order = Order::create([
                'name'        => $data['name'],
                'phone'       => $data['phone'],
                'address'     => $data['address'],
                'comment'     => $data['comment'] ?? null,
                'status'      => 'pending',
                'total_price' => 0, // посчитаем ниже
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                /** @var \App\Models\Product $p */
                $p = $products[$item['id']];

                // ЦЕНООБРАЗОВАНИЕ: берём итоговую, как ты и договорился
                // old_price — оригинальная; price — скидочная; если есть скидка и price < old_price — продаём по price
                $unit = (filled($p->price) && filled($p->old_price) && $p->price < $p->old_price)
                    ? $p->price
                    : ($p->old_price ?? $p->price);

                $qty = (int)$item['quantity'];
                $line = $unit * $qty;

                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $p->id,
                    'sku'         => $p->sku,
                    'name'        => $p->name,   // снимок названия
                    'price'       => $unit,      // фиксируем цену в момент заказа
                    'quantity'    => $qty,
                    'total_price' => $line,
                ]);

                $total += $line;
            }

            $order->update(['total_price' => $total]);

            return $order;
        });

        return response()->json([
            'ok'      => true,
            'number'  => $order->number,
            'orderId' => $order->id,
        ]);
    }
}
