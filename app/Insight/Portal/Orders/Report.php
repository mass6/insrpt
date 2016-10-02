<?php
namespace Insight\Portal\Orders;

use Illuminate\Support\Facades\Log;
use Insight\Portal\Connections\PortalRestClient;
use Insight\Portal\Exceptions\WebservicesUnavailableException;
use DateTime;
use DateTimeZone;

class Report
{
    private $_restClient = null;

    public function __construct(PortalRestClient $client)
    {
        $this->_restClient = $client;
    }

    public function getRestClient()
    {
        return $this->_restClient;
    }

    /**
     * get products ordered report
     * callback function is used to format the response.
     * This may be useful for future formatting the response grouped by contract.
     * @param mixed $filters
     * @param string $callback
     * @return mixed|null
     */
    //public function getProductsOrderedReport($filters, $callback = '')
    //{
    //    try {
    //        $client = $this->getRestClient();
    //        Log::info('////// Filters //////');
    //        Log::info($filters);
    //        $response = $client->getJSON('products-ordered', $filters);
    //        Log::info($response);
    //    } catch (WebservicesUnavailableException $e) {
    //        $response = array('data' => array());
    //    }
    //    if ($callback) {
    //        $response = $this->$callback($response);
    //    }
    //    return $response;
    //}

    //public function prepareProductsOrderedResponse($response)
    //{
    //    $products = array();
    //    $periods = array();
    //    foreach ($response['data'] as $period) {
    //        $periods[] = $period['period'];
    //        foreach ($period['products_ordered']['data'] as $product) {
    //            if (empty($product['sku']))
    //                continue;
    //            if (!array_key_exists($product['sku'], $products)) {
    //                $products[$product['sku']] = array(
    //                    'name' => $product['name'],
    //                    'sku' => $product['sku'],
    //                    'uom' => $product['uom'],
    //                    'price' => $product['unit_price'],
    //                    'qty_ordered' => array(
    //                        $period['period'] => $product['qty_ordered']
    //                    )
    //                );
    //            } else {
    //                $products[$product['sku']]['qty_ordered'][$period['period']] = $product['qty_ordered'];
    //            }
    //        }
    //    }
    //
    //    return compact('products', 'periods');
    //}

    //public function getReport($reportName, $format = 'json', $params = array())
    //{
    //    $func = 'get' . $reportName;
    //    //Log::info($func);
    //    if (in_array($reportName, array('OrdersToday', 'OrdersYesterday', 'OrdersThisMonth', 'OrdersThirdPartyThisMonth', 'OrdersLastMonth', 'OrdersYtd', 'Custom'))) {
    //        $func = 'getApprovedOrdersReport';
    //    }
    //    if (!is_callable(array($this, $func))) {
    //        return false;
    //    }
    //
    //    try {
    //        $params['timezone'] = @date_default_timezone_get();
    //        //Log::info('/////////////////////////// API //////////////////////////');
    //        //Log::info($params);
    //        //Log::info($reportName);
    //        $response = $this->$func($params, $reportName);
    //    } catch (WebservicesUnavailableException $e) {
    //        $response = array();
    //    }
    //
    //    if ($format == 'json' && is_array($response)) {
    //        $response = json_encode($response);
    //    }
    //
    //    return $response;
    //}

    /**
     * @param array $params `
     * @param string $reportName
     * @return array
     */
//    public function getOrdersPendingApproval($params, $reportName = '')
//    {
////        return false; // uncomment this to use old API
//        $params['fields'] = 'increment_id,status,created_at,updated_at,approver,approved,custom_ref1,custom_ref2,lead_time_hours';
//        $params['sub_call'] = ['order_customer'];
//        $params['order'] = 'created_at'; // order by
//        $params['dir'] = 'desc'; // order direction
//        $params['filter'][] = ['attribute' => 'status', 'eq' => 'pending_approval'];
//
//        $response = $this->getRestClient()->getJSON('orders', $params);
//
//        $fieldsMap = array('weborder' => 'increment_id');
//        $reportFields = array('entity_id', 'weborder', 'total', 'customer', 'contract', 'status', 'created_at', 'updated_at', 'lead_time_hours', 'total_lead_time_hours', 'ordered_by', 'current_approver', 'last_approver', 'custom_ref1', 'custom_ref2');
//        return $this->_prepareOrderReportData($response, $reportFields, $fieldsMap);
//    }

