<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['master_id' , 'category_id', 'title', 'description', 'price', 'currency_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'packages';

    use SoftDeletes;

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'master_id' => 'required',
                    'category_id' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'price' => 'required',
                    'currency_id' => 'required'
                );
            case 'update':
                return array(
                    'master_id' => 'required',
                    'category_id' => 'required',
                    'title' => 'required',
                    'description' => 'required',
                    'price' => 'required',
                    'currency_id' => 'required'
                );

            case 'save-list':
                return array(
                    'packages' => 'required|array',
                    'packages.*.category_id' => 'required',
                    'packages.*.title' => 'required',
                    'packages.*.description' => 'required',
                    'packages.*.price' => 'required',
                    'packages.*.currency_id' => 'required'
                );
        }
    }
    public function master(){
        return $this->belongsTo('Models\Master');
    }
    public function category(){
        return $this->belongsTo('Models\Category');
    }
    public function currency(){
        return $this->belongsTo('Models\Currency');
    }
}