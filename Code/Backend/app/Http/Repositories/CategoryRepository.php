<?php

namespace Repositories;

/**
 * Description of Masters
 *
 * @author rana.ahmed
 */
class CategoryRepository extends BaseRepository
{

    /**
     * Determine the model of the repository
     *
     */
    public function model()
    {
        return 'Models\Category';
    }

    public function getCategoryChildren($categoryId)
    {
        return $this->model->where('parent_category_id', $categoryId)->get();
    }

    public function getNestedCategories()
    {
        return $this->model->where('parent_category_id', null)
            ->selectRaw('categories.* , images.image_url')
            ->with(['subCategories' => function ($query) {
                $query->selectRaw('categories.* , images.image_url')
                    ->leftJoin('images', 'images.id', 'categories.icon_image_id');
            }])
            ->leftJoin('images', 'images.id', 'categories.icon_image_id')
            ->get();
    }


    public function getUsedSubCategories($parentId, $masterId)
    {
        return $this->model
            ->where('id', $parentId)
            ->with(['subCategories' => function ($query) use ($parentId, $masterId) {
                $query->selectRaw("distinct categories.parent_category_id,categories.*")
                    ->join('master_categories', 'categories.id', 'master_categories.category_id')
                    ->where('master_id', $masterId)
                    ->where('master_categories.deleted_at' , null);
            }])
            ->first();
    }
}