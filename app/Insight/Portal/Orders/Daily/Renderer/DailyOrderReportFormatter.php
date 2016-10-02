<?php

namespace Insight\Portal\Orders\Daily\Renderer;

use Insight\Portal\Orders\Daily\FieldFormatters\FieldFormatterFactory;

class DailyOrderReportFormatter
{

    private $orders;
    private $reportFields;
    private $report;
    private $currentIncrementId;
    private $currentOrderLineNumber;
    private $column;


    public function __construct($orders, $reportFields)
    {
        $this->orders       = $orders;
        $this->reportFields = $reportFields;
        $this->report       = [];
        $this->currentIncrementId = '';
        $this->currentOrderLineNumber = 0;
    }

    public function format()
    {
        foreach ($this->orders as $order) {
            $report = $this->generateOrderReportLine($order);
        }

        return $report;
    }

    protected function generateOrderReportLine($order)
    {
        $reportFields = $this->reportFields;

        foreach ($order['order_items'] as $orderLineItemDetails) {
            $this->setOrderLineNumber($order);

            $reportRow = [];
            foreach ($reportFields as $key => $reportField) {
                $columnData = $this->setFieldData($order, $reportField, $orderLineItemDetails);
                if ($columnData) {
                    $reportRow[$columnData['name']] = $columnData['value'];
                }
            }
            $this->report[] = $reportRow;
        }

        return $this->report;
    }

    /**
     * @param $order
     */
    protected function setOrderLineNumber($order)
    {
        if ($order['increment_id'] == $this->currentIncrementId) {
            $this->currentOrderLineNumber++;
        } else {
            $this->currentIncrementId     = $order['increment_id'];
            $this->currentOrderLineNumber = 1;
        }
    }
    protected function setFieldData($order, $field, $orderLineItemDetails)
    {
        if (! array_get($field, 'enabled', false)) {
            return false;
        }
        if ($field['src'] == 'user_defined') {
            $fieldData = $this->setColumnValueFromUserDefinedField($field);

            return $fieldData;
        } else {
            $fieldData = $this->setColumnValueFromOrderData($field, $order, $orderLineItemDetails);

            return $fieldData;
        }
    }
    protected function setColumnValueFromUserDefinedField($field)
    {
        return ['name' => $field['label'], 'value' => $this->applyFieldFormatting($field)];
    }

    protected function setColumnValueFromOrderData($field, $order, $orderLineItemDetails)
    {
        $field['value'] = $this->getFieldValue($field, $orderLineItemDetails, $order) ?: '';

        return [
            'name' => array_get($field, 'label') ?: $field['default_label'],
            'value' => $this->applyFieldFormatting($field)
        ];
    }

    /**
     * @param $field
     * @param $orderLineItemDetails
     * @param $order
     *
     * @return mixed
     */
    protected function getFieldValue($field, $orderLineItemDetails, $order)
    {
        if (array_get($field, 'type') == 'calculated') {
            return $this->getCalculatedFieldValue($field['src'], $order, $orderLineItemDetails);
        }

        $fieldName = substr($field['src'], strpos($field['src'], ".") + 1);
        if (! $value = array_get($orderLineItemDetails, $fieldName, null)) {
            $value = array_get($order, $field['src'], null);
        }

        return $value;
    }

    protected function getCalculatedFieldValue($fieldName, $order, $orderLineItemDetails)
    {
        $value = null;
        if (substr($fieldName,0,7) ===  'ship_to') {
            $value = $this->getShippingAddressField($fieldName, $order);
        }
        elseif ($fieldName == 'line_number') {
            $value = $this->currentOrderLineNumber;
        }
        elseif ($fieldName == 'customer_note') {
            $value = $this->getCustomerNote($order, $orderLineItemDetails);
        } elseif ($fieldName == 'delivery_date') {
            $value = $this->calculateDeliveryDate($order);
        } elseif ($fieldName == 'units') {
            $value = $this->calculateUnits($orderLineItemDetails);
        }
        else {
            throw new \Exception('No Calculation Found for field ' . $fieldName);
        }

        return $value;
    }

    protected function getShippingAddressField($fieldName, $order)
    {
        $addressIndex = $this->getAddressIndex($order);

        if ($fieldName == 'ship_to') {
            return $this->getShippingAddress($order, $addressIndex);
        } else {
            $source = substr($fieldName, strlen('ship_to_')) . '_ship' . $addressIndex;

            return array_get($order, 'contract_data.' . $source);
        }
    }
    protected function getAddressIndex($order)
    {
        if (isset($order['contract_data']) && $order['contractship'] != '') {

            return abs((int)$order['contractship'] - 10000);
        }
    }

    protected function getShippingAddress($order, $addressIndex)
    {
        $shippingAddress = '';
        $contract = $order['contract_data'];

        if (isset($contract['name_ship'.$addressIndex])) {
            $shippingAddress = implode(' ', array($contract['name_ship'.$addressIndex], $contract['street_ship'.$addressIndex], $contract['street1_ship'.$addressIndex], $contract['city_ship'.$addressIndex], $contract['state_ship'.$addressIndex], $contract['zip_ship'.$addressIndex]));
        }

        return $shippingAddress;
    }

    protected function getCustomerNote($order)
    {
         $customerNote = '';
        if (isset($order['order_comments']) && isset($order['order_comments'][0])) {
            // order comments are sorted by date, asc, so customer note is the first comment
            $customerNote = $order['order_comments'][0]['comment'];
        }

        return $customerNote;
    }

    protected function calculateDeliveryDate($order)
    {
        $estDeliveryDate = '';
        if (isset($order['approved_at'])) {
            $estDeliveryDate = date('d/m/Y', strtotime('+3 days', strtotime($order['approved_at'])));
        }

        return $estDeliveryDate;
    }

    protected function calculateUnits($orderItem)
    {
        $uom = '';
        if (isset($orderItem['uom'])) {
            $uom = strpos($orderItem['uom'], ' ') ? substr($orderItem['uom'], 0, strpos($orderItem['uom'], ' ')) : $orderItem['uom']; // get the first word
        }

        return $uom;
    }


    protected function applyFieldFormatting($field)
    {
        $value = $field['value'];
        if ($type = array_get($field, 'type')) {
            if ($format = array_get($field, 'formatting')) {
                $value = FieldFormatterFactory::make($type)->format($value, $format);
            }
        }
        return $value;
    }

}
