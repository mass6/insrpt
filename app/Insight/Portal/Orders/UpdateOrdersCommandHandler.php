<?php namespace Insight\Portal\Orders;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\EventGenerator;
use Laracasts\Commander\Events\DispatchableTrait;
use Insight\Portal\Orders\Events\OrdersWereUpdated;
use \Log;
use Insight\Portal\Orders\Helper;
/**
 * Insight Client Management Portal:
 * Date: 3/1/15
 * Time: 10:24 PM
 */

class UpdateOrdersCommandHandler implements CommandHandler
{
    use EventGenerator, DispatchableTrait;

    public $changeLog;

    public function __construct()
    {
    }

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        $linecount = 0;

        // Headers
        $this->changeLog[$linecount++] = "Order ID, Customer, Ordered By, Approved Date, Contract, Ship To, Customer Note, Product SKU, Old Product Code, Product Name, UOM, QTY, Price, Row Total";
        $helper = new Helper();
        foreach ($command->portalOrders as $order)
        {
            $comment_history = isset ($order->customer_note) ? $order->customer_note : "";
            $comment_history = $helper->omitComma($comment_history);
//             foreach ($order->status_history as $status_update)
//             {
//                 $comment_history = $comment_history . isset($status_update->comment) ? $status_update->comment . " " : "";
//             }

             $increment_id = isset($order->increment_id) ? $order->increment_id : "";
             $customer_group_name = isset($order->customer_group_name) ? $order->customer_group_name : "";
                 $ordered_by = isset($order->customer_email) ? $order->customer_email: "";
             $approved_at = isset($order->approved_at) ? $order->approved_at : "";
             $contract_name = isset($order->contract_name) ? $order->contract_name : "";
             $firstname = isset($order->shipping_address->firstname) ? $order->shipping_address->firstname : "";
             $lastname = isset($order->shipping_address->lastname) ? $order->shipping_address->lastname : "";
             $street = isset($order->shipping_address->street) ? implode(" ", $order->shipping_address->street) : "";
             $city = isset($order->shipping_address->city) ? $order->shipping_address->city : "";
             $region = isset($order->shipping_address->region) ? $order->shipping_address->region : "";
             $country = isset($order->shipping_address->postcode) ? $order->shipping_address->postcode : "";
             $ship_to = str_replace (",", " ", $firstname . " " . $lastname . " " . $street . " " . $city . " " . $region . " " . $country);

             /* @var $lineitem sboeconnectSalesOrderItemEntity */
             foreach ($order->itemss as $lineitem)
             {

             $sku = isset($lineitem->sku) ? $lineitem->sku : "";
             $uom = isset($lineitem->uom) ? $lineitem->uom : "";
             $product_name = isset($lineitem->name) ? $lineitem->name : "";
             $qty_ordered = isset($lineitem->qty_ordered) ? $lineitem->qty_ordered : "";
             $row_total = isset($lineitem->row_total) ? $lineitem->row_total : "";
             $price = isset($lineitem->price) ? $lineitem->price : "";



                 $salesOrderLine = $increment_id . ","
                                   . $customer_group_name . ","
                                   . $ordered_by . ","
                                   . $approved_at . ","
                                   . $contract_name . ","
                                   . $ship_to . ","
                                   . $comment_history . ","
                                   . $sku . ","
                                   . " ,"
                                   . $product_name . ","
                                   . $uom . ","
                                   . $qty_ordered . ","
                                   . $price . ","
                                   . $row_total;
                 $this->changeLog[$linecount++] = $salesOrderLine;

            }
        }

        if ($this->changeLog){
            Log::info($this->changeLog);
            $this->raise(new OrdersWereUpdated($this->changeLog));
            $this->dispatchEventsFor($this);

        } else {
            Log::info("No orders to sync.");
        }


        return "Number of line items processed: " . --$linecount;

    }
}
