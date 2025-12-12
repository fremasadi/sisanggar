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
        Schema::create('kostums', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kostum');               // nama kostum
            $table->string('ukuran');                    // ukuran (S, M, L, XL, dll)
            $table->decimal('harga_sewa', 12, 2);
            $table->integer('stok');                     // stok tersedia
            $table->enum('status', ['tersedia', 'tidak']); // enum status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosta');
    }
};
