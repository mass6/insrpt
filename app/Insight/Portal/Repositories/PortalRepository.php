<?php namespace Insight\Portal\Repositories;

/***
 * Created by:
 * User: sam
 * Date: 7/7/14
 * Time: 9:55 PM
 */

use Insight\Portal\Connections\GuzzleClient;
use Insight\Portal\Connections\PortalRestClient;
use \Session;

/**
 * Class PortalRepository
 * @package Insight\Portal\Repositories
 */
class PortalRepository implements PortalRepositoryInterface
{


    /** @var $client */
    protected $client;

    /**
     * @var GuzzleClient
     */
    private $guzzleClient;


    /**
     * @param PortalRestClient $client
     * @param GuzzleClient     $guzzleClient
     */
    public function __construct(PortalRestClient $client, GuzzleClient $guzzleClient)
    {
        $this->client              = $client;
        $this->guzzleClient = $guzzleClient;
    }

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getApprovalStatistics($dataGroup = null)
    {
        return $this->getGuzzleClient()->getReport('ApprovalStatistics', $dataGroup);
    }

    public function getOrders(OrderQuery $query)
    {
        return $this->getRestClient()->getJSON('orders', $query->getQueryParams());
    }

    /*
    |--------------------------------------------------------------------------
    | Order Search
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param        $reportName
     * @param        $search
     * @param string $format
     * @param null   $dataGroup
     *
     * @return array|bool|float|int|string
     */
    public function getQuery($reportName, $search, $format = 'json', $dataGroup = null)
    {
        $response = $this->getGuzzleClient()->getQuery($reportName, $search, $dataGroup);

        if ($format === 'json') {
            return json_encode($response);
        }
        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | Deliveries
    |--------------------------------------------------------------------------
    |
    */

    public function getDeliveries(DeliveryQuery $query)
    {
        return $this->getRestClient()->getJSON('shipments', $query->getQueryParams());
    }

    /*
    |--------------------------------------------------------------------------
    | Invoices
    |--------------------------------------------------------------------------
    |
    */

    public function getInvoice($entity_id)
    {
        return $this->getRestClient()->getJSON('invoices/' . $entity_id);
    }

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param ProductQuery $query
     *
     * @return mixed
     */
    public function getProducts(ProductQuery $query)
    {
        return $this->getRestClient()->getJSON('products', $query->getQueryParams());
    }

    /**
     * @param      $filters
     *
     * @return mixed
     */
    public function getProductsOrdered($filters)
    {
        return $this->getRestClient()->getJSON('products-ordered', $filters);
    }

    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getContracts($dataGroup = null)
    {
        return $this->getGuzzleClient()->getReport('Contracts', $dataGroup);
    }

    /**
     * @return array
     */
    public function getContractGroups()
    {
        return $this->getRestClient()->getJSON('contracts/groups');
    }

     /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getUsers($dataGroup = null)
    {
        return $this->getGuzzleClient()->getReport('Users', $dataGroup);
    }

    /*
    |--------------------------------------------------------------------------
    | Suppliers
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @return mixed
     */
    public function getSuppliers()
    {
        return $this->getRestClient()->getJSON('suppliers');
    }

    /*
    |--------------------------------------------------------------------------
    | DOA
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getDoa($dataGroup = null)
    {
        return $this->getGuzzleClient()->getReport('Doa', $dataGroup);
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Statistics
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getDailyOrderTotalsThisMonth($dataGroup = null)
    {
        return $this->getGuzzleClient()->getReport('DailyOrderTotalsThisMonth', $dataGroup) ?: [];
    }

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getOrdersPendingApprovalSum($dataGroup = null)
    {
        return $this->getGuzzleClient()->getReport('OrdersPendingApprovalSum', $dataGroup)[0] ?: [];
    }

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getCurrentMonthsOrdersSum($dataGroup = null)
    {
        return $this->getGuzzleClient()->getReport('CurrentMonthsOrdersSum', $dataGroup)[0] ?: [];
    }

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getThirdPartyOrdersThisMonthSum($dataGroup = null)
    {
        return $this->getGuzzleClient()->getReport('ThirdPartyOrdersThisMonthSum', $dataGroup)[0] ?: [];
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Reports
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param        $reportName
     * @param        $store
     * @param        $customer
     * @param string $format
     *
     * @return array|mixed
     */
    public function getValidationReport($reportName, $store, $customer, $format = 'json')
    {
        $response = $this->getGuzzleClient()->getValidationReport(ucwords($reportName), $store, $customer);

        if ($format === 'json') {
            return json_decode($response);
        }
        return $response;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @return PortalRestClient
     */
    protected function getRestClient()
    {
        return $this->client;
    }

    /**
     * @return GuzzleClient
     */
    protected function getGuzzleClient()
    {
        return $this->guzzleClient;
    }
}
