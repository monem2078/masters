<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\LanguageService;
use Validator;
use Helpers\SecurityHelper;
use Models\Language;

class LanguageController extends Controller {

    private $languageService, $securityHelper;

    public function __construct(LanguageService $languageService, SecurityHelper $securityHelper) {
        $this->languageService = $languageService;
        $this->securityHelper = $securityHelper;
    }


    public function index(){
        return response()->json($this->languageService->getAll(),200);
    }

    public function show($id) {
        return response()->json($this->languageService->getById($id),200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, Language::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->languageService->create($data),200);
    }

    public function update(Request $request, $id) {
        $data = $request->all();

        $validator = Validator::make($data, Language::rules('update'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->languageService->update($id, $data),200);
    }

    public function destroy($id) {
        $language = $this->languageService->getById($id);
        if($language == null) {
            return response()->json(['error' => "language doesn't exist"], 400);
        }
        $usersOfLanguage = $language->users;
        if(count($usersOfLanguage) != 0){
            return response()->json(['error' => "can't delete this language, it has users"], 400);
        }
        $this->languageService->delete($id);
        return response()->json(['message' => 'success'],200);
    }
}
