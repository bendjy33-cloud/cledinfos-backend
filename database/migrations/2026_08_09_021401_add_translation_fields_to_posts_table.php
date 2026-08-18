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
        // Titles
        if (!Schema::hasColumn('posts', 'title_fr')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('title_fr')->nullable()->after('title');
            });
        }

        if (!Schema::hasColumn('posts', 'title_en')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('title_en')->nullable()->after('title_fr');
            });
        }

        if (!Schema::hasColumn('posts', 'title_ht')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('title_ht')->nullable()->after('title_en');
            });
        }

        // Content
        if (!Schema::hasColumn('posts', 'content_fr')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->longText('content_fr')->nullable()->after('content');
            });
        }

        if (!Schema::hasColumn('posts', 'content_en')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->longText('content_en')->nullable()->after('content_fr');
            });
        }

        if (!Schema::hasColumn('posts', 'content_ht')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->longText('content_ht')->nullable()->after('content_en');
            });
        }

        // Meta descriptions
        if (!Schema::hasColumn('posts', 'meta_description_fr')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->text('meta_description_fr')->nullable()->after('content_ht');
            });
        }

        if (!Schema::hasColumn('posts', 'meta_description_en')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->text('meta_description_en')->nullable()->after('meta_description_fr');
            });
        }

        if (!Schema::hasColumn('posts', 'meta_description_ht')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->text('meta_description_ht')->nullable()->after('meta_description_en');
            });
        }

        // Keywords
        if (!Schema::hasColumn('posts', 'keywords_fr')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('keywords_fr')->nullable()->after('meta_description_ht');
            });
        }

        if (!Schema::hasColumn('posts', 'keywords_en')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('keywords_en')->nullable()->after('keywords_fr');
            });
        }

        if (!Schema::hasColumn('posts', 'keywords_ht')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('keywords_ht')->nullable()->after('keywords_en');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [
            'title_fr',
            'title_en',
            'title_ht',
            'content_fr',
            'content_en',
            'content_ht',
            'meta_description_fr',
            'meta_description_en',
            'meta_description_ht',
            'keywords_fr',
            'keywords_en',
            'keywords_ht',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('posts', $column)) {
                Schema::table('posts', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};