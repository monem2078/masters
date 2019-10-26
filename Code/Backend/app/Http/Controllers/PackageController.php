<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\MasterService;
use Services\PackageService;
use Validator;
use Helpers\SecurityHelper;
use Models\Package;

class PackageController extends Controller {

    private $packageService, $securityHelper;
    private $masterService;

    public function __construct(PackageService $packageService, SecurityHelper $securityHelper ,
                                MasterService $masterService) {
        $this->packageService = $packageService;
        $this->securityHelper = $securityHelper;
        $this->masterService = $masterService;
    }


    public function index(){
        return response()->json($this->packageService->getAll(),200);
    }

    public function show($id) {
        return response()->json($this->packageService->getById($id),200);
    }

    public function store(Request $request , int $masterId)
    {

        $data = $request->all();
        $data["master_id"] = $masterId;
        $validator = Validator::make($data, Package::rules('save'));
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }


        return response()->json($this->packageService->create($data),200);
    }

    public function update(Request $request , int $masterId, $packageId) {
        $data = $request->all();
        $data["master_id"] = $masterId;

        $user = $this->securityHelper->getCurrentUser();

       $validator = Validator::make($data, Package::rules('update'));
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $package =  $this->packageService->getById($packageId);
        if(!$package){
            return response()->json(['message' => 'package does not exist'] , 400);
        }
        if($user->role_id != config('roles.admin') && $user->master->id != $package->master_id){
            return response()->json(['message' => 'not allowed'] , 401);
        }
        if($masterId != $package->master_id){
            return response()->json(['message' => 'not allowed'] , 401);
        }

        $this->packageService->update($packageId, $data);


        return response()->json(['message' => 'success'],200);
    }

    public function destroy(int $masterId, $packageId) {

        $user = $this->securityHelper->getCurrentUser();
        $package = $this->packageService->getById($packageId);
        if($package == null) {
            return response()->json(['error' => "package doesn't exist"], 400);
        }

        if($user->role_id != config('roles.admin') && $user->master->id != $package->master_id){
            return response()->json(['message' => 'not allowed'] , 401);
        }

        if($masterId != $package->master_id){
            return response()->json(['message' => 'not allowed'] , 401);
        }


        $this->packageService->delete($packageId);
        return response()->json(['message' => 'success'],200);
    }

    public function storeList(Request $request){
        $data = $request->all();
        $user = $this->securityHelper->getCurrentUser();
        $masterId = $user->master->id;
        $packages = $data['packages'];
        $validator = Validator::make($data, Package::rules('save-list'));

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 400);
        }

        $this->packageService->storePackageList($packages , $masterId);

        $masterDetails = $this->masterService->getMasterDetails($masterId , $user->id , true);

        return response()->json($masterDetails, 200);
    }
}
