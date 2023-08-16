<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use voku\helper\HtmlDomParser;

class ParseNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse news from a website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = "https://cryptorank.io/news";

        $response = file_get_contents($url);

        if ($response !== false) {
            $dom = HtmlDomParser::str_get_html($response);
            
            $news_element = $dom->findOne('#84457054');

            $this->info($news_element);

            if ($news_element) {
                $time = $news_element->findOne('.sc-ac6d7642-1 gTjTyc')->text();

                $link = $news_element->findOne('a')->getAttribute('href');

                $title = $news_element->findOne('.sc-ac6d7642-2')->text();

                $description = $news_element->findOne('.sc-ac6d7642-8')->text();

                $this->info("Time: " . $time);
                $this->info("Url: " . $link);
                $this->info("Title: " . $title);
                $this->info("Content: " . $description);

                $this->line(str_repeat("=", 50));
            } else {
                $this->error("404");
            }
        } else {
            $this->error("error");
        }
    }
}
