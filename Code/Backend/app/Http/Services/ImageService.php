<?php

namespace Services;

use Repositories\ImageRepository;
use Repositories\MasterImageRepository;
use Illuminate\Database\DatabaseManager;
use \Illuminate\Database\Eloquent\Model;


class ImageService extends BaseService
{

    public function __construct(DatabaseManager $database, ImageRepository $repository)
    {
        $this->setDatabase($database);
        $this->setRepository($repository);
    }

    public function prepareCreate(array $data)
    {
        $data['image_url'] = $data['url'];
        $data['original_image_url'] = $data['url'];
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
}
