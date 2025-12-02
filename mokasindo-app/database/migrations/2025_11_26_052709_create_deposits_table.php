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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'verifying', 'paid', 'expired', 'failed', 'refunded', 'forfeited'])->default('pending');
            $table->enum('type', ['auction_deposit', 'topup', 'deduction', 'withdrawal'])->default('auction_deposit');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('order_number')->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('payment_proof')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('forfeited_at')->nullable();
            $table->string('refund_status')->nullable();
            $table->decimal('refund_amount', 15, 2)->nullable();
            $table->decimal('forfeit_to_owner', 15, 2)->nullable();
            $table->decimal('forfeit_to_platform', 15, 2)->nullable();
            $table->timestamps();
            
            $table->index(['auction_id', 'user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
