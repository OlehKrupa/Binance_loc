<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Telegram
{
    protected $http;
    const url = 'https://api.telegram.org/bot';

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    public function sendMessage($chatId, $message)
    {
        $this->http::post(self::url . env('TELEGRAM_BOT_TOKEN') . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'html'
        ]);
    }

    public function sendButtons($chatId, $message, $button)
    {
        $this->http::post(self::url . env('TELEGRAM_BOT_TOKEN') . '/sendMessage', [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'html',
            'reply_markup' => $button
        ]);
    }
}
