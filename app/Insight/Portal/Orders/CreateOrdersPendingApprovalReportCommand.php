<?php

namespace Insight\Portal\Orders;

class CreateOrdersPendingApprovalReportCommand
{

    public $data;


    public function __construct($data)
    {
        $this->data = $data;
    }
}
