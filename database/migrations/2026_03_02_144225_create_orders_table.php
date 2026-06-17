<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')
                  ->constrained('tables')
                  ->onDelete('cascade');

            $table->string('order_code', 30)->unique();
            $table->string('customer_name', 100);

            // 1. STATUS PRODUKSI (Untuk Antrian Dapur)
            // pending: baru masuk, cooking: dimasak, ready: siap antar, served: sudah di meja
            $table->enum('status', ['pending', 'cooking', 'ready', 'served'])
                  ->default('pending');

            // 2. STATUS PEMBAYARAN (Untuk Kasir)
            // unpaid: belum bayar, paid: sudah lunas
            $table->enum('payment_status', ['unpaid', 'paid'])
                  ->default('unpaid');

            $table->string('payment_method')->nullable(); // Cash / QRIS
            $table->decimal('total_price', 10, 2)->default(0);
            $table->decimal('payment_amount', 10, 2)->default(0); // Uang yang dibayar pelanggan
            $table->decimal('change_amount', 10, 2)->default(0);  // Kembalian

            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
