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
use Services\MasterService;

class CheckMasterAuthorized
{
    private $scurityHelper, $userService;

    public function __construct(SecurityHelper $scurityHelper, 
        UserService $userService, 
        MasterService $masterService)
    {
        $this->scurityHelper = $scurityHelper;
        $this->userService = $userService;
        $this->masterService = $masterService;
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
        $masterId = null;
        $user = $this->scurityHelper->getCurrentUser();
        if($user['role_id'] == config('roles.admin')){
            return $next($request);
        } else {
            $requestMasterId = $request->master_id;
            $master = $this->masterService->getMasterByUserId($user->id);
            if($master){
                $masterId = $master->id;
            }

            if($requestMasterId != null){
                if($masterId == $requestMasterId){
                    return $next($request);
                }else {
                    return response()->json(['message' => ["Not Allowed"]], 401);
                }
            }else if($masterId != null){
                return $next($request);
            }else{
                return response()->json(['message' => ["Not Allowed"]], 401);
            }
        }

    }

}