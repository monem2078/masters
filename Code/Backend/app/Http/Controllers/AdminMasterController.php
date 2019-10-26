<?php

namespace App\Http\Controllers;

use Helpers\SecurityHelper;
use Illuminate\Http\Request;
use Models\Master;
use Services\ContactRequestService;
use Services\CountryService;
use Services\FeaturedListService;
use Services\ImageService;
use Services\MasterImageService;
use Services\MasterService;
use Services\TrendingListService;
use Services\UserService;
use Validator;

class AdminMasterController extends Controller
{
    private $masterService, $securityHelper;
    private $contactRequestService;
    private $trendingService;
    private $featuredService;
    private $countryService;
    private $requestService;
    private $userService;
    private $imageService;
    private $masterImageService;

    public function __construct(MasterService $masterService,UserService $userService, SecurityHelper $securityHelper,
                                ContactRequestService $contactRequestService ,
                                ImageService $imageService,
                                MasterImageService $masterImageService,
                                TrendingListService $trendingListService ,
                                FeaturedListService $featuredListService ,
                                CountryService $countryService, ContactRequestService $requestService)
    {
        $this->masterService = $masterService;
        $this->userService = $userService;
        $this->securityHelper = $securityHelper;
        $this->contactRequestService = $contactRequestService;
        $this->trendingService =$trendingListService;
        $this->featuredService = $featuredListService;
        $this->countryService = $countryService;
        $this->requestService = $requestService;
        $this->imageService = $imageService;
        $this->masterImageService = $masterImageService;
    }

    public function getPagedList(Request $request)
    {
        $filter = (object) ($request->all());
        return response()->json($this->masterService->getPagedList($filter),200);
    }

    public function show($id)
    {
        $user = $this->masterService->getById($id);
        $contactInfoAuth = true;
        $this->contactRequestService->checkUserAuthorizedContactInfo($user->id, $id);
        $master = $this->masterService->getMasterDetails($id, $user->id, $contactInfoAuth);
        return response()->json($master, 200);
    }

    public function update(Request $request, $id)
    {
        $user = $this->masterService->getById($id);
        $master_data = $request->only('headline', 'about_headline', 'about_text', 'education', 'certificate', 'sub_category_ids', 'packages', 'sub_category');
        $validator = \Validator::make($master_data, Master::rules('update-dashboard'));
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $this->masterService->update($id , $master_data);
        if ($request['url']) {
            $image_data = $request->only('url');
            $image = $this->imageService->create($image_data);
            $master_image_data['master_id'] = $id;
            $master_image_data['image_id'] = $image->id;
            $this->masterImageService->create($master_image_data);
        }


        $masterDetails = $this->masterService->getMasterDetails($id , $user->id , true);

        return response()->json($masterDetails, 200);
    }

    public function destroy($id)
    {
        $deleted = $this->masterService->delete($id);
        if($deleted !=0){
            return response()->json(['message' => 'data deleted successfully'], 200);
        }
    }

    public function autoCompleteMasters(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, Master::rules('auto-complete'));
        if ($validator->fails()) {
            return response()->json([ "error" => $validator->errors()->all()], 400);
        }

        $name = $data['name'];
        $masters = $this->masterService->getMastersByName($name);
        return response()->json($masters, 200);

    }

    public function updateStatus(Request $request) {
        $data = $request->all();
        $requestId = $data['id'];
            $agency = $this->requestService->getById($requestId);
            if($agency) {
                $updated = $this->requestService->update($requestId, $data);
                if ($updated) {
                    return response()->json(["message" => ['تم تغيير الحالة بنجاح']], 200);
                }
            } else {
                return response()->json([ "error" => 'Request Not Found'], 404);
            }
    }
}
