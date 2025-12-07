<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->unsignedBigInteger('deposit_id')->nullable()->after('user_id');
            $table->foreign('deposit_id')->references('id')->on('deposits')->nullOnDelete();
        });

        Schema::table('deposits', function (Blueprint $table) {
            $table->unsignedBigInteger('bid_id')->nullable()->after('auction_id');
            $table->string('snap_token')->nullable()->after('transaction_code');
            $table->string('payment_url')->nullable()->change();
            $table->string('snap_redirect_url')->nullable()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropForeign(['deposit_id']);
            $table->dropColumn('deposit_id');
        });

        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn(['bid_id', 'snap_token', 'snap_redirect_url']);
            // payment_url column change rollback skipped intentionally
        });
    }
};
