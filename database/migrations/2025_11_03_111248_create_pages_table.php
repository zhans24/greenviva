<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->string('template');              // 'about', 'privacy', ...
            $table->boolean('is_published')->default(false);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('content')->nullable();     // вся структура под шаблон
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['template', 'is_published']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('pages');
    }
};
