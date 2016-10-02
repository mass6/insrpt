<?php

namespace Insight\ProductRequests\Commands;

use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\ProductRequests\ProductRequestList;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;


/**
 * Class UploadProductRequestListCommandHandler
 * @package Insight\ProductRequests\Commands
 */
class UploadProductRequestListCommandHandler implements CommandHandler
{

    use CommandBus, DispatchableTrait, RaisableTrait;

    /**
     * @var array
     */
    private $events = [];

    /**
     *
     */
    public function __construct()
    {
        $this->events = ['Uploaded'];
    }

    /**
     * Create the Sourcing Request
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        $referenceFields = $command->referenceFields;

        try {
            $productRequestList = ProductRequestList::create([
                'created_by_id'   => $command->user->id,
                'requested_by_id' => $command->requester->id,
                'company_id'      => $command->company_id,
                'name'            => $command->name,
            ]);

            $command->requester->productRequestLists()->save($productRequestList);

            foreach ($command->uploadFile as $row) {
                $productRequest = $this->execute(new AddNewProductRequestCommand(
                    $command->user, $command->requester, $row->product_description, $row->uom, strtolower($row->category), $this->getPurchaseRecurrenceCode($row->purchase_recurrence),
                    $row->first_time_order_quantity, $row->volume, $row->current_sku, $row->current_price, $row->current_price_currency,
                    $row->current_supplier, $row->current_supplier_contact, $contractsAssigned = [],
                    $row->{array_get($referenceFields, '1.name', null)}, $row->{array_get($referenceFields, '2.name', null)},
                    $row->{array_get($referenceFields, '3.name', null)}, $row->{array_get($referenceFields, '4.name', null)},
                    $row->remarks, $attachments = [], $transition = $command->transition, $productRequestList->id
                ));

                $productRequestList->productRequests()->save($productRequest);
            }

            $this->raiseEvents($productRequestList, $this->events);

        } catch (\Exception $e) {
            return $e;
        }

        $this->dispatchEventsFor($productRequestList);

        return $productRequestList;
    }

    /**
     * @param $string
     * @return bool|string
     */
    private function getPurchaseRecurrenceCode($string)
    {
        switch (strtolower($string)) {
            case 'ahc':
            case 'adhoc':
            case 'ad-hoc':
            case 'once':
            case 'onetime':
            case 'one-time':
            case 'one-off':
            case 'oneoff':
                return 'AHC';
                break;
            case 'mon':
            case 'monthly':
            case 'month':
            case 'months':
            case 'per-month':
            case 'per month':
            case 'every month':
            case 'every-month':
                return 'MON';
                break;
            case 'ann':
            case 'annually':
            case 'year':
            case 'yearly':
            case 'every year':
            case 'every-year':
            case 'per-annum':
            case 'per annum':
                return 'ANN';
                break;
            default:
                return false;
        }
    }


}