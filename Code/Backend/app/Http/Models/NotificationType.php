<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $fillable = ['notification_type_name' , 'notification_type_name_ar' , 'image_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'notification_types';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'notification_type_name' => 'required',
                    'notification_type_name_ar' => 'required'
                );
            case 'update':
                return array(
                    'notification_type_name' => 'required',
                    'notification_type_name_ar' => 'required'
                );
        }
    }
}