<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('title_es')->nullable()->after('title_ht');
            $table->longText('content_es')->nullable()->after('content_ht');
            $table->text('meta_description_es')->nullable()->after('meta_description_ht');
            $table->string('keywords_es')->nullable()->after('keywords_ht');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'title_es',
                'content_es',
                'meta_description_es',
                'keywords_es',
            ]);
        });
    }
};