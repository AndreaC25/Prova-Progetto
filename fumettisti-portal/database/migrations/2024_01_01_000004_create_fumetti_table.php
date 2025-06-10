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
            $table->foreignId('magazine_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->text('plot');
            $table->integer('issue_number');
            $table->integer('publication_year');
            $table->decimal('price', 8, 2)->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'published_at']);
            $table->index(['user_id', 'is_published']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fumetti');
    }
};
