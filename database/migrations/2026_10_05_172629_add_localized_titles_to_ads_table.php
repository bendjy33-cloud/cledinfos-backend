<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'title_fr',
            'title_ht',
            'title_en',
            'title_es',
        ];

        foreach ($columns as $column) {
            if (! Schema::hasColumn('ads', $column)) {
                Schema::table('ads', function (Blueprint $table) use ($column) {
                    $table->string($column)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        $columns = [
            'title_fr',
            'title_ht',
            'title_en',
            'title_es',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('ads', $column)) {
                Schema::table('ads', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};