    /**
     * @param $params
     * @param $reportName
     * @return array
     */
//    public function getApprovedOrdersReport($params, $reportName)
//    {
////        return false; // uncomment this to use old API
//        $params['fields'] = 'increment_id,contract_shipping,status,created_at,updated_at,approved_by,approved_at,actual_delivery_date,custom_ref1,custom_ref2';
//        $params['order'] = 'approved_at'; // order by
//        $params['dir'] = 'desc'; // order direction
//        $params['filter'][] = [
//            'attribute' => 'status',
//            'nin' => ['canceled', 'pending_approval']
//        ];
//        $params['filter'][] = [
//            'attribute' => 'reportable',
//            'eq' => 1
//        ];
//        // specific filters for each report
//        // datetime is in UTC, so we have to convert the datetime range to UTC first
//        $supplierFilterValue = 0;
//        switch ($reportName) {
//            case 'OrdersToday':
//                $from = $this->convertDatetime('today 00:00:00', $params['timezone'], 'UTC');
//                $to = $this->convertDatetime('today 23:59:59', $params['timezone'], 'UTC');
//                break;
//            case 'OrdersYesterday':
//                $from = $this->convertDatetime('yesterday 00:00:00', $params['timezone'], 'UTC');
//                $to = $this->convertDatetime('yesterday 23:59:59', $params['timezone'], 'UTC');
//                break;
//            case 'OrdersThisMonth':
//                $from = $this->convertDatetime('first day of this month 00:00:00', $params['timezone'], 'UTC');
//                $to = $this->convertDatetime('last day of this month 23:59:59', $params['timezone'], 'UTC');
//                break;
//            case 'OrdersThirdPartyThisMonth':
//                $from = $this->convertDatetime('first day of this month 00:00:00', $params['timezone'], 'UTC');
//                $to = $this->convertDatetime('last day of this month 23:59:59', $params['timezone'], 'UTC');
//                $supplierFilterValue = 1;
//                break;
//            case 'OrdersLastMonth':
//                $from = $this->convertDatetime('first day of last month 00:00:00', $params['timezone'], 'UTC');
//                $to = $this->convertDatetime('last day of last month 23:59:59', $params['timezone'], 'UTC');
//                break;
//            case 'OrdersYtd':
//                $from = $this->convertDatetime('Jan 1 00:00:00', $params['timezone'], 'UTC');
//                $to = $this->convertDatetime('Dec 31 23:59:59', $params['timezone'], 'UTC');
//                break;
//            case 'Custom':
//                $filters = $params['report_query'];
//                $supplierFilterValue = $filters['supplier'];
//                if ($filters['date'] == 'range') {
//                    // should be aware of user input
//                    try {
//                        $from = $this->convertDatetime($filters['from'], $params['timezone'], 'UTC');
//                        $to = $this->convertDatetime($filters['to'] . ' 23:59:59', $params['timezone'], 'UTC');
//                    } catch (\Exception $e) {
//                        $from = $to = '';
//                    }
//                }
//                unset($params['report_query']);
//                break;
//            default:
//                $from = $to = '';
//                break;
//        }
//
//        if ($supplierFilterValue !== '')
//            $params['filter'][] = ['attribute' => 'supplier', 'eq' => $supplierFilterValue];
//        if (!empty($from))
//            $params['filter'][] = ['attribute' => 'approved_at', 'gteq' => $from];
//        if (!empty($to))
//            $params['filter'][] = ['attribute' => 'approved_at', 'lteq' => $to];
//
//        $response = $response = $this->getRestClient()->getJSON('orders', $params);
//
//        $fieldsMap = array('weborder' => 'increment_id', 'grand_total' => 'total', 'ship_to' => 'contract_shipping', 'contract_display_name' => 'contract');
//        $reportFields = array('entity_id', 'increment_id', 'grand_total', 'ship_to', 'status', 'customer', 'contract_display_name', 'ordered_by', 'created_at', 'updated_at', 'approved_by', 'approved_at', 'customer_email', 'week', 'month', 'quarter', 'actual_delivery_date', 'custom_ref1', 'custom_ref2', 'delivery_days');
//        return $this->_prepareOrderReportData($response, $reportFields, $fieldsMap);
//    }

