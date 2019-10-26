<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $fillable = ['gender_name' , 'gender_name_ar'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'genders';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'gender_name' => 'required',
                    'gender_name_ar' => 'required'
                );
            case 'update':
                return array(
                    'gender_name' => 'required',
                    'gender_name_ar' => 'required'
                );
        }
    }
    public function users()
    {
        return $this->hasMany('Models\User');
    }
}