<?php

namespace Insight\Portal\Orders;

class DailyOrderReport
{
        public static $defaultConfiguration = [
            'field_definitions' => [
                'increment_id' => [
                    'src' => 'increment_id',
                    'type' => 'text',
                    'default_label' => 'Order ID',
                    'default' => true,
                    'enabled' => true,
                    'position' => 0,
                ],
                'customer' => [
                    'src' => 'customer',
                    'type' => 'text',
                    'default_label' => 'Customer',
                    'default' => true,
                    'enabled' => true,
                    'position' => 1,
                ],
                'customer_email' => [
                    'src' => 'customer_email',
                    'type' => 'text',
                    'default_label' => 'Ordered By',
                    'default' => true,
                    'enabled' => true,
                    'position' => 2,
                ],
                'approved_at' => [
                    'src' => 'approved_at',
                    'type' => 'date',
                    'default_label' => 'Approved Date',
                    'default' => true,
                    'enabled' => true,
                    'position' => 3,
                    'formatting' => [
                        'date_format' => 'Y-m-d H:i:s'
                    ],
                ],
                'contract' => [
                    'src' => 'contract',
                    'type' => 'text',
                    'default_label' => 'Contract',
                    'default' => true,
                    'enabled' => true,
                    'position' => 4,
                ],
                'contract_data.code' => [
                    'src' => 'contract_data.code',
                    'type' => 'text',
                    'default_label' => 'Contract ID',
                    'default' => true,
                    'enabled' => true,
                    'position' => 5,
                ],
                'ship_to' => [
                    'src' => 'ship_to',
                    'type' => 'calculated',
                    'default_label' => 'Ship To',
                    'default' => true,
                    'enabled' => true,
                    'position' => 6,
                ],
                'customer_note' => [
                    'src' => 'customer_note',
                    'type' => 'calculated',
                    'default_label' => 'Customer Note',
                    'default' => true,
                    'enabled' => true,
                    'position' => 7,
                ],
                'order_items.sku' => [
                    'src' => 'order_items.sku',
                    'type' => 'text',
                    'default_label' => 'Product SKU',
                    'default' => true,
                    'enabled' => true,
                    'position' => 8,
                ],
                'order_items.bp_product_code' => [
                    'src' => 'order_items.bp_product_code',
                    'type' => 'text',
                    'default_label' => 'Partner Code',
                    'default' => true,
                    'enabled' => true,
                    'position' => 9,
                ],
                'order_items.name' => [
                    'src' => 'order_items.name',
                    'type' => 'text',
                    'default_label' => 'Product Name',
                    'default' => true,
                    'enabled' => true,
                    'position' => 10,
                ],
                'order_items.uom' => [
                    'src' => 'order_items.uom',
                    'type' => 'text',
                    'default_label' => 'UOM',
                    'default' => true,
                    'enabled' => true,
                    'position' => 11,
                ],
                'order_items.qty_ordered' => [
                    'src' => 'order_items.qty_ordered',
                    'type' => 'number',
                    'default_label' => 'QTY',
                    'default' => true,
                    'enabled' => true,
                    'position' => 12,
                    'formatting' => [
                        'decimal_places' => 0,
                    ],
                ],
                'order_items.price' => [
                    'src' => 'order_items.price',
                    'type' => 'number',
                    'default_label' => 'Price',
                    'default' => true,
                    'enabled' => true,
                    'position' => 13,
                    'formatting' => [
                        'decimal_places' => 2,
                        'thousands_separator' => 'comma',
                    ],
                ],
                'order_items.row_total' => [
                    'src' => 'order_items.row_total',
                    'type' => 'number',
                    'default_label' => 'Row Total',
                    'default' => true,
                    'enabled' => true,
                    'position' => 14,
                    'formatting' => [
                        'decimal_places' => 2,
                        'thousands_separator' => 'comma',
                    ],
                ],
                'order_items.supplier' => [
                    'src' => 'order_items.supplier',
                    'type' => 'text',
                    'default_label' => 'Supplier',
                    'default' => false,
                    'enabled' => true,
                    'position' => 15,
                ],
                'custom_ref1' => [
                    'src' => 'custom_ref1',
                    'type' => 'text',
                    'default_label' => 'Custom Reference 1',
                    'default' => false,
                    'enabled' => true,
                    'position' => 16,
                ],
                'custom_ref2' => [
                    'src' => 'custom_ref2',
                    'type' => 'text',
                    'default_label' => 'Custom Reference 2',
                    'default' => false,
                    'enabled' => true,
                    'position' => 17,
                ],
                'custom_ref3' => [
                    'src' => 'custom_ref3',
                    'type' => 'text',
                    'default_label' => 'Custom Reference 3',
                    'default' => false,
                    'enabled' => true,
                    'position' => 18,
                ],
                'custom_ref4' => [
                    'src' => 'custom_ref4',
                    'type' => 'text',
                    'default_label' => 'Custom Reference 4',
                    'default' => false,
                    'enabled' => true,
                    'position' => 19,
                ],
                'ship_to_name' => [
                    'src' => 'ship_to_name',
                    'type' => 'calculated',
                    'default_label' => 'Ship To: Addr. Name',
                    'default' => false,
                    'enabled' => true,
                    'position' => 20,
                ],
                'ship_to_street' => [
                    'src' => 'ship_to_street',
                    'type' => 'calculated',
                    'default_label' => 'Ship To: Street L1',
                    'default' => false,
                    'enabled' => true,
                    'position' => 21,
                ],
                'ship_to_street1' => [
                    'src' => 'ship_to_street1',
                    'type' => 'calculated',
                    'default_label' => 'Ship To: Street L2',
                    'default' => false,
                    'enabled' => true,
                    'position' => 22,
                ],
                'ship_to_city' => [
                    'src' => 'ship_to_city',
                    'type' => 'calculated',
                    'default_label' => 'Ship To: City',
                    'default' => false,
                    'enabled' => true,
                    'position' => 23,
                ],
                'ship_to_state' => [
                    'src' => 'ship_to_state',
                    'type' => 'calculated',
                    'default_label' => 'Ship To: State',
                    'default' => false,
                    'enabled' => true,
                    'position' => 24,
                ],
                'ship_to_zip' => [
                    'src' => 'ship_to_zip',
                    'type' => 'calculated',
                    'default_label' => 'Ship To: Postal Code',
                    'default' => false,
                    'enabled' => true,
                    'position' => 25,
                ],
                'ship_to_country' => [
                    'src' => 'ship_to_country',
                    'type' => 'calculated',
                    'default_label' => 'Ship To: Country',
                    'default' => false,
                    'enabled' => true,
                    'position' => 26,
                ],
                'customer_data.employee_id' => [
                    'src' => 'customer_data.employee_id',
                    'type' => 'text',
                    'default_label' => 'Employee ID',
                    'default' => false,
                    'enabled' => true,
                    'position' => 27,
                ],
                'delivery_date' => [
                    'src' => 'delivery_date',
                    'type' => 'calculated',
                    'default_label' => 'Delivery Date',
                    'default' => false,
                    'enabled' => true,
                    'position' => 28,
                ],
                'line_number' => [
                    'src' => 'line_number',
                    'type' => 'calculated',
                    'default_label' => 'Line Number',
                    'default' => false,
                    'enabled' => true,
                    'position' => 29,
                ],
                'units' => [
                    'src' => 'units',
                    'type' => 'calculated',
                    'default_label' => 'Units',
                    'default' => false,
                    'enabled' => true,
                    'position' => 30,
                ],
            ],
        ];

        public function getColumns()
        {
            return self::$reportColumns;
        }

        public static function getDefaultFields()
        {
                $defaultFields = [];
                foreach (self::$defaultConfiguration['field_definitions'] as $key => $field) {
                        if ($field['default'] == true) {
                                $defaultFields[$key] = $field;
                        }
                }

                return ['field_definitions' => $defaultFields];
        }

}
