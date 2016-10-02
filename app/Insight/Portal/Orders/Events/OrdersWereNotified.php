<?php
namespace Insight\Portal\Orders\Events;

class OrdersWereNotified {
    public $notify;

    public function __construct($notify)
    {
        $this->notify = $notify;
    }
}