<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('booking_kostums', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('booking_kostums', 'verification_status')) {
                $table->string('verification_status')->default('pending')->after('status');
                $table->text('verification_notes')->nullable()->after('verification_status');
                $table->timestamp('verified_at')->nullable()->after('verification_notes');
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verified_at');
            }
        });
    }

    public function down()
    {
        Schema::table('booking_kostums', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['verification_status', 'verification_notes', 'verified_at', 'verified_by']);
        });
    }
};