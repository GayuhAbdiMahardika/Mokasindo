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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('category', ['motor', 'mobil']);
            $table->string('brand'); // merk
            $table->string('model'); // tipe
            $table->year('year'); // tahun
            $table->string('color')->nullable();
            $table->string('license_plate')->nullable(); // plat nomor
            $table->integer('mileage')->nullable(); // km
            $table->text('description');
            $table->decimal('starting_price', 15, 2);
            $table->string('transmission')->nullable(); // manual/matic
            $table->string('fuel_type')->nullable(); // bensin/diesel/listrik
            $table->integer('engine_capacity')->nullable(); // cc
            $table->string('condition')->default('bekas'); // bekas/baru
            $table->string('province', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('sub_district', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'sold'])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['category', 'brand', 'status']);
            $table->index(['province', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
