<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\HistoryResource;
use App\Models\CurrencyHistory;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Возвращает список всех записей истории курсов валют.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return HistoryResource::collection(CurrencyHistory::all());
    }

    /**
     * Создает новую запись истории курса валюты.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\CurrencyHistory
     */
    public function store(Request $request)
    {
        return CurrencyHistory::create($request->all());
    }

    /**
     * Возвращает все записи истории курсов для определенной валюты.
     *
     * @param  int  $currency_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($currency_id)
    {
        $currencyHistories = CurrencyHistory::where('currency_id', $currency_id)->get();
        return HistoryResource::collection($currencyHistories);
    }

    /**
     * Обновляет информацию о записи истории курса валюты.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Models\CurrencyHistory
     */
    public function update(Request $request, $id)
    {
        $history = CurrencyHistory::find($id);
        $history->update($request->all());
        return $history;
    }

    /**
     * Удаляет запись истории курса валюты.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        $history = CurrencyHistory::find($id);
        $history->delete();
        return ['message' => 'История валюты удалена'];
    }
}
