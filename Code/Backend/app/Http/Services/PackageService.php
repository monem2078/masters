<?php

namespace Services;

use Helpers\PackageHelper;
use Repositories\PackageRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;


class PackageService extends BaseService
{

    public function __construct(DatabaseManager $database, PackageRepository $repository)
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

    public function storePackageList($packages , $masterId){
        foreach ($packages as $package){
            if(!isset($package['id'])){
                $package['master_id'] = $masterId;
                $this->repository->create($package);
            }else{
                $updatePackage = PackageHelper::preparePackageData($package , $masterId);
                $this->repository->update($updatePackage , $package['id']);
            }
        }
    }
}