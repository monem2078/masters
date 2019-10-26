<?php

namespace Repositories;

use function foo\func;
use DB;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class MasterRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\Master';
    }

    public function getMasterByUserId(int $userId)
    {
        $master = $this->model->selectRaw("*")
            ->where('user_id', $userId)
            ->where('deleted_at', null)
            ->first();
        return $master;
    }

    public function getMastersByName($name) {

        $query = $this->model->where('users.name', 'like', '%' . trim($name) . '%')
            ->leftJoin('users','users.id','masters.user_id');

        return $query->take(15)->get();

    }


    public function filterMasters($pageNumber, $pageSize, $searchObj, $sortBy,
                                  $sortDirection)
    {
        $query = $this->getAllCompaniesQuery($searchObj, $sortBy);
        return $this->getPagedQueryResults($pageNumber, $pageSize, $query, $sortBy, $sortDirection);
    }

    public function getAllCompaniesQuery($searchObj, $sortBy)
    {

        if (isset($searchObj->category_ids) && !empty($searchObj->category_ids)) {
            $this->model = $this->model
                ->where(function ($query) use ($searchObj) {
                    $query->whereIn('categories.id', $searchObj->category_ids);
                    $query->orWhereIn('categories.parent_category_id', $searchObj->category_ids);
                });
        }

        if (isset($searchObj->rating_from)) {
            $this->model = $this->model->where('masters.overall_rating', '>=', $searchObj->rating_from);
        }

        if (isset($searchObj->name)) {
            $this->model = $this->model->where('users.name', $searchObj->name);
        }

        if (isset($searchObj->headline)) {
            $this->model = $this->model->where('masters.headline', $searchObj->headline);
        }

        if (isset($searchObj->overall_rating)) {
            $this->model = $this->model->where('masters.overall_rating', $searchObj->overall_rating);
        }

        if (isset($searchObj->os_version)) {
            $this->model = $this->model->where('users.os_version', $searchObj->os_version);
        }

        if (isset($searchObj->source)) {
            $this->model = $this->model->where('users.oauth_provider', $searchObj->source);
        }

        if (isset($searchObj->rating_to)) {
            $this->model = $this->model->where('masters.overall_rating', '<=', $searchObj->rating_to);
        }


        if (isset($searchObj->price_from)) {
            $this->model = $this->model->where('packages.price', '>=', $searchObj->price_from);
        }

        if (isset($searchObj->price_to)) {
            $this->model = $this->model->where('packages.price', '<=', $searchObj->price_to);
        }

        if (isset($searchObj->country_id)) {
            $this->model = $this->model->where('users.country_id', $searchObj->country_id);
        }

        if (isset($searchObj->city_id)) {
            $this->model = $this->model->where('users.city_id', $searchObj->city_id);
        }

        if (isset($searchObj->platform_id)) {
            $this->model = $this->model->where('users.platform_id', $searchObj->platform_id);
        }

        if (isset($searchObj->gender_id)) {
            $this->model = $this->model->where('users.gender_id', $searchObj->gender_id);
        }

        if (isset($searchObj->query) && !empty($searchObj->query)) {
            $searchObj->query = $searchObj->query . "*";
            $this->model = $this->model->where(function ($query) use ($searchObj) {

                $query->whereRaw(" MATCH (masters.headline , masters.about_headline , masters.about_text)
                                   AGAINST ('$searchObj->query' IN BOOLEAN MODE)")
                    ->orWhereRaw(" MATCH (categories.category_name , categories.category_name_ar)
                                   AGAINST ('$searchObj->query' IN BOOLEAN MODE)")
                    ->orWhereRaw(" MATCH (parent.category_name , parent.category_name_ar)
                                   AGAINST ('$searchObj->query' IN BOOLEAN MODE)")
                    ->orWhereRaw(" MATCH (countries.country_name , countries.country_name_ar)
                                   AGAINST ('$searchObj->query' IN BOOLEAN MODE)")
                    ->orWhereRaw(" MATCH (cities.city_name , cities.city_name_ar)
                                   AGAINST ('$searchObj->query' IN BOOLEAN MODE)")
                    ->orWhereRaw(" MATCH (packages.title , packages.description)
                                   AGAINST ('$searchObj->query' IN BOOLEAN MODE)")
                    ->orWhereRaw(" MATCH (users.name)
                                   AGAINST ('$searchObj->query' IN BOOLEAN MODE)");
            });

        }

        if (isset($searchObj->trending)) {
            $this->model = $this->model->join('trending_list', 'trending_list.master_id', 'masters.id')
                ->groupBy('trending_list.order');
        }

        if (isset($searchObj->featured)) {
            $this->model = $this->model
                ->join('featured_list', 'featured_list.master_id', 'masters.id')
                ->groupBy('featured_list.order');
        }

        $this->model = $this->model
            ->selectRaw('masters.id , masters.headline , masters.about_headline , cities.city_name_ar, countries.country_name_ar, users.mobile_no,
             masters.about_text, users.os_version , users.oauth_provider, masters.user_id, platforms.platform_name_ar, platforms.id as platform_id, masters.overall_rating , 
            users.name , min(packages.price) as min_price , images.image_url , 
            profile_image.image_url as profile_image_url')
            ->leftJoin('master_categories', 'masters.id', 'master_categories.master_id')
            ->join('packages', 'masters.id', 'packages.master_id')
            ->leftJoin('users', 'users.id', 'masters.user_id')
            ->leftJoin('images as profile_image', 'users.profile_image_id', 'profile_image.id')
            ->leftJoin('favorites', 'favorites.master_id', 'masters.id')
            ->leftJoin('master_images', function ($join) {
                $join->on('master_images.id', DB::raw('(Select id from master_images where master_images.master_id = masters.id LIMIT 1)'));
            })
            ->leftJoin('images', 'master_images.image_id', 'images.id')
            ->join('categories', 'packages.category_id', 'categories.id')
            ->leftJoin('categories as parent', 'parent.id', 'categories.parent_category_id')
            ->leftJoin('platforms', 'users.platform_id' , 'platforms.id')
            ->leftJoin('countries', 'users.country_id', 'countries.id')
            ->leftJoin('cities', 'cities.id', 'users.city_id')
            ->groupBy('users.name', 'users.oauth_provider', 'masters.id', 'masters.headline', 'cities.city_name_ar', 'countries.country_name_ar', 'users.mobile_no',
                'masters.about_headline', 'masters.about_text', 'users.os_version', 'platforms.id', 'platforms.platform_name_ar',
                'masters.overall_rating', 'masters.user_id', 'images.image_url',
                'profile_image.image_url');

        if (isset($searchObj->user_id)) {
            $this->model = $this->model->selectRaw("(CASE WHEN (SELECT COUNT(id) FROM favorites WHERE favorites.user_id = $searchObj->user_id AND favorites.master_id = masters.id) != 0 THEN 1 ELSE 0 END)as is_favorite");
        } else {
            $this->model = $this->model->selectRaw('0 as is_favorite');
        }

        if ($sortBy == 'country_id') {
            $this->model = $this->model->orderBy('countries.country_name_ar')->orderBy('cities.city_name_ar');
        }


        return $this->model;
    }


    public function getMinRating()
    {
        return $this->model->selectRaw('min(overall_rating) as minRating')
            ->first();

    }

    public function getMaxRating()
    {
        return $this->model->selectRaw('max(overall_rating) as maxRating')
            ->first();
    }


    public function getMasterDetails($id, $userId = null, $seeContactInfo = false)
    {

        if ($userId != null) {
            $this->model = $this->model->selectRaw("(CASE WHEN (SELECT COUNT(id) FROM favorites WHERE favorites.user_id = $userId AND favorites.master_id = masters.id) != 0 THEN 1 ELSE 0 END)as is_favorite");
        } else {
            $this->model = $this->model->selectRaw('0 as is_favorite');
        }

        if ($seeContactInfo) {
            $this->model = $this->model
                ->selectRaw("users.email , users.mobile_no, country_mobile.id as mobile_country_id, platforms.id as platform_id , country_mobile.country_name as mobile_country_name , 
                country_mobile.country_name_ar as mobile_country_name_ar,
                country_mobile.country_code as mobile_country_code , country_mobile.dial_code as mobile_country_dial_code , 
                country_mobile.flag_url as mobile_country_flag_url");
        }

        $master = $this->model
            ->selectRaw('countries.* , cities.* , platforms.*, users.name, users.os_version , users.oauth_provider, users.gender_id,
                masters.*,profile_image.image_url as profile_image_url,users.country_id , users.city_id')
            ->with(['ratings' => function ($query) {
                $query->selectRaw('master_ratings.* , users.name , profile_image.image_url')
                    ->join('users', 'users.id', 'master_ratings.user_id')
                    ->leftJoin('images as profile_image', 'users.profile_image_id', 'profile_image.id');
            }])
            ->withCount(['ratings as rating_count'])
            ->with(['packages' => function ($query) {
                $query->selectRaw('currencies.* , packages.*, categories.*')
                    ->join('currencies', 'currencies.id', 'packages.currency_id')
                    ->join('categories', 'packages.category_id', 'categories.id');
            }])
            ->with(['images' => function ($query) {
                $query->join('images', 'images.id', 'master_images.image_id');
            }])
            ->with('categoryIds')
            ->with(['categories' => function ($query) {
                $query->selectRaw('distinct master_categories.master_id , parent.id')
                    ->join('categories', 'master_categories.category_id', 'categories.id')
                    ->join('categories as parent', 'parent.id', 'categories.parent_category_id')
                    ->where('parent.parent_category_id', null);

            }])
            ->with(['contactRequests' => function($query) {
                $query->selectRaw('contact_requests.* , users.*, countries.*, cities.*, request_status_types.*, contact_requests.created_at as contact_created_at, contact_requests.id as request_id')
                ->join('users', 'contact_requests.user_id', 'users.id')
                ->leftJoin('countries', 'users.country_id', 'countries.id')
                ->leftJoin('cities', 'users.city_id', 'cities.id')
                ->leftJoin('request_status_types', 'contact_requests.request_status_type_id', 'request_status_types.id');
            }])
            ->with(['subCategories' => function($query){
                $query->selectRaw('categories.* , master_categories.master_id')
                ->join('categories', 'master_categories.category_id', 'categories.id');
                  }])
            ->join('users', 'users.id', 'masters.user_id')
            ->leftJoin('images as profile_image', 'users.profile_image_id', 'profile_image.id')
            ->leftJoin('countries', 'countries.id', 'users.country_id')
            ->leftJoin('countries as country_mobile', 'country_mobile.id', 'users.mobile_country_id')
            ->leftJoin('cities', 'cities.id', 'users.city_id')
            ->leftJoin('platforms', 'users.platform_id' , 'platforms.id')
            ->leftJoin('master_ratings', 'masters.id', 'master_ratings.master_id')
            ->where('masters.id', $id)
            ->first();

        $master->sub_category_ids = array_column($master->categoryIds->toArray() , 'category_id');
        $userRating = $master->ratings->where('user_id', $userId)->first();
        $master->user_rating = $userRating;
        unset($master->categoryIds);
        $master->ratings->each(function ($item, $key) {
                if ($item->created_at != null) {
                    $item->created_diff = $item->created_at->diffForHumans();
                    return $item;
                }
        });

        $master->contactRequests->each(function ($item, $key) {
            if ($item->contact_created_at != null) {
                $item->created_diff = $item->contact_created_at->format('j M ');
                return $item;
            }
        });
        return $master;
    }
}
