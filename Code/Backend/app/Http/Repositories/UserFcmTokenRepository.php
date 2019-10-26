<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class UserFcmTokenRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\UserFcmToken';
    }


    public function getEnglishFcmTokens($userIds){
        return $this->model->join('users' , 'users.id' , 'user_id')
            ->where('language_id' , config('languages.english'))
            ->where('users.allow_notification' , 1)
            ->whereIn('user_id' , $userIds)
            ->get();
    }


    public function getArabicFcmTokens($userIds){
        return $this->model->join('users' , 'users.id' , 'user_id')
            ->where('language_id' , config('languages.arabic'))
            ->where('users.allow_notification' , 1)
            ->whereIn('user_id' , $userIds)->get();
    }

}