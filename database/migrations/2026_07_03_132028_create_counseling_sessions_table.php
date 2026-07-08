<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('counseling_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('anonymous_user_id')
                ->constrained('anonymous_users')
                ->cascadeOnDelete();

            $table->foreignId('counselor_id')
                ->constrained('counselors')
                ->cascadeOnDelete();

            $table->enum('status',[
                'Aktif',
                'Selesai'
            ])->default('Aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_sessions');
    }
};