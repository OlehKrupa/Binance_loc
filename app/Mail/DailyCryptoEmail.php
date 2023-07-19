<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class DailyCryptoEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user; // Public variable to hold the user information
    public $currenciesData; // Public variable to hold the currencies data
    public $selectedCurrencies; // Public variable to hold the selected currencies

    /**
     * Create a new DailyCryptoEmail instance.
     *
     * @param User $user The user object
     * @param mixed $currenciesData The currencies data
     * @param mixed $selectedCurrencies The selected currencies
     */
    public function __construct(User $user, $currenciesData, $selectedCurrencies)
    {
        $this->user = $user; // Assign the provided User object to the $user variable
        $this->currenciesData = $currenciesData; // Assign the provided currencies data to the $currenciesData variable
        $this->selectedCurrencies = $selectedCurrencies; // Assign the provided selected currencies to the $selectedCurrencies variable
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.daily_crypto')->subject('Daily Crypto Report'); // Build the email using the 'daily_crypto' view and set the subject as 'Daily Crypto Report'
    }

    /**
     * Get the HTML representation of the message.
     *
     * @return string
     */
    public function toHtml()
    {
        $htmlContent = $this->view('emails.daily_crypto')->render(); // Render the 'daily_crypto' view and store the HTML content in $htmlContent

        return $htmlContent; // Return the HTML content
    }
}