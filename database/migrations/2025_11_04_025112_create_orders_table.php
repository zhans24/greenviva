<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('number')->unique()->index();     // GV-YYYYMMDD-XXXX
            $t->string('name');
            $t->string('phone', 32);
            $t->string('address');
            $t->text('comment')->nullable();

            $t->string('status')->default('pending')->index();
            // pending, confirmed, shipped, cancelled

            $t->unsignedInteger('total_price')->default(0); // сумма в тг
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
