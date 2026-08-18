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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // French
            $table->string('title_fr');
            $table->longText('content_fr');
            $table->text('meta_description_fr')->nullable();
            $table->string('keywords_fr')->nullable();

            // English
            $table->string('title_en')->nullable();
            $table->longText('content_en')->nullable();
            $table->text('meta_description_en')->nullable();
            $table->string('keywords_en')->nullable();

            // Haitian Creole
            $table->string('title_ht')->nullable();
            $table->longText('content_ht')->nullable();
            $table->text('meta_description_ht')->nullable();
            $table->string('keywords_ht')->nullable();

            // SEO
            $table->string('meta_title')->nullable();

            // General
            $table->string('slug')->unique();

            // Images
            $table->string('image')->nullable();
            $table->string('image_url')->nullable();

            // Relationships
            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            // Status and statistics
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};