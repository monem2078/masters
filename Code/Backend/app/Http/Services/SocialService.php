<?php

namespace Services;

use Helpers\CustomerHelper;
use Helpers\UserHelper;
use Models\Customer;
use Models\User;
use Repositories\CustomerRepository;
use Repositories\ImageRepository;
use Repositories\UserRepository;
use Socialite;
use Validator;

/**
 * Description of SocialService
 *
 * @author Eman
 */
class SocialService
{
    private $userHelper;
    private $userRepository;
    private $imageRepository;

    public function __construct(UserHelper $userHelper, UserRepository $userRepository, ImageRepository $imageRepository)
    {
        $this->userHelper = $userHelper;
        $this->userRepository = $userRepository;
        $this->imageRepository = $imageRepository;
    }

    public function socialLogin($provider)
    {
        if ($provider == config('providers.facebook')) {
            return $this->faceBookLogin();
        }
        if ($provider == config('providers.google')) {
            return $this->googleLogin();
        }
    }

    public function handleSocialUser($socialUser, $provider)
    {
        if ($provider == config('providers.facebook')) {
            return $this->handleFaceBookUser($socialUser);
        }
        if ($provider == config('providers.google')) {
            return $this->handleGoogleUser($socialUser);
        }
    }

    public function faceBookLogin()
    {
        $socialUser = Socialite::driver(config('providers.facebook'))->stateless()->user();
        $user = $this->handleFaceBookUser($socialUser);
        return $user;
    }
    public function googleLogin()
    {
        $socialUser = Socialite::driver(config('providers.google'))->stateless()->user();
        $user = $this->handleGoogleUser($socialUser);
        return $user;
    }

    public function handleFaceBookUser($socialUser)
    {

        $user = $this->userRepository->getUsertByProviderAndUid(config('providers.facebook'), $socialUser->getId());
        if (!$user) {
            $user = $this->saveUserData($socialUser, config('providers.facebook'));
        }
        return $user;
    }

    public function handleGoogleUser($socialUser)
    {
        $user = $this->userRepository->getUsertByProviderAndUid(config('providers.google'), $socialUser->getId());
        if (!$user) {
            $user = $this->saveUserData($socialUser, config('providers.google'));
        }
        return $user;
    }

    public function saveUserData($socialUser, $providerName)
    {
        $data = [
            'oauth_uid' => $socialUser->getId(),
            'name' => $socialUser->getName(),
            'email' => $socialUser->getEmail(),
            'image_url' => $socialUser->getAvatar(),
        ];


        $userExist = $this->userRepository->findBy('username' , $data['email']);
        $image['image_url'] = $data['image_url'];
        $image['original_image_url'] = $data['image_url'];
        $image =  $this->imageRepository->create($image);
        $userData = $this->userHelper->prepareUserDataOnCreate($data, config('roles.client'), $providerName);
        $userData['profile_image_id'] = $image->id;

        if(!$userExist){
            $user = $this->userRepository->create($userData);
        }else{
            $this->userRepository->update($userData , $userExist->id);
            $user = $this->userRepository->findBy('id' , $userExist->id);
        }


        return $user;
    }

    public function getUserFromToken($provider, $accessToken,$googleAuthCode=null)
    {
        try {
            if($provider == config('providers.google') && $accessToken == null)
            {
                $accessTokenResponse= Socialite::driver($provider)->getAccessTokenResponse($googleAuthCode);
                $accessToken=$accessTokenResponse["access_token"];
            }

            $user = Socialite::driver($provider)->userFromToken($accessToken);
            return $user;
        } catch (Exception $e) {
            return $e;
        }

    }

}
