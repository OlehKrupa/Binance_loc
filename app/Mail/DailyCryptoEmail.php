<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Currency;
use App\Models\CurrencyHistory;

class DailyCryptoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $currencies;
    public $currenciesData;

    public function __construct(User $user, $currencies, $currenciesData)
    {
        $this->user = $user;
        $this->currencies = $currencies;
        $this->currenciesData = $currenciesData;
    }

    public function build()
    {
        return $this->view('emails.daily_crypto')->subject('Daily Crypto Report');
    }
}
