<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mood_logs', function (Blueprint $table) {
            // Ubah tipe mood jadi string
            $table->string('mood')->change();
            
            if (!Schema::hasColumn('mood_logs', 'notes')) {
                $table->text('notes')->nullable()->after('mood');
            }
        });
    }

    public function down(): void
    {
        Schema::table('mood_logs', function (Blueprint $table) {
            if (Schema::hasColumn('mood_logs', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
