<?php

namespace Insight\Portal\Repositories;

use Carbon\Carbon;

class OrderQuery extends AbstractQuery
{
    public static $allowedFields = [
        "entity_id",
        "state",
        "status",
        "coupon_code",
        "shipping_description",
        "is_virtual",
        "store_id",
        "customer_id",
        "base_discount_amount",
        "base_grand_total",
        "base_shipping_amount",
        "base_shipping_tax_amount",
        "base_subtotal",
        "base_tax_amount",
        "base_total_paid",
        "base_total_refunded",
        "discount_amount",
        "grand_total",
        "shipping_amount",
        "shipping_tax_amount",
        "store_to_order_rate",
        "subtotal",
        "tax_amount",
        "total_paid",
        "total_refunded",
        "can_ship_partially",
        "can_ship_partially_item",
        "customer_is_guest",
        "customer_note_notify",
        "billing_address_id",
        "edit_increment",
        "email_sent",
        "forced_shipment_with_invoice",
        "shipping_address_id",
        "base_shipping_discount_amount",
        "base_subtotal_incl_tax",
        "base_total_due",
        "shipping_discount_amount",
        "subtotal_incl_tax",
        "total_due",
        "customer_dob",
        "increment_id",
        "base_currency_code",
        "customer_email",
        "customer_firstname",
        "customer_lastname",
        "customer_middlename",
        "customer_prefix",
        "customer_suffix",
        "customer_taxvat",
        "discount_description",
        "global_currency_code",
        "hold_before_state",
        "hold_before_status",
        "order_currency_code",
        "original_increment_id",
        "relation_child_id",
        "relation_child_real_id",
        "relation_parent_id",
        "relation_parent_real_id",
        "remote_ip",
        "shipping_method",
        "store_currency_code",
        "store_name",
        "customer_note",
        "created_at",
        "updated_at",
        "total_item_count",
        "customer_gender",
        "shipping_incl_tax",
        "SapSync",
        "l1",
        "l2",
        "l3",
        "l4",
        "l5",
        "approver",
        "approved",
        "approvers_level1",
        "approvers_level2",
        "approvers_level3",
        "approvers_level4",
        "approvers_level5",
        "contract_id",
        "contract_shipping",
        "contract_billing",
        "contractship",
        "view",
        "supplier",
        "override",
        "reportable",
        "approved_at",
        "approved_by",
        "orig_order_date",
        "partial_approval",
        "custom_ref1",
        "custom_ref2",
        "custom_ref3",
        "custom_ref4",
        "custom_ref5",
        "actual_delivery_date",
        "partner_sync",
        "materials_received_status",
        "ordered_by",
        "total",
        "contract",
        "customer",
        "customer_group_id",
        "lead_time_hours"
    ];

    public static $filterFields = ['website', 'contract_group_id'];

    protected $params = [];

    protected $timezone;

    protected $fields;

    protected $orderBy;

    protected $dir;

    protected $sub_call = [];

    protected $filter = [];


    public function __construct(Carbon $carbon)
    {
        parent::__construct($carbon);
        $this->timezone = getenv('APP_TIMEZONE') ?: 'UTC';
        $this->fields = 'increment_id,contract_shipping,status,created_at,updated_at,approved_by,approved_at,actual_delivery_date,custom_ref1,custom_ref2';
    }

    public function setAllSubCalls()
    {
        $this->setSubCalls(['order_item', 'order_contract', 'order_reference', 'order_customer', 'order_comment']);

        return $this;
    }
    public function setMultipleFieldFilter(Array $fields, $searchTerm)
    {
        $conditions = [];
        foreach ($fields as $field)
        {
            $conditions[] = [ 'like' => '%' . $searchTerm . '%' ];
        }
        $this->filter[] = [$fields, $conditions];
    }

    public function getQueryParams()
    {
        return $this->mergeParams();
    }

    protected function mergeParams()
    {
        $params = [];
        foreach($this->params as $key => $value) {
            if (isset($key)) {
                $params[$key] = $value;
            }
        }
        $this->timezone ? $params['timezone'] = $this->timezone : null;
        $this->fields ? $params['fields'] = $this->fields : null;
        $this->sub_call ? $params['sub_call'] = $this->sub_call : null;
        $this->filter ? $params['filter'] = $this->filter : null;
        if ($this->orderBy) {
            $params['order'] = $this->orderBy;
            $params['dir'] = $this->dir;
        }

        return $params;
    }
    public function getAllowedFields()
    {
        return self::$allowedFields;
    }


    public function getAllowedFilters()
    {
        return self::$filterFields;
    }
}