    /**
     * format the response data for each order report
     * @param array $response the web service response
     * @param array $reportFields the needed fields of the report
     * @param array $fieldsMap some of the fields can have different keys from the response, this array is used to map between them
     * @return array
     */
    //private function _prepareOrderReportData($response, $reportFields, $fieldsMap = array())
    //{
    //    $orders = array();
    //    $orderStatuses = [
    //        'pending_approval' => 'Pending Approval',
    //        'pending' => 'Approved',
    //        'processing' => 'Processing',
    //        'partially_delivered' => 'Partially Delivered',
    //        'fully_delivered' => 'Delivery in Full',
    //        'complete' => 'Complete',
    //        'holded' => 'On Hold'
    //    ];
    //    $helper = new Helper();
    //    $holidays = $this->getConfiguredPublicHolidays();
    //    foreach ($response as $order) {
    //        $_order = array();
    //        foreach ($reportFields as $field) {
    //            $key = isset($fieldsMap[$field]) ? $fieldsMap[$field] : $field; // look for the actual key in the response
    //            // calculate delivery days
    //            if ($key == 'delivery_days') {
    //                if (isset($order['actual_delivery_date'])) {
    //                    $days = $helper->numberOfWorkingDays($order['approved_at'], $order['actual_delivery_date'], 5, $holidays);
    //                } else {
    //                    $days = $helper->numberOfWorkingDays($order['approved_at'], '', 5, $holidays);
    //                }
    //                $order[$key] = (string)$days;
    //            }
    //            // order status code to label
    //            if ($key == 'status' && isset($order[$key]) && array_key_exists($order[$key], $orderStatuses)) {
    //                $order[$key] = $orderStatuses[$order[$key]];
    //            }
    //            $_order[$field] = isset($order[$key]) && $order[$key] != '' ? $order[$key] : '';
    //        }
    //        $orders[] = $_order;
    //    }
    //    return $orders;
    //}

    //public function convertDatetime($input, $sourceTimezone, $targetTimezone, $format = 'Y-m-d H:i:s')
    //{
    //    if (!$input || !$targetTimezone)
    //        return $input;
    //    $date = new DateTime($input, new DateTimeZone($sourceTimezone));
    //    $date->setTimezone(new DateTimeZone($targetTimezone));
    //    return $date->format($format);
    //}

    //public function getDaysBetween($start, $end)
    //{
    //    return round(abs(strtotime($end) - strtotime($start)) / 86400);
    //}

    /**
     * Search orders using REST api
     * @param string $q query string
     * @param string $from from date
     * @param string $to to date
     * @param int $customerGroupId current customer group ID
     * @return array
     */
    //public function getOrderSearchResults($q, $from = '', $to = '', $customerGroupId = null)
    //{
    //    $params = [
    //        'timezone' => @date_default_timezone_get(),
    //        'fields' => 'increment_id,created_at,status,actual_delivery_date',
    //        'order' => 'created_at',
    //        'dir' => 'desc',
    //        'filter' => [
    //            [
    //                'attribute' => 'view',
    //                'eq' => '0'
    //            ],
    //            [
    //                'attribute' => 'status',
    //                'nin' => ['canceled']
    //            ]
    //        ]
    //    ];
    //    if ($customerGroupId) {
    //        $params['filter'][] = [
    //            'attribute' => 'customer_group_id',
    //            'eq' => $customerGroupId
    //        ];
    //    }
    //    if ($from) {
    //        $from = $this->convertDatetime($from . ' 00:00:00', $params['timezone'], 'UTC');
    //        $params['filter'][] = [
    //            'attribute' => 'created_at',
    //            'gteq' => $from
    //        ];
    //    }
    //    if ($to) {
    //        $to = $this->convertDatetime($to . ' 00:00:00', $params['timezone'], 'UTC');
    //        $params['filter'][] = [
    //            'attribute' => 'created_at',
    //            'lteq' => $to
    //        ];
    //    }
    //    if ($q) {
    //        // OR condition format
    //        $searchConditions = [
    //            ['increment_id', 'status', 'customer_firstname', 'customer_lastname', 'contract_shipping', 'cname', 'code'],
    //            [
    //                ['like' => '%' . $q . '%'],
    //                ['like' => '%' . $q . '%'],
    //                ['like' => '%' . $q . '%'],
    //                ['like' => '%' . $q . '%'],
    //                ['like' => '%' . $q . '%'],
    //                ['like' => '%' . $q . '%'],
    //                ['like' => '%' . $q . '%']
    //            ]
    //        ];
    //        $params['filter'][] = $searchConditions;
    //    }
    //    $response = $this->getRestClient()->getJSON('orders', $params);
    //    $orders = [];
    //    $badge = [
    //        'pending' => 'badge-success',
    //        'pending_approval' => 'badge-warning',
    //        'complete' => 'badge-success',
    //        'processing' => 'badge-info',
    //        'partially_delivered' => 'badge-info',
    //        'fully_delivered' => 'badge-info',
    //        'canceled' => 'badge-danger'
    //    ];
    //    foreach ($response as $item) {
    //        $order = [
    //            'entity_id' => $item['entity_id'],
    //            'web_order' => $item['increment_id'],
    //            'contract' => $item['contract'],
    //            'grand_total' => $item['total'],
    //            'created_at' => date('d M Y', strtotime($item['created_at'])),
    //            'status' => $item['status'] == 'pending' ? 'approved' : $item['status'],
    //            'badge' => isset($badge[$item['status']]) ? $badge[$item['status']] : '',
    //            'actual_delivery_date' => $item['actual_delivery_date']
    //        ];
    //        $orders[] = $order;
    //    }
    //    return $orders;
    //}

