<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        DB::statement('
            ALTER TABLE ads
            ADD COLUMN IF NOT EXISTS post_id BIGINT NULL,
            ADD COLUMN IF NOT EXISTS title_fr VARCHAR(255) NULL,
            ADD COLUMN IF NOT EXISTS title_ht VARCHAR(255) NULL,
            ADD COLUMN IF NOT EXISTS title_en VARCHAR(255) NULL,
            ADD COLUMN IF NOT EXISTS title_es VARCHAR(255) NULL
        ');
    }

    public function down(): void
    {
        DB::statement('
            ALTER TABLE ads
            DROP COLUMN IF EXISTS post_id,
            DROP COLUMN IF EXISTS title_fr,
            DROP COLUMN IF EXISTS title_ht,
            DROP COLUMN IF EXISTS title_en,
            DROP COLUMN IF EXISTS title_es
        ');
    }
};