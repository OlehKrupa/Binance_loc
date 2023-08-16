<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Http;

class ParseNews extends Command
{
    protected $signature = 'app:parse-news';
    protected $description = 'Parse news from a website';

    public function handle()
    {
        $url = "https://decrypt.co/news";

        $response = Http::get($url);

        if ($response->ok()) {
            $content = $response->body();
            $dom = HtmlDomParser::str_get_html($content);

            $news_elements = $dom->find('article');
            $this->info($news_elements);
            dd(1);
            $parsedNews = [];

            foreach ($news_elements as $news_element) {
                $category_element = $news_element->findOneOrFalse('p.text-cc-pink-2');
                $category = $category_element ? trim($category_element->text()) : '';

                $title_element = $news_element->findOneOrFalse('h3 a');
                $title = $title_element ? trim($title_element->text()) : '';

                $description_element = $news_element->findOneOrFalse('p.mt-1');
                $description = $description_element ? trim($description_element->text()) : '';

                $image_element = $news_element->findOneOrFalse('img');
                $imageSrc = $image_element ? $image_element->getAttribute('src') : '';
                $this->info($imageSrc);

                $source_element = $news_element->findOneOrFalse('h3 a');
                $sourceUrl = $source_element ? $source_element->getAttribute('href') : '';

                if ($category && $title && $description && $imageSrc && $sourceUrl) {
                    $parsedNews[] = [
                        'category' => $category,
                        'title' => $title,
                        'description' => $description,
                        'image' => $imageSrc,
                        'sourceUrl' => $sourceUrl,
                    ];
                }
            }

            foreach ($parsedNews as $news) {
                $this->info("Category: " . $news['category']);
                $this->info("Title: " . $news['title']);
                $this->info("Description: " . $news['description']);
                $this->info("Image: " . $news['image']);
                $this->info("Source URL: " . $news['sourceUrl']);

                $this->line(str_repeat("=", 50));
            }
        } else {
            $this->error("Error: Unable to retrieve content from the URL");
        }
    }
}
