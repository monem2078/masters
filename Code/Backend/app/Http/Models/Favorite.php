<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['master_id' , 'user_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'favorites';


    public function master(){
        return $this->belongsTo('Models\Master' , 'master_id');
    }
    public function user(){
        return $this->belongsTo('Models\User' , 'user_id');
    }
}