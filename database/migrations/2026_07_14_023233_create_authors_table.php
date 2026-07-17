<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('slug')->unique();

            $table->string('photo')->nullable();

            $table->string('job_title')->nullable();

            $table->text('bio')->nullable();

            $table->string('facebook')->nullable();

            $table->string('twitter')->nullable();

            $table->string('linkedin')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};