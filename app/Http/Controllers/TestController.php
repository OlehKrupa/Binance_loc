<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class TestController extends Controller
{
    public function index()
    {
        $url = 'https://api.exchange.coinbase.com/currencies';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);

        dd($data);

    }
   
}
