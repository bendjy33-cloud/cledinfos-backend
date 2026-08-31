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
        Schema::table('posts', function (Blueprint $table) {
            $table->text('subtitle_fr')->nullable()->after('title_fr');
            $table->text('subtitle_ht')->nullable()->after('title_ht');
            $table->text('subtitle_en')->nullable()->after('title_en');
            $table->text('subtitle_es')->nullable()->after('title_es');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'subtitle_fr',
                'subtitle_ht',
                'subtitle_en',
                'subtitle_es',
            ]);
        });
    }
};