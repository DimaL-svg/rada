<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Генерація файлу sitemap.xml';

    public function handle()
    {
        $this->info('Генерація мапи сайту...');

        // Створюємо об'єкт мапи сайту
        $sitemap = Sitemap::create();

        // Додаємо головну сторінку
        $sitemap->add(Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // Додаємо статті.
        Article::where('is_active', true)->get()->each(function (Article $article) use ($sitemap) {
            $sitemap->add(
                Url::create("/articles/{$article->slug}")
                    ->setLastModificationDate($article->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Готово! Файл sitemap.xml створено у папці public.');
    }
}