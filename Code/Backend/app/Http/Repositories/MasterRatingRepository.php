<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class MasterRatingRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\MasterRating';
    }

    public function checkIfRatingExist($userId , $masterId){
        return $this->model->where('user_id' , $userId)
            ->where('master_id' , $masterId)->get();
    }

    public function getAverageRating($masterId){
        return $this->model->selectRaw('round(avg(rating) , 1) as average_rating')
            ->where('master_id' , $masterId)
            ->groupBy('master_id')
            ->first();
    }
}