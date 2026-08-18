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
        // French
        if (!Schema::hasColumn('categories', 'name_fr')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('name_fr')->nullable();
            });
        }

        if (!Schema::hasColumn('categories', 'description_fr')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('description_fr')->nullable();
            });
        }

        // English
        if (!Schema::hasColumn('categories', 'name_en')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('name_en')->nullable();
            });
        }

        if (!Schema::hasColumn('categories', 'description_en')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('description_en')->nullable();
            });
        }

        // Haitian Creole
        if (!Schema::hasColumn('categories', 'name_ht')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('name_ht')->nullable();
            });
        }

        if (!Schema::hasColumn('categories', 'description_ht')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('description_ht')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [
            'name_fr',
            'description_fr',
            'name_en',
            'description_en',
            'name_ht',
            'description_ht',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('categories', $column)) {
                Schema::table('categories', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};