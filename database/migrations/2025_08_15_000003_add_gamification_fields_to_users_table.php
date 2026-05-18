<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete()->after('school');
            $table->unsignedInteger('xp_points')->default(0)->after('dark_mode');
            $table->unsignedInteger('study_streak')->default(0)->after('xp_points');
            $table->date('last_study_date')->nullable()->after('study_streak');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'xp_points', 'study_streak', 'last_study_date']);
        });
    }
};
