<?php

namespace Services;

use Repositories\NotificationRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;


class NotificationService extends BaseService
{

    public function __construct(DatabaseManager $database, NotificationRepository $repository)
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

    public function readAllUserNotifications($userId){
        $this->repository->readUserNotifications($userId);
    }

    public function getNumberOfUnreadNotifications($userId){
       $count =  $this->repository->getNumberOfUnreadNotifications($userId);
        if($count){
            return $count->count;
        }else{
            return 0;
        }
    }

    public function getUserNotifications($userId){
        return $this->repository->getUserNotifications($userId);
    }
}