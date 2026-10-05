<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->string('title_fr')->nullable()->after('title');
            $table->string('title_ht')->nullable()->after('title_fr');
            $table->string('title_en')->nullable()->after('title_ht');
            $table->string('title_es')->nullable()->after('title_en');
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn([
                'title_fr',
                'title_ht',
                'title_en',
                'title_es',
            ]);
        });
    }
};