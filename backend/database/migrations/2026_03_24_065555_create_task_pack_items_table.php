<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_pack_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_pack_id')
                ->constrained('task_packs')
                ->cascadeOnDelete();
            $table->foreignId('task_master_id')
                ->constrained('task_masters')
                ->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['task_pack_id', 'task_master_id']);
            $table->index('task_pack_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_pack_items');
    }
};
