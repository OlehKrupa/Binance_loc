<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Models\CurrencyHistory;

class HistoryController extends Controller
{
    //localhost:80/api/v1/history
    public function index()
    {
        return HistoryResource::collection(CurrencyHistory::all());
    }

    //localhost:80/api/v1/history/{3}
    //Получить все курсы именно {3} этой криптовалюты за все время
    public function show($currency_id)
    {
        $currencyHistories = CurrencyHistory::where('currency_id', $currency_id)->get();
        return HistoryResource::collection($currencyHistories);
    }
}