<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function send_mail(Request $request)
    {
    	$details = [
    		'subject' => 'Test Notification'
    	];
    	
        $job = (new \App\Jobs\Mailer($details))
            	->delay(now()->addSeconds(2)); 

        dispatch($job);
        echo "Mail send successfully !!";
    }
}
