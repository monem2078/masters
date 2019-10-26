<?php

namespace Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'username', 'password' ,  'role_id' , 'gender_id' ,   'country_id', 'oauth_provider', 'oauth_uid', 'is_verified', 'city_id', 'profile_image_id', 'mobile_country_id', 'mobile_no', 'gender', 'language_id', 'allow_notification', 'platform_id', 'os_version'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function rules($action, $id = null)
    {
        switch ($action) {
            case 'save':
                return array(
                    'name' => 'required',
                    'password' => 'sometimes',
                    'email' => 'sometimes|email',
                    'username' => 'required_with:password|unique_with:users,role_id,NULL,id,deleted_at,NULL',
                );
            case 'update':
                return array(
                    'name' => 'sometimes',
                    'email' => 'sometimes|email'
                );

            case 'register':
                return array(
                    'name' => 'required',
                    'password' => 'required|min:6',
                    'email' => 'required|email',
                    'username' => 'required',
                );

            case 'changePassword':
                return array(
                    'old_password' => 'required',
                    'new_password' => 'required|min:6|same:confirm_password',
                    'confirm_password' => 'required|min:6',
                );

            case 'phone':
                return array(
                    'mobile_no' => 'required|phone:country_code',
                );
        }
    }


    public function getUsernameForPasswordReset()
    {
        return $this->username;
    }

    public function favorites()
    {
        return $this->hasMany('Models\Favorite');
    }
    public function contactRequests()
    {
        return $this->hasMany('Models\ContactRequest');
    }
    public function gender()
    {
        return $this->belongsTo('Models\Gender');
    }
    public function country()
    {
        return $this->belongsTo('Models\Country');
    }
    public function city()
    {
        return $this->belongsTo('Models\City');
    }

    public function mobileCountry(){
        return $this->belongsTo('Models\Country' , 'mobile_country_id');
    }
    public function image()
    {
        return $this->belongsTo('Models\Image' , 'profile_image_id');
    }
    public function ratings()
    {
        return $this->hasMany('Models\MasterRating');
    }
    public function Master()
    {
        return $this->hasOne('Models\Master');
    }
    public function notifications()
    {
        return $this->hasMany('Models\Notification');
    }
    public function platform()
    {
        return $this->belongsTo('Models\Platform');
    }


}
