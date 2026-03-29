<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_masters', function (Blueprint $table) {
            $table->boolean('is_official')->default(true)->after('description');
            $table->enum('visibility', ['private', 'public', 'official'])->default('official')->after('is_official');
            $table->unsignedBigInteger('organization_id')->nullable()->after('visibility');
            $table->unsignedBigInteger('created_by')->nullable()->after('organization_id');

            $table->foreign('organization_id')->references('id')->on('organizations')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('task_masters', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn(['is_official', 'visibility', 'organization_id', 'created_by']);
        });
    }
};
