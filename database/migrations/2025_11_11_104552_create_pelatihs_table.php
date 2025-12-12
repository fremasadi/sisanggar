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
        Schema::create('pelatihs', function (Blueprint $table) {
            $table->id('id_pelatih'); // PK dan juga FK ke users.id
            $table->foreign('id_pelatih')->references('id')->on('users')->onDelete('cascade');
            $table->string('bidang_tari');
            $table->text('jadwal_tetap')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihs');
    }
};
