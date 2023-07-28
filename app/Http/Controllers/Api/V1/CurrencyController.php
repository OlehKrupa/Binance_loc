<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;

class CurrencyController extends Controller
{
    //localhost:80/api/v1/currency
    public function index()
    {
        return CurrencyResource::collection(Currency::all());
    }

    //localhost:80/api/v1/currency/{3}
    public function show(Currency $currency)
    {
        return CurrencyResource::make($currency);
    }
}