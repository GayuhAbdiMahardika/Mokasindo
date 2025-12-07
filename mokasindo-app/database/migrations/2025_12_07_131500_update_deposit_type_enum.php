<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add bid_deposit type to deposits.type enum.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE deposits MODIFY type ENUM('auction_deposit','bid_deposit','topup','deduction','withdrawal') NOT NULL DEFAULT 'auction_deposit'");
    }

    /**
     * Revert to previous enum definition.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE deposits MODIFY type ENUM('auction_deposit','topup','deduction','withdrawal') NOT NULL DEFAULT 'auction_deposit'");
    }
};
