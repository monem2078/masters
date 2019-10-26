<?php

namespace Repositories\Criterias;

use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
use Bosnadev\Repositories\Criteria\Criteria;

/**
 * Description of RoleCriteria
 *
 * @author eman.mohamed
 */
class RoleCriteria extends Criteria
{
    protected $roleFilter;

    public function __construct($roleFilter)
    {
        $this->roleFilter = $roleFilter;
    }

    public function apply($model, Repository $repository)
    {
        if (isset($this->roleFilter->role_name)) {
            $model = $model->where('role_name', 'like', '%' . $this->roleFilter->role_name . '%');
        }
        if (isset($this->roleFilter->role_name_ar)) {
            $model = $model->where('role_name_ar', 'like', '%' . $this->roleFilter->role_name_ar . '%');
        }
        return $model;
    }
}
