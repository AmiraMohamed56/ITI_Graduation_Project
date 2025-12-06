<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // الـ admin أو المستخدم اللي عمل الحدث
            $table->string('action'); // create, update, delete, login, logout
            $table->string('model');  // الموديل المتأثر
            $table->unsignedBigInteger('model_id')->nullable(); // رقم الـ record المتأثر
            $table->text('description')->nullable(); // وصف الحدث
            $table->string('ip_address')->nullable(); // ip address
            $table->timestamps();

            $table->index('user_id');
            $table->index('model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logs');
    }
};
