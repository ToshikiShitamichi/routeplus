<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->string('label')->nullable()->after('token'); // URLの名前
            $table->dropColumn('email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('invitation_id')
                ->nullable()
                ->after('organization_id')
                ->constrained('invitations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn('label');
            $table->string('email')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['invitation_id']);
            $table->dropColumn('invitation_id');
        });
    }
};
