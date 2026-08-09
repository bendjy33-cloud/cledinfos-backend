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
        Schema::table('categories', function (Blueprint $table) {
            // French
            $table->string('name_fr')->nullable();
            $table->text('description_fr')->nullable();

            // English
            $table->string('name_en')->nullable();
            $table->text('description_en')->nullable();

            // Haitian Creole
            $table->string('name_ht')->nullable();
            $table->text('description_ht')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'name_fr',
                'description_fr',
                'name_en',
                'description_en',
                'name_ht',
                'description_ht',
            ]);
        });
    }
};