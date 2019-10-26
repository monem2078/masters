<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\FavoriteService;
use Services\PackageService;
use Validator;
use Helpers\SecurityHelper;


class FavoritesController extends Controller {

    private $favoriteService, $securityHelper;

    public function __construct(FavoriteService $favoriteService, SecurityHelper $securityHelper) {
        $this->favoriteService = $favoriteService;
        $this->securityHelper = $securityHelper;
    }

    public function index(){
        $user = $this->securityHelper->getCurrentUser();
        return response()->json($this->favoriteService->getUserFavorites($user->id) , 200);
    }


    public function addFavorite(Request $request){
        $user = $this->securityHelper->getCurrentUser();
        $userId = $user->id;
        $masterId = $request->master_id;
        $data['user_id'] = $userId;
        $data['master_id'] = $masterId;

        $favExist =  $this->favoriteService->checkIfFavoriteExist($userId , $masterId);
        if($favExist){
            return response()->json(['message' => 'favorite already exist']);
        }else{
            $this->favoriteService->create($data);
            return response()->json(['message' => 'success']);
        }
    }

    public function removeFavorite(Request $request){
        $user = $this->securityHelper->getCurrentUser();
        $masterId = $request->master_id;
        $fav = $this->favoriteService->findFavByMasterAndUser($masterId , $user->id);
        if(!$fav){
            return response()->json(['message' => 'favorite does not exist'] , 400);
        }


        if($fav->user_id != $user->id){
            return response()->json(['message' => "Not Allowed"], 401);
        }


        $this->favoriteService->delete($fav->id);
        return response()->json(['message' => 'success'] , 200);
    }
}
