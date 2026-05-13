<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spp_tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->date('bulan_tagihan');
            $table->decimal('nominal', 12, 2)->default(75000);
            $table->enum('status', ['menunggu', 'dibayar', 'gagal', 'dibatalkan'])->default('menunggu');
            $table->string('order_id')->nullable()->unique();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('bank')->nullable();
            $table->string('va_number')->nullable();
            $table->text('payment_token')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('midtrans_response')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['peserta_id', 'bulan_tagihan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spp_tagihans');
    }
};
