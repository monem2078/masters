<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Helpers\SecurityHelper;
use Helpers\UserHelper;
use Illuminate\Http\Request;
use Models\User;
use Services\SocialService;
use Services\UserService;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Socialite;

class AuthenticateController extends Controller
{

    private $userService;
    private $socialService;
    private $userHelper;
    private $securityHelper;

    public function __construct(UserService $userService, SocialService $socialService,
                                UserHelper $userHelper, SecurityHelper $securityHelper)
    {
        $this->userService = $userService;
        $this->socialService = $socialService;
        $this->userHelper = $userHelper;
        $this->securityHelper = $securityHelper;
    }

    public function index()
    {
        // TODO: show users
    }

    public function register(Request $request)
    {

        $data = $request->all();
        $data['username'] = $data['email'];
        $data['role_id'] = config('roles.client');
        $validator = Validator::make($data, User::rules('register'));
        if ($validator->fails()) {
            $message = [];
            $i = 0;
            foreach ($validator->errors()->toArray() as $error) {
                $message[$i] = $error[0];
                $i++;
            }
            return response()->json(['error' => join(',', $message)], 400);
        }

        $userExist = $this->userService->findBy('username', $data['username']);
        if ($userExist) {
            return response()->json(['error_key' => 'email_exist']);
        }



        $user = $this->userService->create($data);

        if(isset($credentials['fcm_token'])){
            $userUpdate['fcm_token'] = $credentials['fcm_token'];
            $this->userService->update($user->id , $userUpdate);
        }

        return response(["created" => true], 200);

    }

    /**
     * Login
     * @param Request $request
     * @return Token
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->all();
        try {
            // verify the credentials and create a token for the user
            $user = $this->userService->login($credentials['username'], $credentials['password']);

            if (!$user) {
                return response()->json(['error_key' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $customClaims = ['user_id' => $user->id];
        $token = JWTAuth::fromUser($user, $customClaims);
        // if no errors are encountered we can return a JWT
        $is_verified = $user->is_verified;

        if(isset($credentials['fcm_token'])){
            $userUpdate['fcm_token'] = $credentials['fcm_token'];
            $this->userService->update($user->id , $userUpdate);
        }

        return response()->json(compact('token', 'is_verified'), 200);
    }

    /**
     * Get Current Authenticated User
     *
     * @return User
     */
    public function getAuthenticatedUser()
    {
        $user = $this->securityHelper->getCurrentUser();

        $isMaster = false;
        if ($user->master != null) {
            $isMaster = true;
        }

        $user->image;
        $user['is_master'] = $isMaster;

        return response()->json($user, 200);
    }

    /**
     * Log Out
     */
    public function invalidate()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function socialLogin(Request $request)
    {
        $data = $request->all();
        $provider = $data["provider"];
        $accessToken = $request->social_access_token;
        $googleAuthCode = $request->google_auth_code;

        $socialUser = $this->socialService->getUserFromToken($provider, $accessToken, $googleAuthCode);

        if ($socialUser != null) {
            $user = $this->socialService->handleSocialUser($socialUser, $provider);
        } else {
            return response()->json(['error' => "Not Valid"], 400);
        }


        $userUpdate = [];
        if(isset($data['fcm_token'])){
            $userUpdate['fcm_token'] = $data['fcm_token'];
        }
        $userUpdate['os_version'] = $data['os_version'];
        $userUpdate['platform_id'] = $data['platform_id'];

        $this->userService->update($user->id, $userUpdate);

        //login here
        $customClaims = ['user_id' => $user->id];
        $token = JWTAuth::fromUser($user, $customClaims);
        $is_verified = $user->is_verified;
        $is_profile_completed = $user->username ? 1 : 0;
        return response()->json(compact('token', 'is_verified', 'is_profile_completed'), 200);
    }

    public function handleSocialCallback(Request $request, $provider)
    {

        $user = $this->socialService->socialLogin($provider);
        if (isset($user["error"])) {
            return response()->json(['error' => $user["error"]], 400);
        }
        //login here
        $customClaims = ['user_id' => $user->id];
        $token = JWTAuth::fromUser($user, $customClaims);
        $is_verified = $user->is_verified;
        $is_profile_completed = $user->username ? 1 : 0;
        return response()->json(compact('token', 'is_verified', 'is_profile_completed'), 200);
    }

}
