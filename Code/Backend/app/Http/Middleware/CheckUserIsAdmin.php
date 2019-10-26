<?php
/**
 * Created by PhpStorm.
 * User: rana.ahmed
 * 
 * 
 */

namespace App\Http\Middleware;

use Helpers\SecurityHelper;
use JWTAuth;
use Services\UserService;


class CheckUserIsAdmin
{
    private $scurityHelper, $userService;

    public function __construct(SecurityHelper $scurityHelper, UserService $userService)
    {
        $this->scurityHelper = $scurityHelper;
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            if (!$payload->get('user_id')) {
                return null;
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $ex) {
            return null;
        }

        $user = $this->userService->getById($payload->get('user_id'));
        if($user['role_id'] == config('roles.admin')){
            return $next($request);
        }else {
            return response()->json(['message' => ["Not Allowed"]], 401);
        }

    }

}