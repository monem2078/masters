<?php

namespace Models;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Description of City
 *
 * @author rana.ahmed
 */
class City extends Model
{
    protected $fillable = ['city_name','city_name_ar','country_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'cities';
    use SoftDeletes;

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'city_name' => 'required',
                    'city_name_ar' => 'required',
                    'country_id' => 'required'
                );
            case 'update':
                return array(
                    'city_name' => 'required',
                    'city_name_ar' => 'required',
                    'country_id' => 'required'
                );
        }
    }

    public function country()
    {
        return $this->belongsTo('Models\Country');
    }
    public function users()
    {
        return $this->hasMany('Models\User');
    }
}
