<?php

namespace Insight\Portal\Services;

use Illuminate\Support\Facades\App;
use Insight\Core\CommandBus;
use Insight\Portal\Approvals\FormatApprovalStatisticsCommand;
use Insight\Portal\Orders\Helper;
use Insight\Portal\Repositories\OrderQuery;
use Insight\Portal\Repositories\PortalRepository;

class OrderService
{
    use CommandBus;

    /**
     * @var PortalRepository
     */
    private $portalRepository;

    /**
     * @var OrderQuery
     */
    private $query;

    /**
     * @var Helper
     */
    private $helper;


    /**
     * OrderService constructor.
     *
     * @param PortalRepository  $portalRepository
     * @param OrderQuery        $query
     * @param Helper            $helper
     */
    public function __construct(PortalRepository $portalRepository, OrderQuery $query, Helper $helper)
    {
        $this->portalRepository = $portalRepository;
        $this->query = $query;
        $this->helper = $helper;
    }



    public function getOrderDetails($id)
    {
        $order          = $this->portalRepository->getQuery('orderDetails', $id, 'array')[0];
        $order['items']          = $this->portalRepository->getQuery('orderItemDetails', $id, 'array');
        $order['comments']       = $this->portalRepository->getQuery('orderComments', $id, 'array');
        $order['deliveries']     = App::make('Insight\Portal\Services\DeliveriesService')->getDeliveriesByOrderId($order['entity_id'], true)['data'];
        $order['deliveryTotals'] = $this->getDeliveredTotals($order['deliveries']);

        return $order;
    }


    /**
     * Search orders
     *
     * @param        $customerGroupId
     * @param string $searchTerm
     * @param string $from
     * @param string $to
     *
     * @return array
     */
    public function getOrderSearchResults($customerGroupId, $searchTerm, $from = '', $to = '')
    {
        $params          = [
            'timezone' => @date_default_timezone_get(),
            'fields'   => 'increment_id,created_at,status,actual_delivery_date',
            'order'    => 'created_at',
            'dir'      => 'desc',
            'filter'   => [
                [
                    'attribute' => 'view',
                    'eq'        => '0'
                ],
                [
                    'attribute' => 'status',
                    'nin'       => [ 'canceled' ]
                ]
            ]
        ];
        if ($customerGroupId && ! empty( $customerGroupId )) {
            $this->query->setFilter('customer_group_id', $customerGroupId);
        }
        if ($from) {
            $this->query->setFromDateFieldFilter('created_at', $from . ' 00:00:00');
        }
        if ($to) {
            $this->query->setToDateFieldFilter('created_at', $to);
        }
        if ($searchTerm) {
            $this->query->setMultipleFieldFilter([
                'increment_id',
                'status',
                'customer_firstname',
                'customer_lastname',
                'contract_shipping',
                'cname',
                'code'
            ], $searchTerm);
        }
        $response = $this->portalRepository->getOrders($this->query);
        $orders   = [ ];
        $badge    = [
            'pending'             => 'badge-success',
            'pending_approval'    => 'badge-warning',
            'complete'            => 'badge-success',
            'processing'          => 'badge-info',
            'partially_delivered' => 'badge-info',
            'fully_delivered'     => 'badge-info',
            'canceled'            => 'badge-danger'
        ];
        foreach ($response as $item) {
            $order    = [
                'entity_id'            => $item['entity_id'],
                'web_order'            => $item['increment_id'],
                'contract'             => $item['contract'],
                'grand_total'          => $item['total'],
                'created_at'           => date('d M Y', strtotime($item['created_at'])),
                'status'               => $item['status'] == 'pending' ? 'approved' : $item['status'],
                'badge'                => isset( $badge[$item['status']] ) ? $badge[$item['status']] : '',
                'actual_delivery_date' => $item['actual_delivery_date']
            ];
            $orders[] = $order;
        }

        return $orders;
    }
    /**
     * @param null $group
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedToday($group = null, $contractGroup = null)
    {
        $this->setApprovedOrdersReportParams($group, $contractGroup);
        $this->query->setFromDateFieldFilter('approved_at', 'today');
        $this->query->setToDateFieldFilter('approved_at', 'today');

        return $this->getApprovedOrders();
    }


    /**
     * @param null $group
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedYesterday($group = null, $contractGroup = null)
    {
        $this->setApprovedOrdersReportParams($group, $contractGroup);
        $this->query->setFromDateFieldFilter('approved_at', 'yesterday');
        $this->query->setToDateFieldFilter('approved_at', 'yesterday');

        return $this->getApprovedOrders();
    }

    /**
     * @param null $group
     * @param null $contractGroup
     * @param bool $thirdPartySupplier
     *
     * @return array
     */
    public function getOrdersApprovedThisMonth($group = null, $contractGroup = null, $thirdPartySupplier = false)
    {
        $this->setApprovedOrdersReportParams($group, $contractGroup, $thirdPartySupplier);
        $this->query->setFromDateFieldFilter('approved_at', 'first day of this month 00:00:00');
        $this->query->setToDateFieldFilter('approved_at', 'last day of this month');

        return $this->getApprovedOrders();
    }

