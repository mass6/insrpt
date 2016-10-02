<?php

namespace Insight\Portal\Repositories;

use Carbon\Carbon;

class DeliveryQuery extends AbstractQuery
{

    public static $allowedFields = [
        "entity_id",
        "order_id",
        "store_id",
        "user_id",
        "increment_id",
        "date_dispatched",
        "updated_at",
        "delivery_number",
        "SapSync",
        "materials_received_note",
        "received_at",
        "attachment",
        "received_by",
        "order_status",
        "order_increment_id",
        "contract_name",
        "order.customer_group_id"
    ];

    public static $filterFields = ['delivery_number', 'shipment_number', 'id', 'created_at','order.customer_group_id'];


    public function __construct(Carbon $carbon)
    {
        parent::__construct($carbon);
        $this->timezone = getenv('APP_TIMEZONE') ?: 'UTC';
        $this->setSubCalls(['shipment_item']);
    }


    public function getAllowedFields()
    {
        return self::$allowedFields;
    }


    public function getAllowedFilters()
    {
        return self::$filterFields;
    }

    protected function validateField($attribute)
    {
        if (! in_array($attribute, $this->getAllowedFields())) {
            throw new \InvalidArgumentException("{$attribute} is not in the list of available report fields.");
        }
    }

    protected function validateFilter($attribute)
    {
        if (! in_array($attribute, array_merge($this->getAllowedFields(), $this->getAllowedFilters()))) {
            throw new \InvalidArgumentException("{$attribute} is not in the list of available report filters.");
        }
    }


    public function getQueryParams()
    {
        return $this->mergeParams();
    }

}
