<?php

namespace App\Http\Controllers;

use Helpers\UploadHelper;
use Illuminate\Http\Request;
use Models\User;
use Services\ContactRequestService;
use Services\CountryService;
use Services\FeaturedListService;
use Services\MasterCategoryService;
use Services\MasterService;
use Services\TrendingListService;
use Validator;
use Helpers\SecurityHelper;
use Models\Master;

class MasterController extends Controller
{

    private $masterService, $securityHelper;
    private $contactRequestService;
    private $trendingService;
    private $featuredService;
    private $countryService;

    public function __construct(MasterService $masterService, SecurityHelper $securityHelper,
                                ContactRequestService $contactRequestService ,
                                TrendingListService $trendingListService ,
                                FeaturedListService $featuredListService ,
                                CountryService $countryService)
    {
        $this->masterService = $masterService;
        $this->securityHelper = $securityHelper;
        $this->contactRequestService = $contactRequestService;
        $this->trendingService =$trendingListService;
        $this->featuredService = $featuredListService;
        $this->countryService = $countryService;
    }


    public function show($id , Request $request)
    {

        $user = $this->securityHelper->getCurrentUser();
        $userId = null;
        $contactInfoAuth = false;

        if ($user) {
            $userId = $user->id;
            $currentMaster = $user->master;
            $currentMasterId = null;
            if($currentMaster){
                $currentMasterId = $currentMaster->id;
            }
            if ($user->role_id == config('roles.admin') ||
                $this->contactRequestService->checkUserAuthorizedContactInfo($user->id, $id) ||
                $currentMasterId == $id
            ) {
                $contactInfoAuth = true;
            }
        }

        $master = $this->masterService->getMasterDetails($id, $userId, $contactInfoAuth);
        return response()->json($master, 200);
    }

    public function store(Request $request)
    {
        $user = $this->securityHelper->getCurrentUser();
        $data = $request->all();

        if(isset($data['master']['user_id'])){
            $master = $this->masterService->getMasterByUserId($data['master']['user_id']);
        }else{
            $master = $this->masterService->getMasterByUserId($user->id);
            $data['master']['user_id'] = $user->id;
        }

        $validator = Validator::make($data, Master::rules('save'));
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        if ($master) {
            return response()->json(['message' => 'user is already master'] , 400);
        }

        $createdMaster =  $this->masterService->create($data);

        $masterDetails = $this->masterService->getMasterDetails($createdMaster->id , $user->id , true);

        return response()->json($masterDetails, 200);
    }

    public function update(Request $request, $id)
    {
        $user = $this->securityHelper->getCurrentUser();
        $data = $request->all();
        $validator = Validator::make($data, Master::rules('update'));
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $this->masterService->update($id , $data);

        $masterDetails = $this->masterService->getMasterDetails($id , $user->id , true);

        return response()->json($masterDetails, 200);
    }

    public function destroy($id)
    {

    }

    public function getPagedList(Request $request)
    {
        $countryId = $request->header('CountryId');
        $user = $this->securityHelper->getCurrentUser();
        $filter = (object)($request->all());
        if ($user != null) {
            $filter->SearchObject['user_id'] = $user->id;
        }

        $filter->SearchObject['country_id'] = $countryId;
        $masters = $this->masterService->getPagedList($filter);
        return response()->json($masters, 200);
    }

    public function getMastersFilterData()
    {
        return response()->json($this->masterService->getMastersFilterData(), 200);
    }

    public function getTrendingAndFeatured(Request $request){
        $countryId = $request->header('CountryId');

        $size = $request->size;
        $user = $this->securityHelper->getCurrentUser();
        $userId = null;
        if($user != null){
            $userId = $user->id;
        }

        $trending = $this->trendingService->getTrendingList($countryId , $userId , $size);
        $featured = $this->featuredService->getFeaturedList($countryId , $userId , $size);

        return response()->json(['trending' => $trending ,
                                'featured' => $featured]);
    }


    public function validateMasterRequest(Request $request){

        $data = $request->all();

        $validator = Validator::make($data, Master::rules('masterValidation'));
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $country = $this->countryService->getById($data['user']['mobile_country_id']);

        $mobileData['country_code'] = $country->country_code;
        $mobileData['mobile_no'] = $data['user']['mobile_no'];


        $validator = Validator::make($mobileData, User::rules('phone'));
        if ($validator->fails()) {
           return response()->json(['error_key' => 'mobile_not_valid']);
        }


        return response()->json(['message' => 'success']);
    }
}
