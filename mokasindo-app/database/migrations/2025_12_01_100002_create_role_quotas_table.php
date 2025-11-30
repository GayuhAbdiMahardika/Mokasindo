<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_quotas', function (Blueprint $table) {
            $table->id();
            $table->string('role')->unique();
            $table->integer('post_limit')->default(5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_quotas');
    }
};
