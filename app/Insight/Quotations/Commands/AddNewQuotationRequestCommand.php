<?php

namespace Insight\Quotations\Commands;

use Insight\Companies\Company;
use Insight\Users\User;

/**
 * Class AddNewQuotationRequestCommand
 * @package Insight\Quotations\Commands
 */
class AddNewQuotationRequestCommand
{

    /**
     * @var User
     */
    public $user;
    /**
     * @var Company
     */
    public $company;
    /**
     * @var
     */
    public $product_requests;

    /**
     * @param User $user
     * @param Company $company
     * @param $product_requests
     */
    public function __construct(User $user, Company $company, $product_requests)
    {
        $this->user = $user;
        $this->company = $company;
        $this->product_requests = $product_requests;
    }
}
