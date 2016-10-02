<?php

namespace Insight\Portal\Orders\Events;

class OrdersPendingApprovalReportWasCreated
{

    public $report;

    public $data;


    public function __construct($report, $data)
    {
        $this->report     = $report;
        $this->data       = $data;
    }
}