    /**
     * @param null $group
     * @param null $contractGroup
     *
     * @return array
     */
    public function getThirdPartyOrdersApprovedThisMonth($group = null, $contractGroup = null)
    {
        return $this->getOrdersApprovedThisMonth($group, $contractGroup, true);
    }

    /**
     * @param null $group
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedLastMonth($group = null, $contractGroup = null)
    {
        $this->setApprovedOrdersReportParams($group, $contractGroup);
        $this->query->setFromDateFieldFilter('approved_at', 'first day of last month 00:00:00');
        $this->query->setToDateFieldFilter('approved_at', 'last day of last month');

        return $this->getApprovedOrders();
    }

    /**
     * @param null $group
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedYearToDate($group = null, $contractGroup = null)
    {
        $this->setApprovedOrdersReportParams($group, $contractGroup);
        $this->query->setFromDateFieldFilter('approved_at', 'Jan 1 00:00:00');
        $this->query->setToDateFieldFilter('approved_at', 'Dec 31');

        return $this->getApprovedOrders();
    }

    /**
     * @param null $group
     *
     * @return array
     */
    public function getOrdersPendingApproval($group = null)
    {
        $this->query->setFields(['increment_id','status','created_at','updated_at','approver','approved','custom_ref1','custom_ref2','lead_time_hours']);
        $this->query->setSubCalls([ 'order_customer']);
        $this->query->orderBy('created_at','desc'); // order direction
        $this->query->setFilter('status', 'pending_approval');
        if ($group && ! empty( $group )) {
            $this->query->setFilter('customer_group_id', $group);
        }

        $response     = $this->portalRepository->getOrders($this->query);
        $reportFields = [
            'entity_id',
            'weborder',
            'total',
            'customer',
            'contract',
            'status',
            'created_at',
            'updated_at',
            'lead_time_hours',
            'total_lead_time_hours',
            'ordered_by',
            'current_approver',
            'last_approver',
            'custom_ref1',
            'custom_ref2'
        ];
        $fieldsMap    = [ 'weborder' => 'increment_id' ];

        return $this->prepareOrderReportData($response, $reportFields, $fieldsMap);

    }

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getApprovalStatistics($dataGroup = null)
    {
        $approvalHistory = $this->portalRepository->getApprovalStatistics($dataGroup);

        return $this->execute(new FormatApprovalStatisticsCommand($approvalHistory));
    }

    /**
     * @param $filters
     *
     * @return array
     */
    public function getCustomOrderReport($filters)
    {
        $this->setApprovedOrdersReportParams(array_get($filters, 'customer_group'), array_get($filters, 'contract_group'));
        if (array_get($filters, 'customer_group')) {
            $this->query->setFilter('customer_group_id', array_get($filters, 'customer_group'));
        }
        if (array_get($filters, 'contract_group')) {
            $this->query->setFilter('contract_group_id', array_get($filters, 'contract_group'));
        }
        if (array_get($filters, 'supplier')) {
            $this->query->setFilter('supplier', array_get($filters, 'supplier'));
        }

        if ($filters['date'] == 'range') {
            // should be aware of user input
            try {
                $this->query->setFromDateFieldFilter('approved_at', $filters['from']);
                $this->query->setToDateFieldFilter('approved_at', $filters['to']);
            } catch (\Exception $e) {
            }
        }

        return $this->getApprovedOrders();
    }

