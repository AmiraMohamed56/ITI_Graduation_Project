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
            $table->string('phone')->nullable()->after('email');
            $table->enum('role', ['patient', 'doctor', 'admin'])->default('patient')->after('password');
            $table->string('profile_pic')->nullable()->after('role');
            $table->enum('status', ['active', 'suspended'])->default('active')->after('profile_pic');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone','role','profile_pic','status']);
        });
    }
};
