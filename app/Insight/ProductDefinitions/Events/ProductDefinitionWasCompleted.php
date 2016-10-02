<?php namespace Insight\ProductDefinitions\Events;
use Insight\ProductDefinitions\ProductDefinition;

/**
 * Insight Client Management Portal:
 * Date: 7/28/14
 * Time: 10:59 PM
 */



/**
 * Class ProductDefinition
 * @package Insight\ProductDefinitions\Events
 */
class ProductDefinitionWasCompleted
{
    /**
     * @var ProductDefinition
     */
    public $productDefinition;
    /**
     * @var
     */
    public $remarks;

    /**
     * @param ProductDefinition $productDefinition
     */
    public function __construct(ProductDefinition $productDefinition, $remarks)
    {
        $this->productDefinition = $productDefinition;
        $this->remarks = $remarks;
    }
    
} 