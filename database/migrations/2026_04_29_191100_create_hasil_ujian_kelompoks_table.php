<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_ujian_kelompoks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_kelompok_id')->constrained('ujian_kelompoks')->cascadeOnDelete();
            $table->foreignId('peserta_id')->constrained('users')->cascadeOnDelete();
            $table->enum('hasil', ['menunggu', 'lulus', 'tidak_lulus'])->default('menunggu');
            $table->unsignedInteger('nilai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamp('promoted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_ujian_kelompoks');
    }
};
