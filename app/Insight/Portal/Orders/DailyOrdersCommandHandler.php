<?php
namespace Insight\Portal\Orders;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\EventGenerator;
use Laracasts\Commander\Events\DispatchableTrait;
use Insight\Portal\Orders\Events\DailyOrderReportWasRequested;
use \Log;

/**
 * Class DailyOrdersCommandHandler
 * @package Insight\Portal\Orders
 */
class DailyOrdersCommandHandler implements CommandHandler
{

    use EventGenerator, DispatchableTrait;

    /**
     * @var
     */
    public $orderReport;

    /**
     * @var
     */
    public $file;

    public $orderLineCount;

    /**
     * Custom spreadsheet renderer for specific website
     * array(
        'website_code' => 'renderer class'
     * )
     * @var array
     */
    public $spreadsheetRenders= array(
        'default' => '\Insight\Portal\Orders\Daily\Renderer\Base',
        'emrill' => '\Insight\Portal\Orders\Daily\Renderer\Emrill'
    );

    /**
     * @param $command
     * @return string
     */
    public function handle($command)
    {
        // Number of line items in report
        $this->orderLineCount = 0;
        // Customer Name
        $this->orderReport['customer'] = $command->customer->customer_group_code;
        // Customer Group Id
        $this->orderReport['customer_group_id'] = $command->customer->customer_group_id;
        // Date filter of report
        if (is_null($command->report_date)) {
            throw new \InvalidArgumentException('Report date must contain a value.');
        }
        $this->orderReport['report_date'] = $command->report_date;

        // Generate the line item report
        $this->generateLineItemsReport($command);

        // If there were orders, Log the order report, create the spreadsheet file, and raise an event
        if ($this->orderReport['order_data']) {

//            $this->orderReport['file'] = $this->createSpreadsheet($this->orderReport['order_data']);
            $this->orderReport['file'] = $this->getSpreadsheetRenderer($command->websiteCode)
                                        ->setOrdersData($command->portalOrders)
                                        ->setSpreadsheetData($this->orderReport['order_data'])
                                        ->createSpreadsheet();

            $this->raise(new DailyOrderReportWasRequested($this->orderReport));

            $this->dispatchEventsFor($this);

        } else {
            Log::info("No orders to notify.");
        }

        return "Number of line items processed: " . $this->orderLineCount;

    }

    private function generateLineItemsReport($command)
    {
        /*
         * COMMON LINE ITEM DATA
         *
         * Loop through each customer order and assign variables common to each order line item.
         * These values will be common for each line item of the order.
         */
        foreach ($command->portalOrders as $order) {
            $comment_history = isset ($order->customer_note) ? $order->customer_note : "";
            $increment_id = isset($order->order_id) ? $order->order_id : "";
            $customer_group_name = isset($order->customer_name) ? $order->customer_name : "";
            $ordered_by = isset($order->customer_email) ? $order->customer_email : "";
            $approved_at = isset($order->approved_at) ? $order->approved_at : "";
            $contract_name = isset($order->contract_name) ? $order->contract_name : "";
            $firstname = isset($order->shipping_address->firstname) ? $order->shipping_address->firstname : "";
            $lastname = isset($order->shipping_address->lastname) ? $order->shipping_address->lastname : "";
            $street = isset($order->shipping_address->street) ? implode(" ", $order->shipping_address->street) : "";
            $city = isset($order->shipping_address->city) ? $order->shipping_address->city : "";
            $region = isset($order->shipping_address->region) ? $order->shipping_address->region : "";
            $country = isset($order->shipping_address->postcode) ? $order->shipping_address->postcode : "";
            $ship_to = str_replace(",", " ", $firstname . " " . $lastname . " " . $street . " " . $city . " " . $region . " " . $country);
            $contract_id = isset($order->contract_code) ? $order->contract_code : '';


            /*
             * LINE ITEM SPECIFIC DATA
             *
             * Loop through each order line item and assign variables for each column
             */
            foreach ($order->items as $lineitem) {
                $sku = isset($lineitem->sku) ? $lineitem->sku : "";
                $uom = isset($lineitem->uom) ? $lineitem->uom : "";
                $product_name = isset($lineitem->name) ? $lineitem->name : "";
                $qty_ordered = isset($lineitem->qty) ? floatval($lineitem->qty) : "";
                $row_total = isset($lineitem->row_total) ? floatval($lineitem->row_total) : "";
                $price = isset($lineitem->price) ? floatval($lineitem->price) : "";
                $supplier = isset($lineitem->supplier) ? $lineitem->supplier : '';

                // Assign the line item values to an associative array
                $salesOrderLine = [];
                $salesOrderLine['Order ID'] = $increment_id;
                $salesOrderLine['Customer'] = $customer_group_name;
                $salesOrderLine['Ordered By'] = $ordered_by;
                $salesOrderLine['Approved Date'] = $approved_at;
                $salesOrderLine['Contract'] = $contract_name;
                $salesOrderLine['Contract ID'] = $contract_id;
                $salesOrderLine['Ship To'] = $ship_to;
                $salesOrderLine['Customer Note'] = $comment_history;
                $salesOrderLine['Product SKU'] = $sku;
                $salesOrderLine['Product Name'] = $product_name;
                $salesOrderLine['UOM'] = $uom;
                $salesOrderLine['QTY'] = $qty_ordered;
                $salesOrderLine['Price'] = $price;
                $salesOrderLine['Row Total'] = $row_total;
                $salesOrderLine['Supplier'] = $supplier;

                // If customer has custom order reference fields defined, add them to the line item array
                if ($oderReferences = isset($order->custom_refs) ? $order->custom_refs : null) {
                    foreach ($oderReferences as $orderReference) {
                        if ((int) $orderReference->is_enabled === 1) {
                            $salesOrderLine[$orderReference->label] = $orderReference->value;
                        }
                    }
                }

                // Add the line item array to the parent orderReport array
                $this->orderReport['order_data'][] = $salesOrderLine;
                $this->orderLineCount ++;

            }
        }
    }

    /**
     * get spreadsheet renderer for
     * @param string $websiteCode
     * @return \Insight\Portal\Orders\Daily\Renderer\Base
     * @throw \InvalidArgumentException
     */
    public function getSpreadsheetRenderer($websiteCode)
    {
        $websiteCode = strtolower($websiteCode);
        $class = $this->spreadsheetRenders['default'];
        if (array_key_exists($websiteCode, $this->spreadsheetRenders)) {
            $class = $this->spreadsheetRenders[$websiteCode];
        }
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Spreadsheet renderer for website code \'%s\' does not exist.', $websiteCode));
        }
        return new $class();
    }

}