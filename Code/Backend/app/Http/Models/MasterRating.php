<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class MasterRating extends Model
{
    protected $fillable = ['user_id' , 'master_id', 'rating', 'review'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'master_ratings';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'user_id' => 'required',
                    'master_id' => 'required',
                    'rating' => 'required'
                );
            case 'update':
                return array(
                    'user_id' => 'required',
                    'master_id' => 'required',
                    'rating' => 'required'
                );
        }
    }
    public function master(){
        return $this->belongsTo('Models\Master');
    }
    public function user(){
        return $this->belongsTo('Models\User');
    }


}
