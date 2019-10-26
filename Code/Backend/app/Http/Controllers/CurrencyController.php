<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\CountryService;
use Services\CityService;
use Services\CurrencyService;
use Validator;
use Helpers\SecurityHelper;
use Models\Country;


class CurrencyController extends Controller {

    private $currencyService;

    public function __construct(CurrencyService $currencyService) {
        $this->currencyService = $currencyService;
    }

    public function index(){
        return response()->json($this->
        currencyService->getAll(),200);
    }
}
