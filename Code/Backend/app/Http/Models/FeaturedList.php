<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class FeaturedList extends Model
{
    protected $fillable = ['master_id' , 'order'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'featured_list';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'master_id' => 'required',
                    'order' => 'required'
                );
            case 'update':
                return array(
                    'master_id' => 'required',
                    'order' => 'required'
                );
        }
    }
    public function master(){
        return $this->belongsTo('Models\Master');
    }
}