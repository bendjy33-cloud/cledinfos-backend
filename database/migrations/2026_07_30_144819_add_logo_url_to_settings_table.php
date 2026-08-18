<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('settings', 'logo_url')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->text('logo_url')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', 'logo_url')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('logo_url');
            });
        }
    }
};