<?php

namespace Services;

use Repositories\FeaturedListRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;


class FeaturedListService extends BaseService
{

    public function __construct(DatabaseManager $database, FeaturedListRepository $repository)
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

    public function getFeaturedList($countryId , $userId = null , $maxSize = null){
        return $this->repository->getFeatured($countryId , $userId , $maxSize);
    }
}