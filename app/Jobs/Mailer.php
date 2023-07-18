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
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("krupao.krnu@gmail.com", "Oleh");
        $email->setSubject("Daily crypto analysis");
        $email->addTo($this->user->email);
        
        $dailyCryptoEmail = new DailyCryptoEmail($this->user, $this->currenciesData, $this->selectedCurrencies);
        $htmlContent = $dailyCryptoEmail->toHtml();
        
        $email->addContent("text/html", $htmlContent);
        
        $sendgrid = new \SendGrid(getenv('MAIL_PASSWORD'));
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (\Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }
    }
}
