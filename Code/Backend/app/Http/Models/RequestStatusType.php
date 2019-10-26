<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class RequestStatusType extends Model
{
    protected $fillable = ['request_status_name' , 'request_status_name_ar'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'request_status_types';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'request_status_name' => 'required',
                    'request_status_name_ar' => 'required'
                );
            case 'update':
                return array(
                    'request_status_name' => 'required',
                    'request_status_name_ar' => 'required'
                );
        }
    }
}