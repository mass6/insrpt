<?php

namespace Insight\Portal\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Insight\Portal\Repositories\DeliveryQuery;
use Insight\Portal\Repositories\PortalRepository;

class DeliveriesService
{

    /**
     * @var PortalRepository
     */
    private $portalRepository;

    /**
     * @var DeliveryQuery
     */
    private $query;

    /**
     * @var Carbon
     */
    private $carbon;


    /**
     * DeliveriesService constructor.
     *
     * @param PortalRepository $portalRepository
     * @param DeliveryQuery    $query
     * @param Carbon           $carbon
     */
    public function __construct(PortalRepository $portalRepository, DeliveryQuery $query, Carbon $carbon)
    {
        $this->portalRepository = $portalRepository;
        $this->query = $query;
        $this->carbon = $carbon;
    }

    /*
    |--------------------------------------------------------------------------
    | Deliveries
    |--------------------------------------------------------------------------
    |
    */
    /**
     * @param null $customerGroupId
     *
     * @return mixed
     */
    public function getDeliveries($customerGroupId = null)
    {
        if ($customerGroupId) {
            $this->query->setFilter('order.customer_group_id', $customerGroupId ?: $this->getPortalCustomerGroupId());
        }
        $this->query->setFromDateFieldFilter('created_at', $this->carbon->now()->subDays(90));
        $response = $this->portalRepository->getDeliveries($this->query);

        return $response;
    }

    /**
     * @param $order_id
     *
     * @return mixed
     */
    public function getDeliveriesByOrderId($order_id)
    {
        $this->query->setFilter('order_id', $order_id);

        return $this->portalRepository->getDeliveries($this->query);
    }

    /**
     * @param $increment_id
     *
     * @return mixed
     */
    public function getDelivery($increment_id)
    {
        $this->query->setFilter('increment_id', $increment_id);

        if (! $delivery = array_shift($this->portalRepository->getDeliveries($this->query)['data'])) {
            throw new \InvalidargumentException('Delivery not found.');
        }
        $delivery['totals'] = $this->getDeliveredTotals($delivery);
        $delivery['order'] = App::make('Insight\Portal\Services\OrderService')->getOrderDetails($delivery['order_id']);
        $delivery['orderQuantities'] = $this->getOrderedTotals($delivery['order']['items']);

        return $delivery;
    }

    protected function getDeliveredTotals($delivery)
    {
        foreach ($delivery['shipment_items']['data'] as $item) {
            $deliveryTotals[$item['sku']] = isset($deliveryTotals[$item['sku']]) ? $deliveryTotals[$item['sku']] + $item['qty'] : $item['qty'];
        }

        return $deliveryTotals;
    }

    protected function getOrderedTotals($orderItems)
    {
        foreach ($orderItems as $item) {
            $orderQuantities[$item['sku']]['qty'] = $item['qty'];
        }
        return $orderQuantities;
    }
}
