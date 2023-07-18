<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\DailyCryptoEmail;

class Mailer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    protected $currenciesData;
    protected $selectedCurrencies;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     *
     * @param mixed $user
     * @param mixed $currenciesData
     * @param mixed $selectedCurrencies
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
        // Create a new SendGrid email instance
        $email = new \SendGrid\Mail\Mail();

        // Set the email sender and subject
        $email->setFrom("krupao.krnu@gmail.com", "Oleh");
        $email->setSubject("Daily crypto analysis");

        // Add the recipient email address
        $email->addTo($this->user->email);

        // Create a new instance of the DailyCryptoEmail Mailable
        $dailyCryptoEmail = new DailyCryptoEmail($this->user, $this->currenciesData, $this->selectedCurrencies);
        $htmlContent = $dailyCryptoEmail->toHtml();

        // Add the HTML content to the email
        $email->addContent("text/html", $htmlContent);

        // Create a new SendGrid instance and send the email
        $sendgrid = new \SendGrid(getenv('MAIL_PASSWORD'));
        try {
            $response = $sendgrid->send($email);
        } catch (\Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
