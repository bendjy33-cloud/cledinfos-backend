<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('job_title_fr')->nullable()->after('photo');
            $table->string('job_title_en')->nullable()->after('job_title_fr');
            $table->string('job_title_ht')->nullable()->after('job_title_en');
            $table->string('job_title_es')->nullable()->after('job_title_ht');
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn([
                'job_title_fr',
                'job_title_en',
                'job_title_ht',
                'job_title_es',
            ]);
        });
    }
};