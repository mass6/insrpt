<?php
namespace Insight\Portal\Orders;

use Illuminate\Support\Facades\Log;
use Insight\Portal\Orders\Daily\Renderer\DailyOrderReportFormatter;
use Insight\Portal\Orders\Daily\Renderer\OrderReportFormatter;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\EventGenerator;
use Laracasts\Commander\Events\DispatchableTrait;
use Insight\Portal\Orders\Events\DailyOrderReportWasRequested;

class DailyOrdersCommandRestHandler implements CommandHandler
{
    use EventGenerator, DispatchableTrait;

    public $orderReport;
    public $file;
    public $orderLineCount;

    /**
     * Custom spreadsheet renderer for specific website
     * array(
     * 'website_code' => 'renderer class'
     * )
     * @var array
     */
    public $spreadsheetRenders = array(
        'default' => '\Insight\Portal\Orders\Daily\Renderer\Base',
        'emrill' => '\Insight\Portal\Orders\Daily\Renderer\Emrill'
    );

    public function handle($command)
    {
        $this->orderLineCount = 0;
        $this->orderReport['customer'] = $command->customer->customer_group_code;
        $this->orderReport['customer_group_id'] = $command->customer->customer_group_id;
        if (is_null($command->report_date)) {
            throw new \InvalidArgumentException('Report date must contain a value.');
        }
        $this->orderReport['report_date'] = $command->report_date;

        $fieldDefinitions         = array_get($command->orderReportSettings, 'field_definitions');
        $formattedSpreadSheetData = (new DailyOrderReportFormatter($command->portalOrders, $fieldDefinitions))->format();
        $this->orderReport['order_data'] = $formattedSpreadSheetData;

        // If there were orders, Log the order report, create the spreadsheet file, and raise an event
        if ($this->orderReport['order_data']) {
            $this->orderReport['file'] = $this->getSpreadsheetRenderer()
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

    /**
     * get spreadsheet renderer for
     * @return \Insight\Portal\Orders\Daily\Renderer\Base
     * @throw \InvalidArgumentException
     */
    public function getSpreadsheetRenderer()
    {
        $class = $this->spreadsheetRenders['default'];

        return new $class();
    }

}