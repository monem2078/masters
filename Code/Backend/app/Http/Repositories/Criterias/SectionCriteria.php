<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Repositories\Criterias;

use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;
/**
 * Description of SectionCriteria
 *
 * @author yasser.mohamed
 */
class SectionCriteria extends Criteria
{
    protected $sectionFilter;
    public function __construct($sectionFilter)
    {
        $this->sectionFilter = $sectionFilter;
    }
    public function apply($model, Repository $repository)
    {
        if(!is_null($this->sectionFilter->code)){
           $model = $model->where('code', '=', $this->sectionFilter->code);
        }
        return $model;
    }
}