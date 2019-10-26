<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = ['language_name' , 'language_name_ar'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'languages';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'language_name' => 'required',
                    'language_name_ar' => 'required'
                );
            case 'update':
                return array(
                    'language_name' => 'required',
                    'language_name_ar' => 'required'
                );
        }
    }

    public function users()
    {
        return $this->hasMany('Models\User');
    }
}