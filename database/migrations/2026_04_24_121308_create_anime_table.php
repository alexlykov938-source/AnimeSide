<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anime', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();               // для URL: /anime/naruto
            $table->string('genre');                        // жанр
            $table->string('type')->nullable();             // сериал, фильм, OVA
            $table->integer('episodes')->default(0);        // количество серий
            $table->integer('year')->nullable();            // год выхода
            $table->string('studio')->nullable();           // студия
            $table->decimal('rating', 3, 2)->default(0);    // рейтинг 0.00 - 10.00
            $table->text('description')->nullable();        // описание
            $table->string('image')->nullable();            // путь к картинке
            $table->string('status')->default('ongoing');   // ongoing, completed, announced
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animes');
    }
};
