<?php
// database/migrations/xxxx_create_task_masters_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_masters', function (Blueprint $table) {
            $table->id();
            $table->string('category');                    // フロントエンド/サーバーサイド/インフラ
            $table->unsignedTinyInteger('order');          // 表示順（1〜30）
            $table->unsignedTinyInteger('level');          // レベル（1〜5）
            $table->string('title');
            $table->text('description');
            $table->timestamps();

            $table->index(['category', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_masters');
    }
};
