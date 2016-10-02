<?php
namespace Insight\Portal\Orders\Daily\Renderer;

use Insight\Portal\Orders\Helper;

class Emrill extends Base
{
    public function getFilename()
    {
        return mktime(date('now')) . '-EmrillOrderReport';
    }

    public function getTitle()
    {
        return 'EmrillDailyOrderReport';
    }

    public function prepareSpreadsheetData()
    {
        $data = array();
        $helper = new Helper();
        foreach ($this->getOrdersData() as $order)
        {
            if (!isset($order['order_items']))
                continue;

            $estDeliveryDate = '';
            if (isset($order['approved_at'])) {
                $estDeliveryDate = date('d/m/Y', strtotime('+3 days', strtotime($order['approved_at'])));
            }
            foreach ($order['order_items'] as $item) {
                $uom = '';
                if (isset($item['uom'])) {
                    $uom = strpos($item['uom'], ' ') ? substr($item['uom'], 0, strpos($item['uom'], ' ')) : $item['uom']; // get the first word
                }
                $data[] = array(
                    'Document No' => '',
                    'Old PO No' => '',
                    'Date' => '',
                    'Vendor Name' => 'Thirty Six Strategies General Trading LLC',
                    'Vendor Code' => '311102074',
                    'Narration' => $order['increment_id'],
                    'Buyer' => '117153',
                    'Purchase Type' => 'Inventory',
                    'Projects' => isset($order['contract_data']) ? $order['contract_data']['code'] : '',
                    'Company' => '10',
                    'Branch' => isset($order['contract_data']) ? $order['contract_data']['zip_ship1'] : '',
                    'Location Service' => '1099',
                    'Job No' => 'NA',
                    'New Item Code' => $item['bp_product_code'],
                    'Old Item Code' => '',
                    'Item Description' => $item['name'],
                    'Qty' => (int)$item['qty_ordered'],
                    'Units' => $uom,
                    'Rate' => $helper->formatNumber($item['price']),
                    'Amount' => $helper->formatNumber($item['row_total']),
                    'Discount %' => '0',
                    'Other Chgrs' => '',
                    'Net Amount' => $helper->formatNumber($item['row_total']),
                    'Del Instruc' => '',
                    'Specification' => '',
                    'Delivery Date' => $estDeliveryDate,
                    'Remarks' => '',
                    'PR NO' => $order['increment_id'],
                    'PO No' => '',
                    'MR No' => $order['increment_id'],
                    'Del To Location' => isset($order['contract_data']) ? $order['contract_data']['street_ship1'] : '',
                    'Del PoC' => isset($order['customer_data']['employee_id']) ? $order['customer_data']['employee_id'] : '',
                    'Del Telephone Number' => '',
                    'Warranty' => '',
                    'Action' => '',
                    'Issues / Comments' => '',
                    'Payment Terms' => '30',
                    'Comments For Auth' => '',
                    'Service Type' => 'Inventory',
                    'Attach Doc' => '',
                    'Budget Amt' => '',
                    'Cur Spd Amt' => '',
                    'Avail Budget' => '',
                    'Old LPO Date' => ''
                );
            }
        }

        $this->setSpreadsheetData($data);
        return parent::prepareSpreadsheetData();
    }
}