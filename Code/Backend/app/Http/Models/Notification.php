<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'notification_title', 'notification_title_ar', 'notification_text', 'notification_text_ar', 'notification_type_id', 'is_read', 'parameters' , 'action_user_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'notification';
    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'user_id' => 'required',
                    'notification_title' => 'required',
                    'notification_title_ar' => 'required',
                    'notification_text' => 'required',
                    'notification_text_ar' => 'required',
                    'notification_type_id' => 'required',
                    'is_read' => 'required',
                    'parameters' => 'required'
                );
            case 'update':
                return array(
                    'user_id' => 'required',
                    'notification_title' => 'required',
                    'notification_title_ar' => 'required',
                    'notification_text' => 'required',
                    'notification_text_ar' => 'required',
                    'notification_type_id' => 'required',
                    'is_read' => 'required'
                );
        }
    }
    public function user(){
        return $this->belongsTo('Models\User');
    }
    public function notificationType(){
        return $this->belongsTo('Models\NotificationType');
    }
}