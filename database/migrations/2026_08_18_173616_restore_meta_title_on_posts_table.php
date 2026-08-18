<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('posts', 'meta_title')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->string('meta_title')->nullable()->after('keywords_ht');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('posts', 'meta_title')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('meta_title');
            });
        }
    }
};