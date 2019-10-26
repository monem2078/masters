<?php

namespace Services;

use Repositories\ModuleRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;
use Repositories\TrendingListRepository;


class TrendingListService extends BaseService
{

    public function __construct(DatabaseManager $database, TrendingListRepository $repository)
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

    public function getTrendingList($countryId , $userId = null , $maxSize = null){
        return $this->repository->getTrending($countryId , $userId , $maxSize);
    }
}