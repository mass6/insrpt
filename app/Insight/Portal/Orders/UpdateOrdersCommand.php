<?php namespace Insight\Portal\Orders; 
/**
 * Insight Client Management Portal:
 * Date: 8/10/14
 * Time: 3:22 PM
 */

class UpdateOrdersCommand
{

    /**
     * @var array
     */
    public $portalOrders;

    public function __construct(Array $portalOrders)
    {
        $this->portalOrders = $portalOrders;
    }
    
} 
