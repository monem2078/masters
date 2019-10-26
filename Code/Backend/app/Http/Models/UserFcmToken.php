<?php
/**
 * Description of UserFcmToken
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class UserFcmToken extends Model
{
    protected $fillable = ['user_id' , 'fcm_token'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'user_fcm_tokens';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'user_id' => 'required',
                    'fcm_token' => 'required'
                );
            case 'update':
                return array(
                    'user_id' => 'required',
                    'fcm_token' => 'required'
                );
        }
    }
    public function user(){
        return $this->belongsTo('Models\User');
    }
}