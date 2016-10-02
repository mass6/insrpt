<?php

namespace Insight\ProductRequests;

use Insight\Repositories\Repository;

/**
 * Class ProductRequestListRepository
 * @package Insight\ProductRequests
 */
class ProductRequestListRepository extends Repository
{

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return ProductRequestList::class;
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->model->with('requestedBy', 'company', 'productRequests')->get($columns);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findById($id, $columns = array('*'))
    {
        return $this->model->with('requestedBy', 'company', 'productRequests')->find($id, $columns);
    }

}
