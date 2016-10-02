<?php
namespace Insight\Portal\Orders\Events;

class DailyOrderReportWasRequested {
    public $orderReport;

    public function __construct($orderReport)
    {
        $this->orderReport = $orderReport;
    }
}