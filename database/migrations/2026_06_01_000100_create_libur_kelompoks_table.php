<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libur_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks')->cascadeOnDelete();
            $table->foreignId('jadwal_kelompok_id')->nullable()->constrained('jadwal_kelompoks')->nullOnDelete();
            $table->date('tanggal');
            $table->string('judul');
            $table->text('alasan')->nullable();
            $table->enum('status', ['aktif', 'dibatalkan'])->default('aktif');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['kelompok_id', 'tanggal', 'jadwal_kelompok_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libur_kelompoks');
    }
};
