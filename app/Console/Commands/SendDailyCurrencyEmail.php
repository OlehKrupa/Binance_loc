<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyCryptoEmail;
use App\Models\User;
use App\Models\Currency;
use App\Models\CurrencyHistory;

use App\Services\UserService;
use App\Services\CurrencyService;
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
     * @param UserService $userService
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
        $users = $this->userService->getSubscribedUsers();

        foreach ($users as $user) {
            $selectedCurrencies = $user->currencies()->pluck('currency_id')->toArray();
            $currenciesData = $this->currencyHistoryService->analyzeCurrencyTrend($selectedCurrencies);

            Mail::to($user->email)->send(new DailyCryptoEmail($user, $currenciesData, $selectedCurrencies));
            sleep(2);
        }

        $this->info('Daily crypto emails sent successfully.');
    }

}
