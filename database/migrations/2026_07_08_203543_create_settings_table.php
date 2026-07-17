<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {

            $table->id();

            $table->string('site_name')->default("Clé d'Infos");

            $table->string('logo')->nullable();

            $table->string('email')->nullable();

            $table->string('phone')->nullable();

            $table->string('address')->nullable();

            $table->string('facebook')->nullable();

            $table->string('instagram')->nullable();

            $table->string('youtube')->nullable();

            $table->string('twitter')->nullable();

            $table->text('about')->nullable();

            $table->string('breaking_news')->nullable();

            $table->boolean('breaking_active')->default(true);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};