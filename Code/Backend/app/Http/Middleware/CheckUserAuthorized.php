<?php
/**
 * 
 * User: rana.ahmed
 * 
 * 
 */

namespace App\Http\Middleware;

use Helpers\SecurityHelper;
use JWTAuth;
use Services\UserService;

class CheckUserAuthorized
{
    private $scurityHelper, $userService;

    public function __construct(SecurityHelper $scurityHelper, 
        UserService $userService)
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
        $user = $this->userService->getById($payload->get('user_id'));
        if($user['role_id'] == config('roles.admin')){
            return $next($request);
        } else {
            $requesrUserId = $request->user_id;
            $userId = $user->id;
            if($requesrUserId == $userId){
                return $next($request);
            }else {
                return response()->json(['message' => ["Not Allowed"]], 401);
            }
        }

    }

}