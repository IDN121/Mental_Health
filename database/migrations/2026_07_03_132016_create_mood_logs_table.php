<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mood_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('anonymous_user_id')
                ->constrained('anonymous_users')
                ->cascadeOnDelete();

            $table->enum('mood', [
                'Senang',
                'Sedih',
                'Marah',
                'Cemas',
                'Netral'
            ]);

            $table->string('emotion_label')->nullable();
            $table->float('confidence_score')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mood_logs');
    }
};