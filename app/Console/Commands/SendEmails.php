<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!empty($email = $this->argument('email'))){
            $email = $this->argument('email');
        } else {
            $email = $this->ask('Enter user email?');
            while (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $this->error('Email is invalid');
                $email = $this->ask('Enter user email?');
            }
        }
        
        Mail::raw('Binance', function (Message $message) use ($email){
                $message->to($email);
            });

        $this->info('Email was successful send!');
    }
}
