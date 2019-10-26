<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class CityRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\City';
    }
    public function getCitiesOfCountry($countryId){
        $cities = $this->model
                ->selectRaw('*')
                ->where('country_id',$countryId)
                ->get();
        return $cities;      
    }
}