<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\CityService;
use Models\City;
use Validator;
use Helpers\SecurityHelper;


class CityController extends Controller {

    private $cityService, $securityHelper;

    public function __construct(CityService $cityService, SecurityHelper $securityHelper) {
        $this->cityService = $cityService;
        $this->securityHelper = $securityHelper;
    }


    public function index(){
        return response()->json($this->cityService->getAll(),200);
    }

    public function show($id) {
        return response()->json($this->cityService->getById($id),200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, City::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->cityService->create($data),200);
    }

    public function update(Request $request, $id) {
        $data = $request->all();

        $validator = Validator::make($data, City::rules('update'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->cityService->update($id, $data),200);
    }

    public function destroy($id) {
        $city = $this->cityService->getById($id);
        if($city == null) {
            return response()->json(['error' => "city doesn't exist"], 400);
        }
        $usersOfCity = $city->users;
        if(count($usersOfCity) != 0){
            return response()->json(['error' => "can't delete this city, it has users"], 400);
        }
        $this->cityService->delete($id);
        return response()->json(['message' => 'success'],200);
    }

    public function getPagedList(Request $request) {
        $filter = (object) ($request->all());
        return response()->json($this->cityService->getPagedList($filter),200);
    }

}
