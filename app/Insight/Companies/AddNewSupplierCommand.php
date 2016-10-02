<?php

namespace Insight\Companies;

/**
 * Class AddNewCompanyCommand
 * @package Insight\Companies
 */
class AddNewSupplierCommand
{

    /**
     * @var
     */
    public $name;

    /**
     * @var
     */
    public $notes;

    /**
     * @var
     */
    public $primary_contact_user_id;
    /**
     * @var
     */
    public $type;
    /**
     * @var
     */
    public $address1_description;
    /**
     * @var
     */
    public $address1_body;
    /**
     * @var
     */
    public $address2_description;
    /**
     * @var
     */
    public $address2_body;
    /**
     * @var
     */
    public $address3_description;
    /**
     * @var
     */
    public $address3_body;
    /**
     * @var
     */
    public $address4_description;
    /**
     * @var
     */
    public $address4_body;
    /**
     * @var
     */
    public $magento_customer_group_id;
    /**
     * @var
     */
    public $customer_company_id;
    /**
     * @param $name
     * @param $type
     * @param $notes
     * @param $address1_description
     * @param $address1_body
     * @param $address2_description
     * @param $address2_body
     * @param $address3_description
     * @param $address3_body
     * @param $address4_description
     * @param $address4_body
     * @param $customer_company_id
     * @param $magento_customer_group_id
     * @param $primary_contact_user_id
     */
    public function __construct($name, $type, $notes,$primary_contact_user_id,$magento_customer_group_id,$customer_company_id,
                                $address1_description, $address1_body, $address2_description, $address2_body,
                                $address3_description, $address3_body, $address4_description, $address4_body)
    {
        $this->name = $name;
        $this->type = $type;
        $this->notes = $notes;
        $this->primary_contact_user_id = $primary_contact_user_id;
        $this->customer_company_id = $customer_company_id;
        $this->address1_description = $address1_description;
        $this->address1_body = $address1_body;
        $this->address2_description = $address2_description;
        $this->address2_body = $address2_body;
        $this->address3_description = $address3_description;
        $this->address3_body = $address3_body;
        $this->address4_description = $address4_description;
        $this->address4_body = $address4_body;
        $this->magento_customer_group_id = $magento_customer_group_id;
    }
} 