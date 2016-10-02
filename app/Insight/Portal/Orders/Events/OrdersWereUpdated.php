<?php namespace Insight\Portal\Orders\Events; 
/**
 * Insight Client Management Portal:
 * Date: 3/1/15
 * Time: 10:23 PM
 */

class OrdersWereUpdated 
{
    public $changeLog;

    public function __construct($changeLog)
    {
        $this->changeLog = $changeLog;
    }
} 
