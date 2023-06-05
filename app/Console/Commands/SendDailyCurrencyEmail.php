<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Currency;
use App\Models\CurrencyHistory;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyCryptoEmail;

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
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            $selectedCurrencies = $user->currencies()->pluck('currency_id')->toArray();
            $currenciesData = CurrencyHistory::analyzeCurrencyTrend($selectedCurrencies);

            Mail::to($user->email)->send(new DailyCryptoEmail($user, $currenciesData, $selectedCurrencies));
            sleep(1);
        }

        $this->info('Daily crypto emails sent successfully.');
    }
}
