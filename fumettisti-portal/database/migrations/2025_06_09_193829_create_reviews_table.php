<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fumetto_id')->constrained('fumetti')->onDelete('cascade');
            $table->integer('rating')->unsigned();
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'fumetto_id']);
            $table->index(['fumetto_id', 'is_approved']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
