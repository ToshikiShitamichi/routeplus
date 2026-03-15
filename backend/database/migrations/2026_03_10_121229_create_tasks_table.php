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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roadmap_id')->constrained()->cascadeOnDelete();
            $table->string('task_code')->unique()->comment('fe-1 などの表示用コード');
            $table->string('title')->comment('課題名');
            $table->text('description')->nullable()->comment('課題説明');
            $table->unsignedInteger('difficulty')->default(1)->comment('難易度 1〜5想定');
            $table->unsignedInteger('sort_order')->default(0)->comment('ロードマップ内の表示順');
            $table->boolean('is_recommended')->default(false)->comment('初期おすすめ表示用');
            $table->boolean('is_active')->default(true)->comment('有効フラグ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};