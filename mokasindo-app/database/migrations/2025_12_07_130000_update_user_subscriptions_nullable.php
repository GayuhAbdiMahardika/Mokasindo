<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            // Make plan nullable and allow null on delete
            if (Schema::hasColumn('user_subscriptions', 'subscription_plan_id')) {
                $table->unsignedBigInteger('subscription_plan_id')->nullable()->change();
            }

            // Allow nullable start/end dates for auto-generated stubs
            if (Schema::hasColumn('user_subscriptions', 'start_date')) {
                $table->dateTime('start_date')->nullable()->change();
            }
            if (Schema::hasColumn('user_subscriptions', 'end_date')) {
                $table->dateTime('end_date')->nullable()->change();
            }

            // Price can be nullable; default to 0
            if (Schema::hasColumn('user_subscriptions', 'price_paid')) {
                $table->decimal('price_paid', 10, 2)->nullable()->default(0)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('user_subscriptions', 'subscription_plan_id')) {
                $table->unsignedBigInteger('subscription_plan_id')->nullable(false)->change();
            }
            if (Schema::hasColumn('user_subscriptions', 'start_date')) {
                $table->dateTime('start_date')->nullable(false)->change();
            }
            if (Schema::hasColumn('user_subscriptions', 'end_date')) {
                $table->dateTime('end_date')->nullable(false)->change();
            }
            if (Schema::hasColumn('user_subscriptions', 'price_paid')) {
                $table->decimal('price_paid', 10, 2)->nullable(false)->default(null)->change();
            }
        });
    }
};
