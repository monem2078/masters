<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class MasterImageRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\MasterImage';
    }

    public function deleteAllImagesForMaster($id) {
        return $this->model->selectRaw("*")
        ->where('master_id', $id)
        ->get()
        ->each
        ->delete();
    }
}