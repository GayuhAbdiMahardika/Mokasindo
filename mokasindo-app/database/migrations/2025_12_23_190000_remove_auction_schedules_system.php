<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * 
     * Perubahan alur lelang:
     * - Tidak ada lagi auction_schedules
     * - Setiap kendaraan yang di-approve langsung masuk lelang aktif
     * - Setiap kendaraan punya durasi waktu sendiri
     */
    public function up(): void
    {
        // 1. Hapus kolom auction_schedule_id dari auctions jika ada
        if (Schema::hasColumn('auctions', 'auction_schedule_id')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->dropForeign(['auction_schedule_id']);
                $table->dropColumn('auction_schedule_id');
            });
        }

        // 2. Pastikan duration_hours ada di auctions
        if (!Schema::hasColumn('auctions', 'duration_hours')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->integer('duration_hours')->default(48)->after('end_time');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambah kembali kolom schedule_id jika ingin rollback
        if (!Schema::hasColumn('auctions', 'auction_schedule_id')) {
            Schema::table('auctions', function (Blueprint $table) {
                $table->foreignId('auction_schedule_id')->nullable()->after('vehicle_id');
            });
        }
    }
};
