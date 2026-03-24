<?php
// database/migrations/xxxx_create_invitations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('invited_by')           // 招待した管理者のuser_id
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('token')->unique();         // 招待トークン（ランダム文字列）
            $table->string('email')->nullable();       // 特定の宛先（任意）
            $table->timestamp('expires_at');           // 有効期限
            $table->timestamp('used_at')->nullable();  // 使用済み日時
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
