<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\MasterService;
use Services\TrendingListService;
use Services\CityService;
use Validator;
use Helpers\SecurityHelper;
use Models\TrendingList;


class TrendingListController extends Controller {

    private $trendingListService, $securityHelper , $cityService;
    private $masterService;

    public function __construct(TrendingListService $trendingListService,
                                SecurityHelper $securityHelper,
                                CityService $cityService ,
                                MasterService $masterService) {
        $this->trendingListService = $trendingListService;
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

        $trending = $this->trendingListService->getTrendingList($userId);
        return response()->json($trending , 200);
    }

    public function show($id) {
        return response()->json($this->trendingListService->getById($id),200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, TrendingList::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->trendingListService->create($data),200);
    }

    public function update(Request $request, $id) {
        $data = $request->all();

        $validator = Validator::make($data, TrendingList::rules('update'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }
        return response()->json($this->trendingListService->update($id, $data),200);
    }

    public function getPagedList(Request $request)
    {
        $countryId = $request->header('CountryId');
        $user = $this->securityHelper->getCurrentUser();
        $filter = (object)($request->all());
        if ($user != null) {
            $filter->SearchObject['user_id'] = $user->id;
        }
        $filter->SortBy = "trending_list.order";
        $filter->SortDirection = "ASC";
        $filter->SearchObject['trending'] = true;
        $filter->SearchObject['country_id'] = $countryId;
        $masters = $this->masterService->getPagedList($filter);
        return response()->json($masters, 200);
    }

}