    /**
     * @param $filters
     *
     * @return array
     */
    public function getCustomOrderLinesReport($filters)
    {
        $this->query->setFields(['entity_id','increment_id','created_at','approved_by','approved_at','contract_id','contractship','customer_note','custom_ref1','custom_ref2','custom_ref3','custom_ref4','custom_ref5']);
        $this->query->setSubCalls([ 'order_item', 'order_contract', 'order_reference', 'order_customer' ]);
        $this->query->orderBy('approved_at','desc'); // order direction
        $this->query->setFilter('status', [ 'canceled', 'pending_approval' ], 'nin');
        $this->query->setFilter('reportable', 1);
        if (array_get($filters, 'customer_group')) {
            $this->query->setFilter('customer_group_id', array_get($filters, 'customer_group'));
        }
        if (array_get($filters, 'contract_group')) {
            $this->query->setFilter('contract_group_id', array_get($filters, 'contract_group'));
        }
        if (array_get($filters, 'supplier')) {
            $this->query->setFilter('supplier', array_get($filters, 'supplier'));
        }

        if ($filters['date'] == 'range') {
            // should be aware of user input
            try {
                $this->query->setFromDateFieldFilter('approved_at', $filters['from']);
                $this->query->setToDateFieldFilter('approved_at', $filters['to']);
            } catch (\Exception $e) {
            }
        }

        try {
            $response = $this->portalRepository->getOrders($this->query);
        } catch (\Exception $e) {
            $response = [ ];
        }

        $products = [ ];
        foreach ($response as $order) {
            if ( ! isset( $order['order_items'] )) {
                continue;
            }
            $shippingAddress = '';
            if (isset( $order['contract_data'] ) && $order['contractship'] != '') {
                $contract = $order['contract_data'];
                $i        = abs((int) $order['contractship'] - 10000);
                if (isset( $contract['name_ship' . $i] )) {
                    $shippingAddress = implode(' ', [
                        $contract['name_ship' . $i],
                        $contract['street_ship' . $i],
                        $contract['street1_ship' . $i],
                        $contract['city_ship' . $i],
                        $contract['state_ship' . $i],
                        $contract['zip_ship' . $i]
                    ]);
                }
            }
            foreach ($order['order_items'] as $item) {
                $categories         = [ ];
                $finalCategory      = '';
                $combinedCategories = '';
                if (isset( $item['categories'] )) {
                    foreach ($item['categories'] as $key => $category) {
                        $categories[$key + 1] = $category['name'];
                    }
                    $combinedCategories = implode(' | ', $categories);
                    $finalCategory      = count($categories) ? $categories[count($categories)] : '';
                }
                $products[] = [
                    'entity_id'               => $order['entity_id'],
                    'increment_id'            => $order['increment_id'],
                    'customer'                => $order['customer'],
                    'created_at'              => $order['created_at'],
                    'ordered_by'              => $order['ordered_by'],
                    'approved_at'             => $order['approved_at'],
                    'contract'                => $order['contract'],
                    'contract_id'             => $order['contract_id'],
                    'contract_site'           => isset( $order['contract_data'] ) ? $order['contract_data']['site'] : '',
                    'contract_contractor'     => isset( $order['contract_data'] ) ? $order['contract_data']['contractor'] : '',
                    'ship_to'                 => $shippingAddress,
                    'customer_note'           => $order['customer_note'],
                    'sku'                     => $item['sku'],
                    'partner_code'            => $item['bp_product_code'],
                    'name'                    => $item['name'],
                    'uom'                     => isset( $item['uom'] ) ? $item['uom'] : '',
                    'qty'                     => (int) $item['qty_ordered'],
                    'qty_shipped'             => (int) $item['qty_shipped'],
                    'qty_received'            => (int) $item['qty_received'],
                    'qty_received_updated_at' => $item['qty_received_updated_at'],
                    'qty_received_updated_by' => $item['qty_received_updated_by'],
                    'qty_canceled'            => (int) $item['qty_canceled'],
                    'qty_invoiced'            => (int) $item['qty_invoiced'],
                    'qty_refunded'            => (int) $item['qty_refunded'],
                    'price'                   => $this->helper->formatNumber($item['price']),
                    'row_total'               => $this->helper->formatNumber($item['row_total']),
                    'supplier'                => $item['supplier'],
                    'attribute_set_name'      => $item['attribute_set_name'],
                    'chargeable'              => $item['chargeable'],
                    'category'                => $finalCategory,
                    'category_level1'         => array_get($categories, 1),
                    'category_level2'         => array_get($categories, 2),
                    'category_level3'         => array_get($categories, 3),
                    'category_level4'         => array_get($categories, 4),
                    'category_level5'         => array_get($categories, 5),
                    'all_categories'          => $combinedCategories,
                    'custom_ref1'             => $order['custom_ref1'],
                    'custom_ref2'             => $order['custom_ref2'],
                    'custom_ref3'             => $order['custom_ref3'],
                    'custom_ref4'             => $order['custom_ref4'],
                    'custom_ref5'             => $order['custom_ref5']
                ];
            }
        }

        return $products;
    }

    /**
     * get daily orders report using REST
     *
     * @param $reportDate
     * @param $websiteCode
     *
     * @return array|mixed
     */
    public function getDailyOrders($reportDate, $websiteCode)
    {
        if ( ! $reportDate) {
            $reportDate = date('Y-m-d');
        }
        $this->query->setFields(['increment_id','store_id','customer_id','customer_email','approved_at','contract_id','contractship','custom_ref1','custom_ref2','custom_ref3','custom_ref4','custom_ref5']);
        $this->query->setSubCalls([ 'order_item', 'order_contract', 'order_reference', 'order_customer', 'order_comment' ]);
        $this->query->orderBy('created_at', 'desc');
        $this->query->setFilter('reportable', 1);
        $this->query->setFilter('website', $websiteCode);

        try {
            $this->query->setFromDateFieldFilter('approved_at', $reportDate . " 00:00:00");
            $this->query->setToDateFieldFilter('approved_at', $reportDate);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Invalid report date.");
        }

        try {
            $response = $this->portalRepository->getOrders($this->query);
        } catch (\Exception $e) {
            $response = [ ];
        }

        return $response;
    }

