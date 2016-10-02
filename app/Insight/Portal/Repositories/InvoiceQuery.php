<?php

namespace Insight\Portal\Repositories;

class InvoiceQuery extends AbstractQuery
{

    public static $allowedFields = [
        "entity_id",
        "store_id",
        "base_grand_total",
        "shipping_tax_amount",
        "tax_amount",
        "base_tax_amount",
        "store_to_order_rate",
        "base_shipping_tax_amount",
        "base_discount_amount",
        "base_to_order_rate",
        "grand_total",
        "shipping_amount",
        "subtotal_incl_tax",
        "base_subtotal_incl_tax",
        "store_to_base_rate",
        "base_shipping_amount",
        "total_qty",
        "base_to_global_rate",
        "subtotal",
        "base_subtotal",
        "discount_amount",
        "billing_address_id",
        "is_used_for_refund",
        "order_id",
        "email_sent",
        "can_void_flag",
        "state",
        "shipping_address_id",
        "store_currency_code",
        "transaction_id",
        "order_currency_code",
        "base_currency_code",
        "global_currency_code",
        "increment_id",
        "created_at",
        "updated_at",
        "hidden_tax_amount",
        "base_hidden_tax_amount",
        "shipping_hidden_tax_amount",
        "base_shipping_hidden_tax_amnt",
        "shipping_incl_tax",
        "base_shipping_incl_tax",
        "base_total_refunded",
        "invoice_number",
        "SapSync"
    ];

    public static $filterFields = ['entity_id'];


    public function getAllowedFields()
    {
        return self::$allowedFields;
    }


    public function getAllowedFilters()
    {
        return self::$filterFields;
    }


    public function getQueryParams()
    {
        return $this->mergeParams();
    }
}
