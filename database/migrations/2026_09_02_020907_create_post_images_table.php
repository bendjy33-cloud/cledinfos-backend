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
        Schema::create('post_images', function (Blueprint $table) {
            $table->id();

            // Article
            $table->foreignId('post_id')
                ->constrained('posts')
                ->cascadeOnDelete();

            // Image
            $table->string('image')->nullable();
            $table->string('image_url')->nullable();

            // Order of images in the gallery
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['post_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_images');
    }
};