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
            $table->enum('role', ['anggota', 'member', 'admin', 'owner'])->default('anggota')->after('email');
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->string('province', 100)->nullable()->after('address');
            $table->string('city', 100)->nullable()->after('province');
            $table->string('district', 100)->nullable()->after('city');
            $table->string('sub_district', 100)->nullable()->after('district');
            $table->string('postal_code', 10)->nullable()->after('sub_district');
            $table->string('avatar')->nullable()->after('postal_code');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->timestamp('verified_at')->nullable()->after('email_verified_at');
            $table->integer('weekly_post_count')->default(0)->after('is_active');
            $table->timestamp('last_post_reset')->nullable()->after('weekly_post_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'address', 'province', 'city', 
                'district', 'sub_district', 'postal_code', 'avatar', 
                'is_active', 'verified_at', 'weekly_post_count', 'last_post_reset'
            ]);
        });
    }
};