    /**
     * @param null $group
     * @param null $contractGroup
     * @param bool $thirdPartySuppliers
     *
     * @return mixed
     * @internal param array $overrides
     */
    protected function setApprovedOrdersReportParams($group = null, $contractGroup = null, $thirdPartySuppliers = false)
    {
        $this->query->orderBy('approved_at', 'desc'); // order by
        $this->query->setFilter('status', [ 'canceled', 'pending_approval' ], 'nin');
        $this->query->setFilter('reportable', 1);
        $this->query->setFilter('supplier', $thirdPartySuppliers ? 1 : 0);
        if ($contractGroup) {
            $this->query->setFilter('contract_group_id', $contractGroup);
        }
        if ($group && ! empty( $group )) {
            $this->query->setFilter('customer_group_id', $group);
        }
    }

    /**
     * @return array
     */
    protected function getApprovedOrders()
    {
        $response = $this->portalRepository->getOrders($this->query);

        return $this->formatOrderReportResponse($response);
    }

    /**
     * @param $deliveries
     *
     * @return array
     */
    protected function getDeliveredTotals($deliveries)
    {
        $deliveryTotals = [ ];
        foreach ($deliveries as $delivery) {
            foreach ($delivery['shipment_items']['data'] as $item) {
                $deliveryTotals[$item['sku']] = isset( $deliveryTotals[$item['sku']] ) ? $deliveryTotals[$item['sku']] + $item['qty'] : $item['qty'];
            }
        }

        return $deliveryTotals;
    }

    /**
     * @param $response
     *
     * @return array
     */
    protected function formatOrderReportResponse($response)
    {
        $fieldsMap    = [
            'weborder'              => 'increment_id',
            'grand_total'           => 'total',
            'ship_to'               => 'contract_shipping',
            'contract_display_name' => 'contract'
        ];
        $reportFields = [
            'entity_id',
            'increment_id',
            'grand_total',
            'ship_to',
            'status',
            'customer',
            'contract_display_name',
            'ordered_by',
            'created_at',
            'updated_at',
            'approved_by',
            'approved_at',
            'customer_email',
            'week',
            'month',
            'quarter',
            'actual_delivery_date',
            'custom_ref1',
            'custom_ref2',
            'delivery_days'
        ];

        return $this->prepareOrderReportData($response, $reportFields, $fieldsMap);
    }

    /**
     * format the response data for each order report
     *
     * @param array $response     the web service response
     * @param array $reportFields the needed fields of the report
     * @param array $fieldsMap    some of the fields can have different keys from the response, this array is used to
     *                            map between them
     *
     * @return array
     */
    private function prepareOrderReportData($response, $reportFields, $fieldsMap = [ ])
    {
        $orders        = [ ];
        $orderStatuses = [
            'pending_approval'    => 'Pending Approval',
            'pending'             => 'Approved',
            'processing'          => 'Processing',
            'partially_delivered' => 'Partially Delivered',
            'fully_delivered'     => 'Delivery in Full',
            'complete'            => 'Complete',
            'holded'              => 'On Hold'
        ];
        $holidays      = getConfiguredPublicHolidays();
        foreach ($response as $order) {
            $_order = [ ];
            foreach ($reportFields as $field) {
                $key = isset( $fieldsMap[$field] ) ? $fieldsMap[$field] : $field; // look for the actual key in the response
                // calculate delivery days
                if ($key == 'delivery_days') {
                    if (isset( $order['actual_delivery_date'] )) {
                        $days = $this->helper->numberOfWorkingDays($order['approved_at'], $order['actual_delivery_date'], 5,
                            $holidays);
                    } else {
                        $days = $this->helper->numberOfWorkingDays($order['approved_at'], '', 5, $holidays);
                    }
                    $order[$key] = (string) $days;
                }
                // order status code to label
                if ($key == 'status' && isset( $order[$key] ) && array_key_exists($order[$key], $orderStatuses)) {
                    $order[$key] = $orderStatuses[$order[$key]];
                }
                $_order[$field] = isset( $order[$key] ) && $order[$key] != '' ? $order[$key] : '';
            }
            $orders[] = $_order;
        }

        return $orders;
    }

}
