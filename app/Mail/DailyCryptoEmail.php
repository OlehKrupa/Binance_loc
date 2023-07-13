<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class DailyCryptoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $currenciesData;
    public $selectedCurrencies;

    public function __construct(User $user, $currenciesData, $selectedCurrencies)
    {
        $this->user = $user;
        $this->currenciesData = $currenciesData;
        $this->selectedCurrencies = $selectedCurrencies;
    }

    public function build()
    {
        return $this->view('emails.daily_crypto')->subject('Daily Crypto Report');
    }
}
