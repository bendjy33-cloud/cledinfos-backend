<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Names
            $table->string('name_fr');
            $table->string('name_en')->nullable();
            $table->string('name_ht')->nullable();

            // Slug
            $table->string('slug')->unique();

            // Descriptions
            $table->text('description_fr')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ht')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};