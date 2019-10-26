<?php
namespace Models;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Description of Country
 *
 * @author rana.ahmed
 */
class Country extends Model
{
    protected $fillable = ['country_name','country_name_ar', 'country_code', 'flag_url', 'dial_code' , 'currency_id' , 'is_operating'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'countries';
    use SoftDeletes;

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'country_name' => 'required',
                    'country_name_ar' => 'required'
                    
                );
            case 'update':
                return array(
                    'country_name' => 'required',
                    'country_name_ar' => 'required'
                );
        }
    }
    public function cities()
    {
        return $this->hasMany('Models\City');
    }
    public function users()
    {
        return $this->hasMany('Models\User');
    }
}
