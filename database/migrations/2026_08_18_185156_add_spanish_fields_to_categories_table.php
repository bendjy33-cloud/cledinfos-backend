<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('categories', 'name_es')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('name_es')->nullable()->after('name_ht');
            });
        }

        if (!Schema::hasColumn('categories', 'description_es')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->text('description_es')->nullable()->after('description_ht');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('categories', 'name_es')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('name_es');
            });
        }

        if (Schema::hasColumn('categories', 'description_es')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('description_es');
            });
        }
    }
};