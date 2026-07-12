<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('anonymous_user_id')
                ->constrained('anonymous_users')
                ->cascadeOnDelete();
                
            $table->date('session_date');
            
            $table->text('summary')->nullable();
            
            $table->string('dominant_mood')->nullable();
            
            $table->enum('risk_level', [
                'LOW',
                'MEDIUM',
                'HIGH',
                'CRITICAL'
            ])->default('LOW');
            
            $table->integer('message_count')->default(0);

            $table->timestamps();
            
            // Ensures only one session per user per day
            $table->unique(['anonymous_user_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};
