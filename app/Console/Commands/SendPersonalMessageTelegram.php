<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UserService;
use App\Services\CurrencyService;
use App\Services\CurrencyHistoryService;

class SendPersonalMessageTelegram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:personal_message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send personal messages with cryptocurrencies';

    /**
     * The UserService instance.
     *
     * @var UserService
     */
    private $userService;

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
        UserService $userService,
        CurrencyService $currencyService,
        CurrencyHistoryService $currencyHistoryService
    ) {
        parent::__construct();
        $this->userService = $userService;
        $this->currencyService = $currencyService;
        $this->currencyHistoryService = $currencyHistoryService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = $this->userService->getUsersTelegramId();

        $botToken = env('TELEGRAM_BOT_TOKEN');

        foreach ($users as $user) {
            $selectedCurrencies = $user->currencies()->pluck('currency_id')->toArray();
            $currenciesData = $this->currencyHistoryService->analyzeCurrencyTrend($selectedCurrencies);

            $idChannel = $user['telegram_Id'];
            
            $message = $user['first_name'] . " " . $user['last_name'] . " daily trends: ";

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

        $this->info('Personal messages sent successfully.');
    }
}
