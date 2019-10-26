<?php

namespace Services;

use Repositories\MasterRatingRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;


class MasterRatingService extends BaseService
{

    public function __construct(DatabaseManager $database, MasterRatingRepository $repository)
    {
        $this->setDatabase($database);
        $this->setRepository($repository);
    }

    public function prepareCreate(array $data)
    {
        return $this->repository->create($data);
    }

    public function prepareUpdate(Model $model, array $data)
    {
        return $this->repository->update($data, $model->id);
    }

    public function prepareDelete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function checkIfRatingExist($userId , $masterId){
        $ratings =  $this->repository->checkIfRatingExist($userId , $masterId);
        return (sizeof($ratings) > 0 );
    }

    public function calculateOverallRating($masterId){
        $rating =  $this->repository->getAverageRating($masterId);
        if($rating){
            return $rating->average_rating;
        }else{
            return 0.0;
        }
    }

}