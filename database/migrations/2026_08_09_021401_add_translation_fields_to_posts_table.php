<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { 
    
    /** * Run the migrations. */

    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {

              // Titles
            $table->string('title_fr')->nullable()->after('title');
            $table->string('title_en')->nullable()->after('title_fr');
            $table->string('title_ht')->nullable()->after('title_en');
                    

            // Content
            $table->longText('content_fr')->nullable()->after('content');
            $table->longText('content_en')->nullable()->after('content_fr');
            $table->longText('content_ht')->nullable()->after('content_en');
            
            // Meta descriptions
            $table->text('meta_description_fr')->nullable()->after('content_ht');
            $table->text('meta_description_en')->nullable()->after('meta_description_fr');
            $table->text('meta_description_ht')->nullable()->after('meta_description_en');
            
            // Keywords
            $table->string('keywords_fr')->nullable()->after('meta_description_ht');
            $table->string('keywords_en')->nullable()->after('keywords_fr');
            $table->string('keywords_ht')->nullable()->after('keywords_en');
             }); 
        }
   
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
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
            ]); 
        });
    }
};