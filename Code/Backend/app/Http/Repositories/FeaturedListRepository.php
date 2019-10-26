<?php

namespace Repositories;

use DB;
class FeaturedListRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\FeaturedList';
    }

    public function getFeatured($countryId , $userId = null , $maxCount = null){
        $this->model = $this->model
            ->selectRaw('masters.id , masters.headline , masters.about_headline , masters.about_text , masters.user_id , masters.overall_rating , 
            users.name , min(packages.price) as min_price , images.image_url , 
             profile_image.image_url as profile_image_url')
            ->join('masters' , 'featured_list.master_id' , 'masters.id')
            ->leftJoin('master_categories', 'masters.id', 'master_categories.master_id')
            ->join('packages', 'masters.id', 'packages.master_id')
            ->leftJoin('users', 'users.id', 'masters.user_id')
            ->leftJoin('images as profile_image' , 'users.profile_image_id' , 'profile_image.id')
            ->leftJoin('favorites', 'favorites.master_id', 'masters.id')
            ->leftJoin('master_images', function ($join) {
                $join->on('master_images.id', DB::raw('(Select id from master_images where master_images.master_id = masters.id LIMIT 1)'));
            })
            ->leftJoin('images', 'master_images.image_id', 'images.id')
            ->join('categories', 'packages.category_id', 'categories.id')
            ->leftJoin('categories as parent', 'parent.id', 'categories.parent_category_id')
            ->leftJoin('countries', 'users.country_id', 'countries.id')
            ->leftJoin('cities', 'cities.id', 'users.city_id')
            ->where('users.country_id' , $countryId)
            ->orderBy('featured_list.order' , 'asc')
            ->orderBy('masters.id' , 'asc')
            ->groupBy('users.name', 'masters.id', 'masters.headline',
                'masters.about_headline', 'masters.about_text',
                'masters.overall_rating', 'masters.user_id', 'images.image_url' ,
                'featured_list.order' , 'profile_image.image_url');


        $this->model = $this->model->selectRaw("(select currencies.currency_name
         from packages as p join currencies
          on currencies.id = p.currency_id order by p.price limit 1) as currency_name");

        $this->model = $this->model->selectRaw("(select currencies.currency_name_ar
         from packages as p join currencies
          on currencies.id = p.currency_id order by p.price limit 1) as currency_name_ar");

        $this->model = $this->model->selectRaw("(select currencies.symbol
         from packages as p join currencies
          on currencies.id = p.currency_id order by p.price limit 1) as symbol");

        if ($userId != null) {
            $this->model = $this->model->selectRaw("(CASE WHEN (SELECT COUNT(id) FROM favorites WHERE favorites.user_id = $userId AND favorites.master_id = masters.id) != 0 THEN 1 ELSE 0 END)as is_favorite");
        } else {
            $this->model = $this->model->selectRaw('0 as is_favorite');
        }

        if($maxCount){
            $this->model = $this->model->take($maxCount);
        }

        return $this->model->get();
    }

}