<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class TestController extends Controller
{
    public function index()
    {
        $url = 'https://api.coinbase.com/v2/prices/BTC-USD/buy';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        
        // Дальнейшая обработка полученных данных
        // ...
        dump($data); 

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.example.com/cryptocurrencies', // Замените URL на соответствующий API для получения списка криптовалют
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $cryptocurrencies = json_decode($response, true); // Предполагается, что API возвращает JSON со списком криптовалют


        dump($cryptocurrencies);
    }
   
}
