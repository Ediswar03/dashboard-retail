<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retail_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 20);
            $table->string('stock_code', 20);
            $table->string('description', 255);
            $table->integer('quantity');
            $table->dateTime('invoice_date');
            $table->decimal('unit_price', 12, 2);
            $table->string('customer_id', 20)->nullable(); // 'GUEST' jika tanpa akun
            $table->string('country', 100);
            $table->decimal('total_price', 14, 2);

            // Kolom turunan waktu (disiapkan saat cleaning Python, disimpan
            // langsung agar query dashboard tidak perlu hitung ulang setiap request)
            $table->smallInteger('year');
            $table->tinyInteger('month');
            $table->string('month_name', 10);
            $table->string('day_of_week', 10);
            $table->tinyInteger('hour');

            // Tidak memakai timestamps() agar insert ratusan ribu baris lebih ringan
            $table->index('invoice_date');
            $table->index('country');
            $table->index('stock_code');
            $table->index(['year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retail_transactions');
    }
};
