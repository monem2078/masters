<?php

namespace Repositories;

use DB;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class FavoriteRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\Favorite';
    }


    public function getUserFavorites($userId){
        $this->model =  $this->model
            ->selectRaw('favorites.id as favorite_id, masters.id, masters.headline , masters.about_headline , masters.about_text , masters.user_id , masters.overall_rating , 
            users.name , min(packages.price) as min_price , images.image_url , 
            profile_image.image_url as profile_image_url')
            ->join('masters' , 'favorites.master_id' , 'masters.id')
            ->leftJoin('master_images' , function($join){
                $join->on('master_images.id' , DB::raw('(Select id from master_images where master_images.master_id = masters.id LIMIT 1)'));
            })
            ->join('packages' , 'masters.id' , 'packages.master_id')
            ->leftJoin('users' , 'users.id' , 'masters.user_id')
            ->leftJoin('images as profile_image' , 'users.profile_image_id' , 'profile_image.id')
            ->leftJoin('images' , 'master_images.image_id' , 'images.id')
            ->groupBy('users.name' , 'masters.id' , 'masters.headline' ,
                'masters.about_headline' , 'masters.about_text' ,
                'masters.overall_rating' , 'masters.user_id' , 'images.image_url' ,
                'favorites.id' , 'profile_image.image_url')
            ->where('favorites.user_id' , $userId);

              $this->model = $this->model->selectRaw("(select currencies.currency_name
         from packages as p join currencies
          on currencies.id = p.currency_id order by p.price limit 1) as currency_name");

        $this->model = $this->model->selectRaw("(select currencies.currency_name_ar
         from packages as p join currencies
          on currencies.id = p.currency_id order by p.price limit 1) as currency_name_ar");

        $this->model = $this->model->selectRaw("(select currencies.symbol
         from packages as p join currencies
          on currencies.id = p.currency_id order by p.price limit 1) as symbol");

            return $this->model->get();
    }

    public function findByUserAndMaster($userId , $masterId){
        return $this->model->where('user_id' , $userId)
            ->where('master_id' , $masterId)->first();
    }

}