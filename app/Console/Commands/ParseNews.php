<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Http;
use App\Services\NewsService;

class ParseNews extends Command
{
    protected $signature = 'parse:news';
    protected $description = 'Parse news from a website';

    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function handle()
    {
        $url = "https://decrypt.co";

        $response = Http::get($url . "/news");

        if (!$response->ok()) {
            $this->error("Error: Unable to retrieve content from the URL");
            return;
        }

        $content = $response->body();
        $dom = HtmlDomParser::str_get_html($content);

        $news_elements = $dom->find('article');
        $processedUrls = [];

        $allowedCategories = ['Business', 'Coins', 'NFTs', 'Artificial Intelligence'];

        foreach ($news_elements as $news_element) {
            $category_element = $news_element->findOneOrFalse('p.text-cc-pink-2');
            $category = $category_element ? trim($category_element->text()) : '';

            if (!in_array($category, $allowedCategories)) {
                continue;
            }

            $title_element = $news_element->findOneOrFalse('h3 a');
            $title = $title_element ? trim($title_element->text()) : '';

            $content_element = $news_element->findOneOrFalse('p.mt-1');
            $content = $content_element ? trim($content_element->text()) : '';

            $image_elements = $news_element->find('img');
            $imageSrc = '';

            if (!empty($image_elements) && count($image_elements) >= 2) {
                $second_image_element = $image_elements[1];
                $imageSrc = $second_image_element->getAttribute('src');
            }

            $source_element = $news_element->findOneOrFalse('h3 a');
            $sourceUrl = $source_element ? $source_element->getAttribute('href') : '';

            if (in_array($sourceUrl, $processedUrls)) {
                continue;
            }

            $processedUrls[] = $sourceUrl;

            if ($category && $title && $content && $imageSrc && $sourceUrl) {
                $existingNews = $this->newsService->findBySourceUrl($url . $sourceUrl);
                if (!$existingNews) {
                    $this->newsService->create([
                        'title' => $title,
                        'content' => $content,
                        'image' => $imageSrc,
                        'source' => $url . $sourceUrl,
                        'category' => $category,
                        'published_at' => now(),
                    ]);
                }
            }
        }

        $this->info("News parsing and saving to the database complete.");
    }
}