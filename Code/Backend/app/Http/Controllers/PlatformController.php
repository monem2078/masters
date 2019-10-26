<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\GenderService;
use Services\PlatformService;
use Validator;
use Helpers\SecurityHelper;
use Models\Gender;

class PlatformController extends Controller {

    private $platformService;

    public function __construct(PlatformService $platformService) {
        $this->platformService = $platformService;
    }


    public function index(){
        return response()->json($this->platformService->getAll(),200);
    }

}
