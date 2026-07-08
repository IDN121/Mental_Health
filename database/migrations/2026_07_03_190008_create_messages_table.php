<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // ANONIM USER (tidak pakai nama asli)
            $table->unsignedBigInteger('anonymous_user_id');

            // isi chat
            $table->text('message');

            // siapa yang kirim (employee / counselor)
            $table->string('sender')->default('employee');

            // admin atau bukan
            $table->boolean('is_admin')->default(false);

            // AI ANALYSIS (nanti dipakai)
            $table->string('emotion')->nullable(); // happy, sad, stress
            $table->decimal('confidence', 5, 2)->nullable();

            // status baca
            $table->boolean('is_read')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};