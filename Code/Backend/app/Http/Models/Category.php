<?php

namespace Models;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Description of Category
 *
 * @author rana.ahmed
 */
class Category extends Model
{
    protected $fillable = ['category_name','category_name_ar','icon_image_id','order','parent_category_id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $table = 'categories';
    use SoftDeletes;

    public static function rules($action, $id = null) {
        switch ($action) {
            case 'save':
                return array(
                    'category_name' => 'required',
                    'category_name_ar' => 'required',
                    'icon_image_id' => 'required'
                );
            case 'update':
                return array(
                    'category_name' => 'required',
                    'category_name_ar' => 'required',
                    'icon_image_id' => 'required'
                );
        }
    }
    public function masters()
    {
        return $this->hasMany('Models\MasterCategory');
    }

    public function parentCategory(){
        return $this->belongsTo('Models\Category' , 'id' , 'parent_category_id');
    }

    public function subCategories(){
        return $this->hasMany('Models\Category' , 'parent_category_id' , 'id');
    }
}
