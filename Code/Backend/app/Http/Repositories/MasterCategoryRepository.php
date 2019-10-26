<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class MasterCategoryRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\MasterCategory';
    }
    public function deleteAllCategoriesForMaster($id) {
        return $this->model->selectRaw("*")
        ->where('master_id', $id)
        ->get()
        ->each
        ->delete();
    }
}