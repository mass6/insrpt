<?php

namespace Insight\Portal\Repositories;

use Carbon\Carbon;

class ProductQuery extends AbstractQuery
{

    public static $allowedFields = [
        "entity_id",
        "type_id",
        "sku",
        "status",
        "category",
        "name",
        "bp_product_code",
        "uom",
        "url_key",
        "image",
        "thumbnail",
        "price",
        "special_price",
        "visibility",
        "supplier",
        "supplier_id",
        "website"
    ];

    public static $filterFields = ['entity_id', 'sku', 'status', 'visibility'];

    public function __construct(Carbon $carbon)
    {
        parent::__construct($carbon);
        $this->timezone = getenv('APP_TIMEZONE') ?: 'UTC';
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

    public function getAllowedFields()
    {
        return static::$allowedFields;
    }

    public function getAllowedFilters()
    {
        return static::$filterFields;
    }


    public function getQueryParams()
    {
        return $this->mergeParams();
    }


}
