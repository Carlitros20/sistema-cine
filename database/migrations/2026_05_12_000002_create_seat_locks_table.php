<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_session_id')->constrained()->onDelete('cascade');
            $table->string('seat');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // La reserva temporal expira a los 10 minutos
            $table->timestamp('expires_at');
            $table->timestamps();

            // Una butaca solo puede tener un bloqueo activo a la vez
            $table->unique(['movie_session_id', 'seat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_locks');
    }
};
