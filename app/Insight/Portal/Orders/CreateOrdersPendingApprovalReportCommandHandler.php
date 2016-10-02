<?php

namespace Insight\Portal\Orders;

use Insight\Core\DispatchableObject;
use Insight\Core\Eventable;
use Insight\Portal\Orders\Events\OrdersPendingApprovalReportWasCreated;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;
use Maatwebsite\Excel\Facades\Excel;

class CreateOrdersPendingApprovalReportCommandHandler implements CommandHandler
{
    use DispatchableTrait;

    protected $file;

    /**
     * Handle the command
     *
     * @param $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        $this->createSpreadsheet($command);

        $this->raiseEvent($command);
    }

    /**
     * @param $command
     */
    protected function createSpreadsheet($command)
    {
        $data = $this->prepareSpreadsheetData($command->data['orders']);
        Excel::create($this->getFilename($command), function ($excel) use ($data, $command) {
            $excel->setTitle($this->getTitle($command));
            $excel->setDescription($this->getDescription());
            $file       = $excel->sheet($this->getSheetName(), function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false);
            })->store('xlsx');
            $this->file = $file;
        });
    }

    protected function raiseEvent($command)
    {
        $dispatchable = new DispatchableObject;
        $dispatchable->raise(new OrdersPendingApprovalReportWasCreated($this->file, $command->data));
        $this->dispatchEventsFor($dispatchable);
    }
    private function prepareSpreadsheetData($orders)
    {
        $data = [];
        foreach ($orders as $order) {
            $orderLine['Current Approver'] = $order['current_approver'];
            $orderLine['Weborder'] = $order['entity_id'];
            $orderLine['Total'] = $order['total'];
            $orderLine['Customer'] = $order['customer'];
            $orderLine['Ordered By'] = $order['ordered_by'];
            $orderLine['Contract'] = $order['contract'];
            $orderLine['Ordered On'] = $order['created_at'];
            $orderLine['Current Lead Time (hrs)'] = $order['lead_time_hours'];
            $orderLine['Total Lead Time (hrs)'] = $order['total_lead_time_hours'];
            $orderLine['Last Approver'] = $order['last_approver'];
            $orderLine['Custom Field 1'] = $order['custom_ref1'];
            $orderLine['Custom Field 2'] = $order['custom_ref2'];

            $data[] = $orderLine;
        }

        return array_reverse($data);
    }

    private function getFilename($command)
    {
        return mktime(date('now')) . '-' . str_replace(' ', '', $command->data['customer_name']) . '-OrdersPendingApprovalReport';
    }

    private function getTitle($command)
    {
        return "Orders Pending Approval for {$command->data['customer_name']}";
    }

    public function getDescription()
    {
        return 'Orders currently pending approval';
    }

    public function getSheetName()
    {
        return 'OrdersPendingApproval';
    }
}