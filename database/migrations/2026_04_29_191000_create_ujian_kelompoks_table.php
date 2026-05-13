<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks')->cascadeOnDelete();
            $table->foreignId('kelompok_tujuan_id')->nullable()->constrained('kelompoks')->nullOnDelete();
            $table->string('nama_ujian');
            $table->date('tanggal_ujian');
            $table->time('jam_mulai')->nullable();
            $table->string('lokasi')->nullable();
            $table->enum('status', ['draft', 'dibuka', 'selesai'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_kelompoks');
    }
};
