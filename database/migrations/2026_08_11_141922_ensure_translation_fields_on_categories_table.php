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
            if (!Schema::hasColumn('categories', 'name_fr')) {
                $table->string('name_fr')->nullable();
            }

            if (!Schema::hasColumn('categories', 'description_fr')) {
                $table->text('description_fr')->nullable();
            }

            if (!Schema::hasColumn('categories', 'name_en')) {
                $table->string('name_en')->nullable();
            }

            if (!Schema::hasColumn('categories', 'description_en')) {
                $table->text('description_en')->nullable();
            }

            if (!Schema::hasColumn('categories', 'name_ht')) {
                $table->string('name_ht')->nullable();
            }

            if (!Schema::hasColumn('categories', 'description_ht')) {
                $table->text('description_ht')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
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
                    $table->dropColumn($column);
                }
            }
        });
    }
};