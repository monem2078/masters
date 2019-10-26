<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = ['platform_name' , 'platform_name_ar'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'platforms';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'platform_name' => 'required',
                    'platform_name_ar' => 'required'
                );
            case 'update':
                return array(
                    'platform_name' => 'required',
                    'platform_name_ar' => 'required'
                );
        }
    }
}