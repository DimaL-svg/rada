<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Article;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Article::chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $content = html_entity_decode($article->content, ENT_QUOTES, 'UTF-8');
                $plainText = strip_tags($content);
                $seoDesc = mb_substr(trim(preg_replace('/\s+/', ' ', $plainText)), 0, 160);

                $article->update([
                    'seo_title' => $article->seo_title ?: $article->title,
                    'seo_desc'  => $article->seo_desc ?: $seoDesc,
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles_laravel', function (Blueprint $table) {
            //
        });
    }
};
