<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('score'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();

            // Un usuario solo puede valorar una película una vez
            $table->unique(['user_id', 'movie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
