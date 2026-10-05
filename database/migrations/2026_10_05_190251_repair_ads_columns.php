<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('ads', 'post_id')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->unsignedBigInteger('post_id')
                    ->nullable()
                    ->after('position');
            });
        }

        if (! Schema::hasColumn('ads', 'title_fr')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('title_fr')->nullable()->after('title');
            });
        }

        if (! Schema::hasColumn('ads', 'title_ht')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('title_ht')->nullable()->after('title_fr');
            });
        }

        if (! Schema::hasColumn('ads', 'title_en')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('title_en')->nullable()->after('title_ht');
            });
        }

        if (! Schema::hasColumn('ads', 'title_es')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('title_es')->nullable()->after('title_en');
            });
        }
    }

    public function down(): void
    {
        $columns = [
            'post_id',
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