    /**
     * get daily orders report using REST
     * @param $reportDate
     * @param $websiteCode
     */
    //public function getDailyOrders($reportDate, $websiteCode)
    //{
    //    if (!$reportDate) {
    //        $reportDate = date('Y-m-d');
    //    }
    //    $params = ['timezone' => getenv('APP_TIMEZONE')];
    //    $params['fields'] = 'increment_id,store_id,customer_id,customer_email,approved_at,contract_id,contractship,custom_ref1,custom_ref2,custom_ref3,custom_ref4,custom_ref5';
    //    $params['sub_call'] = ['order_item', 'order_contract', 'order_reference', 'order_customer', 'order_comment'];
    //    $params['order'] = 'created_at'; // order by
    //    $params['dir'] = 'desc'; // order direction
    //    $params['filter'] = [
    //        [
    //            'attribute' => 'reportable',
    //            'eq' => 1
    //        ],
    //        [
    //            'attribute' => 'website',
    //            'eq' => $websiteCode
    //        ]
    //    ];
    //
    //    try {
    //        $from = $this->convertDatetime($reportDate . " 00:00:00", getenv('APP_TIMEZONE'), 'UTC');
    //        $to = $this->convertDatetime($reportDate . " 23:59:59", getenv('APP_TIMEZONE'), 'UTC');
    //        $params['filter'][] = ['attribute' => 'approved_at', 'gteq' => $from];
    //        $params['filter'][] = ['attribute' => 'approved_at', 'lteq' => $to];
    //    } catch (\Exception $e) {
    //        throw new \InvalidArgumentException("Invalid report date.");
    //    }
    //
    //    try {
    //        $response = $this->getRestClient()->getJSON('orders', $params);
    //    } catch (\Exception $e) {
    //        $response = array();
    //    }
    //
    //    return $response;
    //}

