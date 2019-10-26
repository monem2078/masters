<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCategory extends Model
{
    protected $fillable = ['master_id', 'category_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'master_categories';

    use SoftDeletes;

    public function master(){
        return $this->belongsTo('Models\Master');
    }
    public function category(){
        return $this->belongsTo('Models\Category');
    }
}