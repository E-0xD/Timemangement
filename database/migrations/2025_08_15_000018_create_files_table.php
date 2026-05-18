<?php

use App\Enums\FileType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->string('original_name');
            $table->string('filename');
            $table->string('path');
            $table->string('mime_type', 100);
            $table->string('file_type', 10)->default(FileType::Other->value);
            $table->unsignedBigInteger('size');
            $table->timestamps();

            $table->index(['user_id', 'course_id']);
            $table->index(['user_id', 'file_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
