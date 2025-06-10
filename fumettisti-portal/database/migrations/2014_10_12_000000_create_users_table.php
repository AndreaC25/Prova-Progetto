<?php
// database/migrations/2014_10_12_000000_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

// database/migrations/2024_01_01_000001_create_profiles_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('display_name')->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('website')->nullable();
            $table->json('social_links')->nullable();
            $table->string('location')->nullable();
            $table->date('birth_date')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};

// database/migrations/2024_01_01_000002_create_categories_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};

// database/migrations/2024_01_01_000003_create_magazines_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('magazines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('magazines');
    }
};

// database/migrations/2024_01_01_000004_create_fumettis_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('fumettis', function (Blueprint $table) {
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
        Schema::dropIfExists('fumettis');
    }
};

// database/migrations/2024_01_01_000005_create_fumetto_categories_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('fumetto_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fumetto_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['fumetto_id', 'category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fumetto_categories');
    }
};

// database/migrations/2024_01_01_000006_create_reviews_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fumetto_id')->constrained()->onDelete('cascade');
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

// database/migrations/2024_01_01_000007_create_favorites_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('fumetto_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'fumetto_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};

// database/migrations/2024_01_01_000008_create_contacts_table.php
return new class extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['pending', 'answered', 'closed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
