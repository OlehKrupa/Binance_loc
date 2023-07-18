<?php

namespace App\Console\Commands;

use App\Jobs\Mailer;
use Illuminate\Console\Command;
use App\Services\UserService;
use App\Services\CurrencyHistoryService;

class SendDailyCurrencyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:daily-crypto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily crypto emails to users';

    /**
     * The UserService instance.
     *
     * @var UserService
     */
    private $userService;

    /**
     * The CurrencyHistoryService instance.
     *
     * @var CurrencyHistoryService
     */
    private $currencyHistoryService;

    /**
     * Create a new command instance.
     *
     * @param UserService $userService
     * @param CurrencyHistoryService $currencyHistoryService
     */
    public function __construct(
        UserService $userService,
        CurrencyHistoryService $currencyHistoryService
    ) {
        parent::__construct();
        $this->userService = $userService;
        $this->currencyHistoryService = $currencyHistoryService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the subscribed users from the UserService
        $users = $this->userService->getSubscribedUsers();

        foreach ($users as $user) {
            // Get the selected currencies for the user
            $selectedCurrencies = $user->currencies()->pluck('currency_id')->toArray();

            // Analyze the currency trend for the selected currencies using the CurrencyHistoryService
            $currenciesData = $this->currencyHistoryService->analyzeCurrencyTrend($selectedCurrencies);

            // Dispatch the Mailer job to send the email to the user
            Mailer::dispatch($user, $currenciesData, $selectedCurrencies)->delay(now()->addSeconds(2));
        }

        $this->info('Daily crypto emails sent successfully.');
    }
}