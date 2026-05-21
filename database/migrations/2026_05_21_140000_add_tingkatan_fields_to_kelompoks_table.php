<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelompoks', function (Blueprint $table) {
            $table->string('jalur_tingkatan')->nullable()->after('nama_kelompok');
            $table->unsignedInteger('tingkat_nomor')->nullable()->after('jalur_tingkatan');
        });
    }

    public function down(): void
    {
        Schema::table('kelompoks', function (Blueprint $table) {
            $table->dropColumn(['jalur_tingkatan', 'tingkat_nomor']);
        });
    }
};
