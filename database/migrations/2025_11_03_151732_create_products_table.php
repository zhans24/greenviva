<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->nullable()->constrained()->nullOnDelete();

            $t->string('sku')->nullable();
            $t->string('name');
            $t->string('slug')->unique();

            $t->unsignedInteger('price')->nullable();                 // тг, целое
            $t->unsignedInteger('old_price');

            $t->boolean('is_available')->default(true);   // вместо enum
            $t->boolean('is_best_seller')->default(false);

            // вкладки
            $t->longText('description')->nullable();      // Описание
            $t->longText('composition')->nullable();      // Состав
            $t->longText('usage')->nullable();            // Применение
            $t->text('delivery_info')->nullable();        // Доставка/оплата

            // SEO
            $t->string('seo_title')->nullable();
            $t->string('seo_h1')->nullable();
            $t->text('seo_description')->nullable();

            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
