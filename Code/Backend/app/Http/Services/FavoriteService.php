<?php

namespace Services;

use Repositories\FavoriteRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;


class FavoriteService extends BaseService
{


    public function __construct(DatabaseManager $database, FavoriteRepository $repository)
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

    public function getUserFavorites($userId)
    {
        return $this->repository->getUserFavorites($userId);
    }

    public function checkIfFavoriteExist($userId, $masterId)
    {
        $fav = $this->repository->findByUserAndMaster($userId, $masterId);
        return ($fav != null);
    }

    public function findBy($attribute , $value){
        return $this->repository->findBy($attribute , $value);
    }

    public function findFavByMasterAndUser($masterId , $userId){
        return $this->repository->findByUserAndMaster($userId, $masterId);
    }

}