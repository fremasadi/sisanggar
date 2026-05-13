<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pelatihs', function (Blueprint $table) {
            $table->dropColumn(['bidang_tari', 'jadwal_tetap']);
        });
    }

    public function down(): void
    {
        Schema::table('pelatihs', function (Blueprint $table) {
            $table->string('bidang_tari')->nullable();
            $table->text('jadwal_tetap')->nullable();
        });
    }
};
