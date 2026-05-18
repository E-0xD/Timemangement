<?php

use App\Enums\GroupRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_group_id')->constrained('study_groups')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 10)->default(GroupRole::Member->value);
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['study_group_id', 'user_id']);
            $table->index('study_group_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_group_members');
    }
};
