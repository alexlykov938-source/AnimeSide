<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anime_id')->constrained('anime')->cascadeOnDelete();
            $table->integer('season')->default(1);
            $table->integer('episode_number');
            $table->string('title')->nullable();
            $table->string('video_url');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['anime_id', 'season', 'episode_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};