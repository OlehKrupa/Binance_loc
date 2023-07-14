<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyCryptoEmail;

use App\Services\UserService;
use App\Services\CurrencyService;
use App\Services\CurrencyHistoryService;

class Mailer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    protected $currenciesData;
    protected $selectedCurrencies;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     */

    /**
     * Create a new command instance.
     *
     */
    public function __construct($user, $currenciesData, $selectedCurrencies)
    {
        $this->user = $user;
        $this->currenciesData = $currenciesData;
        $this->selectedCurrencies = $selectedCurrencies;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new DailyCryptoEmail($this->user, $this->currenciesData, $this->selectedCurrencies));
    }
}
