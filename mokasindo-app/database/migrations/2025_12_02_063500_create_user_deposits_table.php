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
        Schema::create('user_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_code')->unique(); // DEP-xxx or WD-xxx
            $table->enum('type', ['topup', 'withdrawal', 'refund', 'deduction'])->default('topup');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->string('payment_method')->nullable(); // bank_transfer, e_wallet, qris, credit_card
            $table->text('payment_instructions')->nullable(); // JSON instructions for user
            $table->string('payment_proof')->nullable(); // uploaded proof file path
            $table->string('bank_name')->nullable(); // for withdrawal
            $table->string('account_number')->nullable(); // for withdrawal
            $table->string('account_holder')->nullable(); // for withdrawal
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete(); // admin who verified
            $table->timestamp('verified_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'status']);
            $table->index('transaction_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_deposits');
    }
};
