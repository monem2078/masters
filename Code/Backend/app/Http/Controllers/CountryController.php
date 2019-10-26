<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\CountryService;
use Services\CityService;
use Validator;
use Helpers\SecurityHelper;
use Models\Country;


class CountryController extends Controller {

    private $countryService, $securityHelper , $cityService;

    public function __construct(CountryService $countryService, SecurityHelper $securityHelper, CityService $cityService) {
        $this->countryService = $countryService;
        $this->securityHelper = $securityHelper;
        $this->cityService = $cityService;
    }

    public function index(){
        return response()->json($this->
        countryService->getAll(),200);
    }

    public function show($id) {
        return response()->json($this->countryService->getById($id),200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, Country::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->countryService->create($data),200);
    }

    public function update(Request $request, $id) {
        $data = $request->all();

        $validator = Validator::make($data, Country::rules('update'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->countryService->update($id, $data),200);
    }

    public function destroy($id) {
        $country = $this->countryService->getById($id);
        if($country == null) {
            return response()->json(['error' => "country doesn't exist"], 400);
        }
        $citiesOfCountry = $country->cities;
        if(count($citiesOfCountry) != 0){
            return response()->json(['error' => "can't delete this country, it has cities"], 400);
        }

        $usersOfCountry = $country->users;
        if(count($usersOfCountry) != 0){
            return response()->json(['error' => "can't delete this country, it has users"], 400);
        }
        $this->countryService->delete($id);
        return response()->json(['message' => 'success'],200);
    }

    public function getPagedList(Request $request) {
        $filter = (object) ($request->all());
        return response()->json($this->countryService->getPagedList($filter),200);
    }
    public function getCitiesOfCountry($countryId) {
        return $this->countryService->getById($countryId)->cities;
    }
}
