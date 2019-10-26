<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    protected $fillable = ['master_id' , 'user_id', 'request_status_type_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'contact_created_at'];
    protected $table = 'contact_requests';

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'master_id' => 'required',
                    'user_id' => 'required',
                    'request_status_type_id' => 'required'
                    
                );
            case 'update':
                return array(
                    'master_id' => 'sometimes',
                    'user_id' => 'sometimes',
                    'request_status_type_id' => 'sometimes'
                );
        }
    }
    public function master(){
        return $this->belongsTo('Models\Master');
    }
    public function user(){
        return $this->belongsTo('Models\User');
    }
    public function statusType(){
        return $this->belongsTo('Models\RequestStatusType');
    }
}
