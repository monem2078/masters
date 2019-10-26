<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class MasterImage extends Model
{
    protected $fillable = ['master_id' , 'image_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'master_images';


    public function master(){
        return $this->belongsTo('Models\Master');
    }
    public function image(){
        return $this->belongsTo('Models\Image');
    }
}