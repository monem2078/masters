<?php

namespace Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Services\UserService;
use Illuminate\Support\Str;

class UserHelper
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public static function prepareUserDataOnCreate($data,$userRole,$provider)
    {
        $userData=[];
        if (isset($data['password'])) {
            $userData["password"]= $data["password"];
        }

        if(isset($data["oauth_uid"])){
            $userData["oauth_uid"]= $data["oauth_uid"];
        }

        if(isset($data["email"])){
            $userData["email"]= $data["email"];
            $userData["username"]= $data["email"];
        }

        if(isset($data["name"])){
            $userData["name"]= $data["name"];
        }

        if(isset($data["mobile"])){
            $userData["username"]= $data["mobile"];
        }

        $userData["role_id"]= $userRole;
        $userData["is_verified"]= 0;
        $userData['oauth_provider'] =$provider;

        return $userData;
    }

    public static function prepareUserDataOnUpdate($data,$userRole)
    {
        $userData=[]; 
       
        $userData["role_id"]= $userRole;

        if (isset($data['email'])) {
            $userData["email"]= $data["email"];
        }

        if (isset($data['name'])) {
            $userData["name"]= $data["name"];
        }

        return $userData;
    }
    
}
