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
        Schema::create('user_task_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();

            $table->enum('status', ['todo', 'in_review', 'done'])->default('todo')->comment('進捗状態');
            $table->unsignedTinyInteger('understanding_level')->nullable()->comment('理解度 1〜5');
            $table->timestamp('submitted_at')->nullable()->comment('提出日時');
            $table->timestamp('reviewed_at')->nullable()->comment('レビュー完了日時');
            $table->text('memo')->nullable()->comment('メモ');
            $table->timestamps();

            $table->unique(['user_id', 'task_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_task_progress');
    }
};