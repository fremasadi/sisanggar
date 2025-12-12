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
        // 1. Modifikasi tabel booking_kostums
        Schema::table('booking_kostums', function (Blueprint $table) {
            // Tambah field order_id untuk grouping
            $table->string('order_id', 100)->after('id')->nullable();
            
            // Hapus id_kostum karena akan dipindah ke detail
            // JANGAN hapus dulu, biarkan untuk backward compatibility
            $table->dropForeign(['id_kostum']);
            $table->dropColumn('id_kostum');
        });

        // 2. Buat tabel booking_details untuk menyimpan detail item
        Schema::create('booking_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking_kostums')->onDelete('cascade');
            $table->foreignId('kostum_id')->constrained('kostums')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('harga_sewa', 10, 2); // Snapshot harga saat booking
            $table->decimal('subtotal', 10, 2); // quantity * harga_sewa * durasi
            $table->timestamps();
        });

        // 3. Modifikasi tabel pembayarans jika belum ada order_id
        if (!Schema::hasColumn('pembayarans', 'order_id')) {
            Schema::table('pembayarans', function (Blueprint $table) {
                $table->string('order_id', 100)->after('transaksi_id')->nullable();
                $table->index('order_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_details_and_update_bookings');
    }
};
