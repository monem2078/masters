<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class PackageRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\Package';
    }

    public function getUsedParentCategories()
    {
        return $this->model->selectRaw('distinct parent.*')
            ->join('categories', 'packages.category_id', 'categories.id')
            ->rightJoin('categories as parent', 'categories.parent_category_id', 'parent.id')
            ->where('parent.parent_category_id', null)
            ->get();
    }

    public function getUsedCountries()
    {
        return $this->model
            ->selectRaw('distinct countries.*')
            ->join('masters', 'masters.id', 'packages.master_id')
            ->join('users', 'users.id', 'masters.user_id')
            ->join('countries', 'countries.id', 'users.country_id')
            ->get();
    }

    public function getMinPrice()
    {
        return $this->model->selectRaw('min(price) as minPrice')
            ->first();

    }

    public function getMaxPrice()
    {
        return $this->model->selectRaw('max(price) as maxPrice')
            ->first();
    }


    public function getMasterPackages($masterId)
    {
        return $this->model->where('master_id', $masterId)->get();
    }

    public function deletePackagesByMasterIdAndCategoryIds($masterId, $categoryIds)
    {
        return $this->model->where('master_id', $masterId)
            ->whereIn('category_id', $categoryIds)
            ->delete();
    }

    public function deletePackageByMasterId($masterId)
    {
        return $this->model->where('master_id', $masterId)
            ->delete();
    }
}
