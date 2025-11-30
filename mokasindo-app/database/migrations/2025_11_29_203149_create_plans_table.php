<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // Silver, Gold, dll
            $table->string('slug')->unique();     // silver, gold
            $table->text('description')->nullable();
            $table->unsignedInteger('price');     // dalam rupiah
            $table->enum('billing_period', ['monthly', 'yearly']);
            $table->unsignedInteger('duration_days'); // misal 30, 365
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
