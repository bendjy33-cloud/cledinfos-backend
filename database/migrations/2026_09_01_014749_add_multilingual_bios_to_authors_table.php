<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->text('bio_fr')->nullable()->after('job_title');
            $table->text('bio_en')->nullable()->after('bio_fr');
            $table->text('bio_ht')->nullable()->after('bio_en');
            $table->text('bio_es')->nullable()->after('bio_ht');
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn([
                'bio_fr',
                'bio_en',
                'bio_ht',
                'bio_es',
            ]);
        });
    }
};