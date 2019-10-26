<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['currency_name' , 'currency_name_ar', 'symbol'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'currencies';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'currency_name' => 'required',
                    'currency_name_ar' => 'required',
                    'symbol' => 'required'
                );
            case 'update':
                return array(
                    'currency_name' => 'required',
                    'currency_name_ar' => 'required',
                    'symbol' => 'required'
                );
        }
    }
}