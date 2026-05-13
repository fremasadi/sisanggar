<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_kostums', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pengunjung')->nullable()->change();
            $table->string('nama_pemesan')->nullable()->after('order_id');
            $table->string('no_hp_pemesan', 30)->nullable()->after('nama_pemesan');
            $table->string('no_hp_pemesan_normalized', 30)->nullable()->after('no_hp_pemesan');
            $table->enum('tipe_booking', ['online', 'manual'])->default('online')->after('no_hp_pemesan_normalized');
            $table->enum('verification_status', ['pending', 'confirmed', 'rejected'])->default('pending')->after('tipe_booking');
            $table->timestamp('verified_at')->nullable()->after('verification_status');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('booking_kostums', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropColumn([
                'nama_pemesan',
                'no_hp_pemesan',
                'no_hp_pemesan_normalized',
                'tipe_booking',
                'verification_status',
                'verified_at',
                'verification_notes',
            ]);
            $table->unsignedBigInteger('id_pengunjung')->nullable(false)->change();
        });
    }
};
