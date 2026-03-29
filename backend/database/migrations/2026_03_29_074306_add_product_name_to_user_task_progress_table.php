<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_task_progress', function (Blueprint $table) {
            $table->string('product_name')->nullable()->after('deploy_url');
        });
    }

    public function down(): void
    {
        Schema::table('user_task_progress', function (Blueprint $table) {
            $table->dropColumn('product_name');
        });
    }
};
