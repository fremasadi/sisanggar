<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE hasil_ujian_kelompoks MODIFY hasil ENUM('menunggu', 'lulus', 'mengulang', 'tidak_lulus') DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        DB::statement("UPDATE hasil_ujian_kelompoks SET hasil = 'tidak_lulus' WHERE hasil = 'mengulang'");
        DB::statement("ALTER TABLE hasil_ujian_kelompoks MODIFY hasil ENUM('menunggu', 'lulus', 'tidak_lulus') DEFAULT 'menunggu'");
    }
};
