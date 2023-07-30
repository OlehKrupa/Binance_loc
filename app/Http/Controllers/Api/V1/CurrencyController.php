<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Возвращает список всех валют.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CurrencyResource::collection(Currency::all());
    }

    /**
     * Создает новую валюту.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Currency
     */
    public function store(Request $request)
    {
        return Currency::create($request->all());
    }

    /**
     * Возвращает информацию о конкретной валюте.
     *
     * @param  \App\Models\Currency  $currency
     * @return \App\Http\Resources\CurrencyResource
     */
    public function show(Currency $currency)
    {
        return CurrencyResource::make($currency);
    }

    /**
     * Обновляет информацию о валюте.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Http\Resources\CurrencyResource
     */
    public function update(Request $request, $id)
    {
        $currency = Currency::find($id);
        $currency->update($request->all());
        return CurrencyResource::make($currency);
    }

    /**
     * Удаляет валюту.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        $currency = Currency::find($id);
        $currency->delete();
        return ['message' => 'Валюта удалена'];
    }
}
