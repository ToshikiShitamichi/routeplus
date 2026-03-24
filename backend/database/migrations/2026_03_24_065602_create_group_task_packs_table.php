<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_task_packs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')
                ->constrained('groups')
                ->cascadeOnDelete();
            $table->foreignId('task_pack_id')
                ->constrained('task_packs')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['group_id', 'task_pack_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_task_packs');
    }
};
