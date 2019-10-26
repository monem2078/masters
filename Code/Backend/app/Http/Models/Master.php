<?php
/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
namespace Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Master extends Model
{
    protected $fillable = ['user_id' , 'headline', 'about_headline', 'about_text', 'education' , 'certificate' , 'overall_rating'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'masters';

    use SoftDeletes;

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'master.user_id' => 'required',
                    'master.headline' => 'required',
                    'master.about_headline' => 'required',
                    'master.about_text' => 'required',
                    'categories'=> 'required|array',
                    'user.country_id' => 'required' ,
                    'user.city_id' => 'required',
                    'user.mobile_no' => 'required',
                    'user.mobile_country_id' => 'required' ,
                    'user.gender_id' =>  'sometimes',
                    'images' => 'required|array',
                    'images.*.original_image_url'=> 'required',
                    'images.*.image_url'=> 'required'
                );
            case 'update':
                return array(
                    'master.headline' => 'required',
                    'master.about_headline' => 'required',
                    'master.about_text' => 'required',
                    'categories'=> 'required|array',
                    'user.country_id' => 'required' ,
                    'user.city_id' => 'required',
                    'user.mobile_no' => 'required',
                    'user.mobile_country_id' => 'required' ,
                    'user.gender_id' =>  'sometimes',
                    'images' => 'required|array',
                    'images.*.original_image_url'=> 'required',
                    'images.*.image_url'=> 'required'
                );

            case 'becomeMaster':
                return array(
                    'master.user_id' => 'required',
                    'master.headline' => 'required',
                    'master.about_headline' => 'required',
                    'master.about_text' => 'required',
                    'categories'=> 'required|array',
                    'user.country_id' => 'required' ,
                    'user.city_id' => 'required',
                    'user.mobile_country_id' => 'required' ,
                    'user.gender_id' =>  'sometimes',
                    'images' => 'required|array',
                    'images.*.original_image_url'=> 'required',
                    'images.*.image_url'=> 'required'
                );

            case 'masterValidation':
                return array(
                    'user.mobile_country_id' => 'required' ,
                    'user.mobile_no' => 'required'
                );
            case 'auto-complete':
                return array(
                    'name'=> 'required'
                );

            case 'update-dashboard':
                return array(
                    'headline' => 'required',
                    'about_headline' => 'required',
                    'about_text' => 'required',
                    'certificate' => 'required',
                    'education' => 'required',
                );
        }
    }

    public function user(){
        return $this->belongsTo('Models\User');
    }

    public function favorites()
    {
        return $this->hasMany('Models\Favorite' , 'master_id' , 'id');
    }
    public function contactRequests()
    {
        return $this->hasMany('Models\ContactRequest');
    }
    public function categories()
    {
        return $this->hasMany('Models\MasterCategory');
    }
    public function images()
    {
        return $this->hasMany('Models\MasterImage');
    }
    public function ratings()
    {
        return $this->hasMany('Models\MasterRating');
    }
    public function packages()
    {
        return $this->hasMany('Models\Package');
    }

    public function isFavorite($userId){
       return ($this->hasMany('Models\Favorite' , 'master_id' , 'id')
           ->where('user_id' , $userId)->count() > 0);
    }

    public function categoryIds(){
        return $this->hasMany('Models\MasterCategory');
    }

    public function subCategories(){
        return $this->hasMany('Models\MasterCategory');
    }
}
