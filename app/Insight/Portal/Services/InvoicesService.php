<?php

namespace Insight\Portal\Services;

use Illuminate\Support\Facades\App;
use Insight\Portal\Repositories\InvoiceQuery;
use Insight\Portal\Repositories\PortalRepository;

class InvoicesService
{

    /**
     * @var PortalRepository
     */
    private $portalRepository;

    /**
     * @var InvoiceQuery
     */
    private $query;


    /**
     * InvoicesService constructor.
     *
     * @param PortalRepository $portalRepository
     * @param InvoiceQuery     $query
     */
    public function __construct(PortalRepository $portalRepository, InvoiceQuery $query)
    {
        $this->portalRepository = $portalRepository;
        $this->query = $query;
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
    //public function getInvoices($customerGroupId = null)
    //{
    //    if ($customerGroupId) {
    //        $this->query->setFilter('order.customer_group_id', $customerGroupId ?: $this->getPortalCustomerGroupId());
    //    }
    //    $this->query->setFromDateFieldFilter('created_at', $this->carbon->now()->subDays(90));
    //    $response = $this->portalRepository->getDeliveries($this->query);
    //
    //    return $response;
    //}

    /**
     * @param $order_id
     *
     * @return mixed
     */
    //public function getDeliveriesByOrderId($order_id)
    //{
    //    $this->query->setFilter('order_id', $order_id);
    //
    //    return $this->portalRepository->getDeliveries($this->query);
    //}

    /**
     * @param $entity_id
     *
     * @return mixed
     */
    public function getInvoice($entity_id)
    {
        //$this->query->setFilter('entity_id', $entity_id);

        if (! $invoice = $this->portalRepository->getInvoice($entity_id)) {
            throw new \InvalidargumentException('Invoice not found.');
        }
        //return $invoice;
        $invoice['totals'] = $this->getInvoicedTotals($invoice);
        $invoice['order'] = App::make('Insight\Portal\Services\OrderService')->getOrderDetails($invoice['order_id']);
        $invoice['orderQuantities'] = $this->getOrderedTotals($invoice['order']['items']);

        return $invoice;
    }

    protected function getInvoicedTotals($invoice)
    {
        foreach ($invoice['items'] as $item) {
            $invoiceTotals[$item['sku']] = isset($invoiceTotals[$item['sku']]) ? $invoiceTotals[$item['sku']] + $item['qty'] : $item['qty'];
        }

        return $invoiceTotals;
    }

    protected function getOrderedTotals($orderItems)
    {
        foreach ($orderItems as $item) {
            $orderQuantities[$item['sku']]['qty'] = $item['qty'];
        }
        return $orderQuantities;
    }


}
