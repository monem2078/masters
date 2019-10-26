<?php

namespace Helpers;


use Services\MasterService;
use Services\UserService;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SecurityHelper
{

    private $userService;
    private $masterService;

    public function __construct(UserService $userService , MasterService $masterService)
    {
        $this->userService = $userService;
        $this->masterService = $masterService;
    }

    public static function getHashedPassword($password)
    {
        $hashedPassword= hash('sha256', $password, FALSE);
        return $hashedPassword;
    }

    public static function check($value, $hashedValue)
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }

        return (hash('sha256', $value) === $hashedValue);
    }


    public function getCurrentUser()
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            if (!$payload->get('user_id')) {
               return null;
            }
        }catch (\Tymon\JWTAuth\Exceptions\JWTException $ex) {
            return null;
        }

        $user = $this->userService->getById($payload->get('user_id'));

        return $user;

    }
}