<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fumetti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->integer('issue_number');
            $table->year('publication_year');
            $table->string('cover_image')->nullable();
            $table->text('plot')->nullable();
            $table->foreignId('magazine_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'publication_year']);
            $table->index(['is_published', 'published_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fumetti');
    }
};
