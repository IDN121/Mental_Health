<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mood_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('mood_logs', 'message_id')) {
                $table->foreignId('message_id')->nullable()->constrained('messages')->nullOnDelete()->after('anonymous_user_id');
            }
            if (!Schema::hasColumn('mood_logs', 'source')) {
                $table->string('source')->default('manual')->after('notes');
            }
            if (!Schema::hasColumn('mood_logs', 'mood_date')) {
                $table->date('mood_date')->nullable()->after('source');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mood_logs', function (Blueprint $table) {
            if (Schema::hasColumn('mood_logs', 'message_id')) {
                $table->dropForeign(['message_id']);
                $table->dropColumn('message_id');
            }
            if (Schema::hasColumn('mood_logs', 'source')) {
                $table->dropColumn('source');
            }
            if (Schema::hasColumn('mood_logs', 'mood_date')) {
                $table->dropColumn('mood_date');
            }
        });
    }
};
