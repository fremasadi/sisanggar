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
        Schema::create('booking_kostums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kostum');
            $table->unsignedBigInteger('id_pengunjung');

            $table->date('tanggal_booking');
            $table->date('tanggal_pengambilan');
            $table->date('tanggal_pengembalian');

            $table->enum('status', ['menunggu', 'dibayar', 'selesai'])->default('menunggu');

            $table->decimal('total_biaya', 12, 2)->default(0);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_kostum')->references('id')->on('kostums')->onDelete('cascade');
            $table->foreign('id_pengunjung')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_kosta');
    }
};
