<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\FeaturedListService;
use Services\CityService;
use Services\MasterService;
use Validator;
use Helpers\SecurityHelper;
use Models\FeaturedList;


class FeaturedController extends Controller {

    private $featuredService, $securityHelper , $cityService;
    private $masterService;

    public function __construct(FeaturedListService $featuredService,
                                SecurityHelper $securityHelper,
                                CityService $cityService ,
                                MasterService $masterService) {
        $this->featuredService = $featuredService;
        $this->securityHelper = $securityHelper;
        $this->cityService = $cityService;
        $this->masterService = $masterService;
    }

    public function index(){
        $user = $this->securityHelper->getCurrentUser();
        $userId = null;
        if($user){
            $userId = $user->id;
        }

        $featured = $this->featuredService->getFeaturedList($userId);
        return response()->json($featured , 200);
    }

    public function show($id) {
        return response()->json($this->featuredService->getById($id),200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, Country::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->featuredService->create($data),200);
    }

    public function update(Request $request, $id) {
        $data = $request->all();

        $validator = Validator::make($data, Country::rules('update'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->featuredService->update($id, $data),200);
    }

    public function getPagedList(Request $request)
    {
        $countryId = $request->header('CountryId');
        $user = $this->securityHelper->getCurrentUser();
        $filter = (object)($request->all());
        if ($user != null) {
            $filter->SearchObject['user_id'] = $user->id;
        }
        $filter->SearchObject['featured'] = true;
        $filter->SortBy = "featured_list.order";
        $filter->SortDirection = "ASC";
        $filter->SearchObject['country_id'] = $countryId;
        $masters = $this->masterService->getPagedList($filter);
        return response()->json($masters, 200);
    }
}
