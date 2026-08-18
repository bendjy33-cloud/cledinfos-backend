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
        $columns = [
            'meta_description',
            'meta_title',
            'keywords',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('posts', $column)) {
                Schema::table('posts', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('posts', 'meta_description')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->text('meta_description')->nullable();
            });
        }

        if (!Schema::hasColumn('posts', 'meta_title')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('meta_title')->nullable();
            });
        }

        if (!Schema::hasColumn('posts', 'keywords')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->text('keywords')->nullable();
            });
        }
    }
};