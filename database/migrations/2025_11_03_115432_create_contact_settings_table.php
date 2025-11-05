<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();

            // Компания
            $table->string('company_name')->nullable();
            $table->text('company_text')->nullable();

            // Контакты
            $table->string('phone')->nullable();          // один телефон — "tel:+77471234567"
            $table->string('email_link')->nullable();     // "mailto:greenviva.kz@gmail.com"

            // Соцсети (только ссылки)
            $table->string('whatsapp_link')->nullable();  // "https://wa.me/77471234567"
            $table->string('youtube_link')->nullable();   // "https://youtube.com/@greenviva"
            $table->string('telegram_link')->nullable();  // "https://t.me/greenviva"

            // Адрес и карта
            $table->text('address')->nullable();
            $table->text('map_embed')->nullable();        // iframe-код карты (canvas)

            $table->timestamps();
        });

        // Дефолтная запись (singleton)
        DB::table('contact_settings')->insert([
            'id'            => 1,
            'company_name'  => 'GREENVIVA',
            'company_text'  => 'Здоровье и природа — наша философия.',
            'phone'         => 'tel:+77471234567',
            'email_link'    => 'mailto:greenviva.kz@gmail.com',
            'whatsapp_link' => 'https://wa.me/77471234567',
            'youtube_link'  => 'https://youtube.com/@greenviva',
            'telegram_link' => 'https://t.me/greenviva',
            'address'       => 'Алматы, Биржана Мустафина 56а',
            'map_embed'     => '<iframe src="https://yandex.uz/map-widget/v1/?ll=76.870735%2C43.171936&z=17" width="100%" height="475" frameborder="1" allowfullscreen="true"></iframe>',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};
