<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('password');
            $table->string('avatar')->nullable()->after('role');
            $table->string('bio', 500)->nullable()->after('avatar');
            $table->string('phone', 20)->nullable()->after('bio');
            $table->string('school')->nullable()->after('phone');
            $table->string('timezone', 100)->default('UTC')->after('school');
            $table->string('language', 10)->default('en')->after('timezone');
            $table->string('telegram_chat_id')->nullable()->after('language');
            $table->boolean('dark_mode')->default(false)->after('telegram_chat_id');
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
