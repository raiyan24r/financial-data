<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormSubmitRequest;
use App\Jobs\SendEmailJob;
use Illuminate\View\View;
use App\Mail\FormSubmitted;
use Illuminate\Http\Request;
use App\Services\CompanyService;
use App\Services\UtilityServices;
use App\Services\ChartDataService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Services\HistoricalQuoteService;

class HistoricalDataController extends Controller
{

    /**
     * fetch the company symbols and return the historical data view
     *
     * @return view
     */
    public function index()
    {

        $symbols = (new CompanyService)->getSymbols();
        return view('historical-data', compact('symbols'));
    }

    /**
     * fetch the filtered historical data for the symbol and send email via job
     *
     * @param Request $request
     * @return void
     */
    public function symbolData(FormSubmitRequest $request)
    {
        $symbol        = $request->symbol;
        $from          = $request->from;
        $to            = $request->to;
        $withChartData = $request->withChartData == 'true' ? true : false;


        $symbolPrices          = (new HistoricalQuoteService)->filteredSymbolPrices($symbol, $from, $to);
        $chartData             = $withChartData ? (new ChartDataService)->multiAxisLine($symbolPrices->sortBy('date'), 'formattedDate', ['open', 'close']) : [];
        $paginatedSymbolPrices = (new UtilityServices)->paginate($symbolPrices, 50, $request->page)->toArray();


        $view = view('symbol-historical-quotes-table', compact('paginatedSymbolPrices', 'symbol', 'from', 'to'))->render();

        if ($request->sendEmail == 'true') {
            SendEmailJob::dispatch($request->email, $request->companyName, $from, $to);
        }

        return response(['status' => 200, 'view' => $view, 'chartData' => $chartData]);
    }
}
