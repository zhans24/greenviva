<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();

            // Снимок товара на момент заказа
            $t->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $t->string('sku')->nullable();
            $t->string('name');

            $t->unsignedInteger('price');   // цена за единицу в тг (фиксируем!)
            $t->unsignedInteger('quantity');
            $t->unsignedInteger('total_price'); // price * quantity

            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
