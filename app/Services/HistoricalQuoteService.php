<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Http;

class HistoricalQuoteService
{


    /**
     * fetch the symbol historical prices and filter them based on the given dates
     *
     * @param  string $symbol
     * @param  string date Y-m-d $from
     * @param  string date Y-m-d $to
     * @param  mixed $region
     * @return array
     */
    public function filteredSymbolPrices($symbol, $from = '1971-12-01', $to = '', $region = '')
    {

        $historicalQuote = $this->getSymbolQuote($symbol, $region);
        $filteredPrices  = $this->filterQuote($historicalQuote['prices'], $from, $to);

        return $filteredPrices->values();
    }

    /**
     * get the price quotes for a symbol
     *
     * @param  string $symbol
     * @param  string $region
     */
    public function getSymbolQuote($symbol, $region = '')
    {
        $url = config('services.HISTORICAL_DATA_API_URL');

        $response = Http::withHeaders([
            'X-RapidAPI-Key'  => config('services.RAPID_API_KEY'),
            'X-RapidAPI-Host' => config('services.RAPID_API_HOST'),
        ])->get($url, [
            'symbol' => $symbol,
            'region' => $region
        ]);

        return  $response->successful() ? $response->json() : ['prices' => []];
    }

    /**
     * filter the prices based on the date
     *
     * @param  mixed $prices
     * @param  string $from
     * @param  string $to
     * @return collection
     */
    public function filterQuote($prices, $from, $to)
    {

        $historicalPrices = collect($prices);
        $historicalPrices = $historicalPrices->map(function ($price) {
            $price['formattedDate'] = date("Y-m-d", $price['date']);
            return $price;
        });
        $to   = $to == '' ? Carbon::now()->format('Y-m-d') : $to;
        $from = date('Y-m-d', strtotime($from));
        $to   = date('Y-m-d', strtotime($to));

        $filteredPrices = $historicalPrices->filter(function ($price) use ($from, $to) {
            return ($price['formattedDate'] >= $from) && ($price['formattedDate'] <= $to);
        });

        return $filteredPrices;
    }
}
