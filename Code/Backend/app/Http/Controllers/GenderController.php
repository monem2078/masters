<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\GenderService;
use Validator;
use Helpers\SecurityHelper;
use Models\Gender;

class GenderController extends Controller {

    private $genderService, $securityHelper;

    public function __construct(GenderService $genderService, SecurityHelper $securityHelper) {
        $this->genderService = $genderService;
        $this->securityHelper = $securityHelper;
    }


    public function index(){
        return response()->json($this->genderService->getAll(),200);
    }

    public function show($id) {
        return response()->json($this->genderService->getById($id),200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, Gender::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->genderService->create($data),200);
    }

    public function update(Request $request, $id) {
        $data = $request->all();

        $validator = Validator::make($data, Gender::rules('update'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->genderService->update($id, $data),200);
    }

    public function destroy($id) {
        $gender = $this->genderService->getById($id);
        if($gender == null) {
            return response()->json(['error' => "gender doesn't exist"], 400);
        }
        $usersOfGender = $gender->users;
        if(count($usersOfGender) != 0){
            return response()->json(['error' => "can't delete this gender, it has users"], 400);
        }
        $this->genderService->delete($id);
        return response()->json(['message' => 'success'],200);
    }

    public function getPagedList(Request $request) {
        $filter = (object) ($request->all());
        return response()->json($this->genderService->filteredUsers($filter),200);
    }

}
