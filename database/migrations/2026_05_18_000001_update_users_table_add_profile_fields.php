<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student');
            $table->string('avatar')->nullable();
            $table->string('bio', 500)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('school')->nullable();
            $table->string('timezone', 100)->default('UTC');
            $table->string('language', 10)->default('en');
            $table->string('telegram_chat_id')->nullable();
            $table->boolean('dark_mode')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'avatar',
                'bio',
                'phone',
                'school',
                'timezone',
                'language',
                'telegram_chat_id',
                'dark_mode',
            ]);
        });
    }
};
