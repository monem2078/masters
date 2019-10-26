<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\CurrencyService;
use Services\UserService;
use Validator;
use Models\User;
use Helpers\SecurityHelper;
use Services\CityService;
use Services\CountryService;
use Services\CategoryService;
use Services\LanguageService;
use Services\GenderService;

class UserController extends Controller {

    private $userService, $securityHelper;
    private $genderService;
    private $languageService;
    private $categoryService;
    private $cityService;
    private $countryService;
    private $currencyService;

    public function __construct(UserService $userService, SecurityHelper $securityHelper,
        CountryService $countryService,
        CityService $cityService,
        CategoryService $categoryService,
        LanguageService $languageService,
        GenderService $genderService ,
        CurrencyService $currencyService) {

        $this->userService = $userService;
        $this->securityHelper = $securityHelper;
        $this->countryService = $countryService;
        $this->cityService = $cityService;
        $this->categoryService = $categoryService;
        $this->languageService = $languageService;
        $this->genderService = $genderService;
        $this->currencyService = $currencyService;

    }

    public function getPagedList(Request $request) {
        $filter = (object) ($request->all());
        return response()->json($this->userService->filteredUsers($filter),200);
    }


    public function show($id) {
        $user = $this->userService->getById($id);
        return response()->json($user ,200);
    }

        /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, User::rules('save'));
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        $user = $this->userService->create($data);
        return response()->json($user, 200);
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $validator = Validator::make($data, User::rules('update', $id));
        if ($validator->fails()) {
            $message = []; $i = 0;
            foreach ($validator->errors()->toArray() as $error){
                $message[$i] = $error[0]; $i++;
            }
            return response()->json(['error' => join(',' , $message)], 400);
        }

        $updated = $this->userService->update($id, $data);
        if ($updated) {
            return response()->json(["message" => 'success'], 200);
        }
    }

    public function destroy($id) {
        $deleted = $this->userService->delete($id);
        if($deleted !=0){
            return response()->json(['message' => 'data deleted successfully'], 200);
        }
    }

    public function getAllLookups() {
        $countries = $this->countryService->getAll();
        $cities = $this->cityService->getAll();
        $categories = $this->categoryService->getNestedCategories();
        $languages = $this->languageService->getAll();
        $genders = $this->genderService->getAll();
        $currencies = $this->currencyService->getAll();

        return response()->json(['countries' => $countries,
            'cities' => $cities,
            'categories' => $categories,
            'languages' => $languages,
            'genders' => $genders ,
            'currencies' => $currencies
        ], 200);
    }

    public function getUserNotifications(Request $request, $userId){
        $payload = JWTAuth::parseToken()->getPayload();
        if (!$payload->get('user_id')) {
            return response()->json(['user_not_found'], 404);
        }
        $user = $this->userService->getById($payload->get('user_id'));
        return response()->json($user->notifications,200);
    }


    public function updateProfile(Request $request){
        $user = $this->securityHelper->getCurrentUser();
        $data = $request->all();

        $validator = Validator::make($data, User::rules('update', $user->id));
        if ($validator->fails()) {
            $message = []; $i = 0;
            foreach ($validator->errors()->toArray() as $error){
                $message[$i] = $error[0]; $i++;
            }
            return response()->json(['error' => join(',' , $message)], 400);
        }


        $this->userService->update($user->id, $data);

        return response()->json(["message" => 'success'], 200);

    }


    public function changePassword(Request $request){
        $user = $this->securityHelper->getCurrentUser();
        $data = $request->all();

        $validator = Validator::make($data, User::rules('changePassword', $user->id));
        if ($validator->fails()) {
            $message = []; $i = 0;
            foreach ($validator->errors()->toArray() as $error){
                $message[$i] = $error[0]; $i++;
            }
            return response()->json(['error' => join(',' , $message)], 400);
        }

        $authUser = $this->userService->login($user['username'], $data['old_password']);
        if($authUser == null || $authUser->id != $user->id){
            return response()->json(['error_key' => 'wrong_password']);
        }

        $this->userService->update($user->id , ['password'=> $data['new_password']]);

        return response()->json(['message' => 'success']);
    }
}
