<?php

namespace Services;

use Helpers\SecurityHelper;
use Illuminate\Database\DatabaseManager;
use Repositories\ImageRepository;
use Repositories\UserFcmTokenRepository;
use Repositories\UserRepository;
use \Illuminate\Database\Eloquent\Model;
use Repositories\Criterias\UserCriteria;

class UserService extends BaseService
{

    private $imageRepository;
    private $userFcmTokenRepository;
    public function __construct(DatabaseManager $database, UserRepository $repository ,
                                ImageRepository $imageRepository , UserFcmTokenRepository $userFcmTokenRepository)
    {
        $this->setDatabase($database);
        $this->setRepository($repository);
        $this->imageRepository = $imageRepository;
        $this->userFcmTokenRepository = $userFcmTokenRepository;
    }

    public function prepareCreate(array $data)
    {
        if (isset($data['password'])) {
            $data["password"] = SecurityHelper::getHashedPassword($data["password"]);
        }
        $user = $this->repository->create($data);
        return $user;
    }

    public function prepareUpdate(Model $model, array $data)
    {
        if(isset($data['image'])){
           $image =  $this->imageRepository->create($data['image']);
           $data['profile_image_id'] = $image->id;
           unset($data['image']);
        }

        if (isset($data['password'])) {
            $data["password"] = SecurityHelper::getHashedPassword($data["password"]);
        }

        if(isset($data['fcm_token'])){
            $this->userFcmTokenRepository->create(['user_id' => $model->id , 'fcm_token' => $data['fcm_token']]);
            unset($data['fcm_token']);
        }

        $this->repository->update($data, $model->id);
    }

    public function prepareDelete(int $id)
    {
        $this->repository->delete($id);
    }

    public function login($email, $password)
    {
        $user = $this->repository->getUserByUsername($email);
        if ($user) {
            if ($user->password == SecurityHelper::getHashedPassword($password)) {
                return $user;
            }
        } else {
            return null;
        }
    }

    public function filteredUsers($filter)
    {
        if (isset($filter->SearchObject)) {
            $params = (object) $filter->SearchObject;
        } else {
            $params = new stdClass();
        }
        $criteria = new UserCriteria($params);

        if (!property_exists($filter, "SortBy")) {
            $filter->SortBy = "id";
        }
        if (!property_exists($filter, "SortDirection")) {
            $filter->SortDirection = "ASC";
        }
      
        $withExpressions = array("role");

        return $this->repository->getPagedResults($filter->PageNumber, $filter->PageSize,$withExpressions,$criteria,$filter->SortBy,$filter->SortDirection);
    }


    public function findBy($attribute , $value){
        return $this->repository->findBy($attribute , $value);
    }
}
