<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CurrencyService;
use App\Services\CurrencyHistoryService;

class SendDailyCurrencyTelegram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:daily-crypto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily crypto into telegram';

    /**
     * The CurrencyService instance.
     *
     * @var CurrencyService
     */
    private $currencyService;

    /**
     * The CurrencyHistoryService instance.
     *
     * @var CurrencyHistoryService
     */
    private $currencyHistoryService;

    /**
     * Create a new command instance.
     *
     * @param CurrencyService $currencyService
     * @param CurrencyHistoryService $currencyHistoryService
     */
    public function __construct(
        CurrencyService $currencyService,
        CurrencyHistoryService $currencyHistoryService
    ) {
        parent::__construct();
        $this->currencyService = $currencyService;
        $this->currencyHistoryService = $currencyHistoryService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //Telegramm channel ID
        $idChannel = env('TELEGRAM_CHANNEL');
        //Bot token
        $botToken = env('TELEGRAM_BOT_TOKEN');

        $selectedCurrencies = $this->currencyService->getAllCurrenciesId();
        $currenciesData = $this->currencyHistoryService->analyzeCurrencyTrend($selectedCurrencies);

        $message = "Currencies daily trends: ";

        foreach ($selectedCurrencies as $currencyId) {
            $message .= "\n";
            $message .= strval($currenciesData[$currencyId]["name"]);
            $message .= "\t";
            $message .= strval(round($currenciesData[$currencyId]["trend"], 2, PHP_ROUND_HALF_UP)) . "%";
            $message .= "\t";
            if ($currenciesData[$currencyId]["trend"] > 0) {
                $message .= "↑";
            } else
                $message .= "↓";
        }

        //encode to preserve line breaks
        $message = urlencode($message);
        //Try send
        try {
            file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$idChannel&text=" . $message);
        } catch (\Exception $e) {
        }
    }
}
