<?php
// database/migrations/xxxx_create_user_task_progress_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('user_task_progress'); // ← 追加
        Schema::create('user_task_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('task_master_id')
                ->constrained('task_masters')
                ->cascadeOnDelete();
            $table->enum('status', ['todo', 'in_progress', 'done'])
                ->default('todo');
            $table->string('github_url')->nullable();
            $table->string('deploy_url')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // 同じユーザーが同じ課題を重複して持たないように
            $table->unique(['user_id', 'task_master_id']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_task_progress');
    }
};