    //public function getCustomProductsReport($params, $reportName)
    //{
    //    $params['fields'] = 'entity_id,increment_id,created_at,approved_by,approved_at,contract_id,contractship,customer_note,custom_ref1,custom_ref2,custom_ref3,custom_ref4,custom_ref5';
    //    $params['sub_call'] = ['order_item', 'order_contract', 'order_reference', 'order_customer'];
    //    $params['order'] = 'approved_at'; // order by
    //    $params['dir'] = 'desc'; // order direction
    //    $params['filter'][] = [
    //        'attribute' => 'status',
    //        'nin' => ['canceled', 'pending_approval']
    //    ];
    //    $params['filter'][] = [
    //        'attribute' => 'reportable',
    //        'eq' => 1
    //    ];
    //    $filters = $params['report_query'];
    //    $supplierFilterValue = $filters['supplier'];
    //    if ($filters['date'] == 'range') {
    //        // should be aware of user input
    //        try {
    //            $from = $this->convertDatetime($filters['from'], $params['timezone'], 'UTC');
    //            $to = $this->convertDatetime($filters['to'] . ' 23:59:59', $params['timezone'], 'UTC');
    //        } catch (\Exception $e) {
    //            $from = $to = '';
    //        }
    //    }
    //    unset($params['report_query']);
    //    if ($supplierFilterValue !== '')
    //        $params['filter'][] = ['attribute' => 'supplier', 'eq' => $supplierFilterValue];
    //    if (!empty($from))
    //        $params['filter'][] = ['attribute' => 'approved_at', 'gteq' => $from];
    //    if (!empty($to))
    //        $params['filter'][] = ['attribute' => 'approved_at', 'lteq' => $to];
    //
    //    try {
    //        $response = $this->getRestClient()->getJSON('orders', $params);
    //    } catch (\Exception $e) {
    //        $response = array();
    //    }
    //
    //    $helper = new Helper();
    //    $products = array();
    //    foreach ($response as $order) {
    //        if (!isset($order['order_items']))
    //            continue;
    //        $shippingAddress = '';
    //        if (isset($order['contract_data']) && $order['contractship'] != '') {
    //            $contract = $order['contract_data'];
    //            $i = abs((int)$order['contractship'] - 10000);
    //            if (isset($contract['name_ship'.$i])) {
    //                $shippingAddress = implode(' ', array($contract['name_ship'.$i], $contract['street_ship'.$i], $contract['street1_ship'.$i], $contract['city_ship'.$i], $contract['state_ship'.$i], $contract['zip_ship'.$i]));
    //            }
    //        }
    //        foreach ($order['order_items'] as $item) {
    //            $categories = [];
    //            $finalCategory = '';
    //            $combinedCategories = '';
    //            if (isset($item['categories'])) {
    //                foreach ($item['categories'] as $key => $category) {
    //                    $categories[$key + 1] = $category['name'];
    //                }
    //                $combinedCategories = implode(' | ', $categories);
    //                $finalCategory = count($categories) ? $categories[count($categories)] : '';
    //            }
    //            $products[] = array(
    //                'entity_id' => $order['entity_id'],
    //                'increment_id' => $order['increment_id'],
    //                'customer' => $order['customer'],
    //                'created_at' => $order['created_at'],
    //                'ordered_by' => $order['ordered_by'],
    //                'approved_at' => $order['approved_at'],
    //                'contract' => $order['contract'],
    //                'contract_id' => $order['contract_id'],
    //                'contract_site' => isset($order['contract_data']) ? $order['contract_data']['site'] : '',
    //                'contract_contractor' => isset($order['contract_data']) ? $order['contract_data']['contractor'] : '',
    //                'ship_to' => $shippingAddress,
    //                'customer_note' => $order['customer_note'],
    //                'sku' => $item['sku'],
    //                'partner_code' => $item['bp_product_code'],
    //                'name' => $item['name'],
    //                'uom' => isset($item['uom']) ? $item['uom'] : '',
    //                'qty' => (int)$item['qty_ordered'],
    //                'qty_shipped' => (int)$item['qty_shipped'],
    //                'qty_received' => (int)$item['qty_received'],
    //                'qty_received_updated_at' => $item['qty_received_updated_at'],
    //                'qty_received_updated_by' => $item['qty_received_updated_by'],
    //                'qty_canceled' => (int)$item['qty_canceled'],
    //                'qty_invoiced' => (int)$item['qty_invoiced'],
    //                'qty_refunded' => (int)$item['qty_refunded'],
    //                'price' => $helper->formatNumber($item['price']),
    //                'row_total' => $helper->formatNumber($item['row_total']),
    //                'supplier' => $item['supplier'],
    //                'attribute_set_name' => $item['attribute_set_name'],
    //                'chargeable' => $item['chargeable'],
    //                'category' => $finalCategory,
    //                'category_level1' => array_get($categories, 1),
    //                'category_level2' => array_get($categories, 2),
    //                'category_level3' => array_get($categories, 3),
    //                'category_level4' => array_get($categories, 4),
    //                'category_level5' => array_get($categories, 5),
    //                'all_categories' => $combinedCategories,
    //                'custom_ref1' => $order['custom_ref1'],
    //                'custom_ref2' => $order['custom_ref2'],
    //                'custom_ref3' => $order['custom_ref3'],
    //                'custom_ref4' => $order['custom_ref4'],
    //                'custom_ref5' => $order['custom_ref5']
    //            );
    //        }
    //    }
    //
    //    return $products;
    //}

    //public function getConfiguredPublicHolidays()
    //{
    //    $holidays = array();
    //    $obj = \Insight\Settings\Setting::whereName('system')->first();
    //    if ($obj) {
    //        $settings = $obj->settings()->all();
    //        if ($str = array_get($settings, 'default_public_holidays')) {
    //            $holidays = explode(',', $str);
    //        }
    //    }
    //    return $holidays;
    //}
}