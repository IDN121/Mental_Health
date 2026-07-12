<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            if (!Schema::hasColumn('counselors', 'role')) {
                $table->enum('role', ['admin', 'karyawan'])->default('karyawan')->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('counselors', function (Blueprint $table) {
            if (Schema::hasColumn('counselors', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
