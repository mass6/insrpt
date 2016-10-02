<?php

namespace Insight\ProductRequests;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Insight\Repositories\Repository;

/**
 * Class ProductRequestRepository
 * @package Insight\ProductRequests
 */
class ProductRequestRepository extends Repository
{

    /**
     * @var
     */
    protected $currentUser;

    /**
     * @var
     */
    protected $hasCompanyDataAccess;

    /**
     * @var bool
     */
    protected $isSiteOwner;

    /**
     * @param Application $app
     * @throws \Insight\Repositories\Exceptions\RepositoryException
     */
    public function __construct(Application $app)
    {
        $this->currentUser = Sentry::getUser();
        $this->isSiteOwner = $this->currentUser->company->id == (int) settings('site_owner');
        $this->hasCompanyDataAccess = $this->currentUser->hasAccess('customers.data');
        parent::__construct($app);
    }
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return ProductRequest::class;
    }

    public function all($columns = array('*'))
    {
       return $this->model->with('company')->acl()->get();
    }

    public function getAll($columns = array('*'))
    {
        //return $this->model->with('requestedBy.company', 'productRequestList', 'quotations', 'quotationsReceived')->acl()->get()->sortByDesc('created_at');


        $query = DB::table('product_requests')
            ->join('users AS users1', 'product_requests.created_by_id', '=', 'users1.id')
            ->join('users AS users2', 'product_requests.requested_by_id', '=', 'users2.id')
            ->join('companies', 'users2.company_id', '=', 'companies.id')
            ->leftJoin('product_request_lists', 'product_requests.list_id', '=', 'product_request_lists.id')
            ->leftJoin('quotations', function($join)
            {
                $join->on('product_requests.id', '=', 'quotations.request_id')
                    ->where('quotations.state', '=', 'RCV');
            })
            ->select(
                'product_requests.*', 'users2.first_name AS requested_by_first_name','users2.last_name AS requested_by_last_name',
                'users1.first_name AS created_by_first_name','users1.last_name AS created_by_last_name',
                'companies.name as customer', 'product_request_lists.name as list_name',
                DB::raw('count(quotations.id) AS quotations_count')
        );

        $this->applyAclConstraints($query);

        return $query->orderBy('product_requests.id', 'desc')->groupBy('product_requests.id')->get();
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findById($id, $columns = array('*'))
    {
        return $this->model->with('requestedBy')->acl()->find($id, $columns);
    }

    /**
     * @param $request_id
     * @return mixed
     */
    public function findByRequestId($request_id)
    {
        return $this->model->with('requestedBy', 'contracts')->where('request_id', $request_id)->acl()->first();
    }

    /**
     * @param $column
     * @param $value
     * @return Collection
     */
    public function filterBy($column, $value)
    {
        try {
            $results = $this->model->with('requestedBy', 'contracts')->where($column, $value)->acl()->get();
        } catch (\Exception $e) {
            $results = new Collection([]);
        }

        return $results;
    }


    /**
     * @param $query
     *
     * @throws \Insight\Repositories\Exceptions\RepositoryException
     */
    private function applyAclConstraints($query)
    {
        if ( ! $this->isSiteOwner) {
            $query->where($this->getTableName() . '.company_id', $this->currentUser->company->id);

            if ( ! $this->hasCompanyDataAccess) {
                $query->where(function ($query)  {

                    $query->where($this->getTableName() . '.created_by_id', $this->currentUser->id)->orWhere(function ($query) {
                        if (Schema::hasColumn($this->getTableName(), 'requested_by_id')) {
                            $query->where($this->getTableName() . '.requested_by_id', $this->currentUser->id);
                        }
                    })->orWhere(function ($query) {
                        if (Schema::hasColumn($this->getTableName(), 'assigned_to_id')) {
                            $query->where($this->getTableName() . '.assigned_to_id', $this->currentUser->id);
                        }
                    });
                });
            }
        }
    }

}
