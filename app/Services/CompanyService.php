<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CompanyService
{

    public $companyList;
    /**
     * fetch the list of companies
     *
     * @return void
     */
    public function __construct()
    {
        $url = config('services.COMPANY_LIST_API_URL');
        $seconds           = 99999999999;
        $this->companyList = Cache::remember('symbols', $seconds, function () use ($url) {
            return Http::get($url)->collect();
        });
    }

    /**
     * get company symbols from the api
     *
     * @return collection
     */
    public function getSymbols()
    {

        return collect($this->companyList)->pluck('Symbol', 'Company Name');
    }
}
