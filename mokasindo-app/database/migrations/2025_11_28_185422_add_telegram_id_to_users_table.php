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
        Schema::table('users', function (Blueprint $table) {
        // Menambahkan kolom telegram_chat_id setelah kolom email
        $table->string('telegram_chat_id')->nullable()->after('email');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('users', function (Blueprint $table) {
        // Hapus kolom kalau migrasi dibatalkan
        $table->dropColumn('telegram_chat_id');
    });
    }
};
