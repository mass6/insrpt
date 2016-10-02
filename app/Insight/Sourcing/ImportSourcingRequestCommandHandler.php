<?php namespace Insight\Sourcing;

use Carbon\Carbon;
use Insight\Core\RaisableTrait;
use Insight\Sourcing\Events\SourcingRequestWasImported;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class AddNewSourcingRequestCommandHandler
 * @package Insight\Sourcing
 */
class ImportSourcingRequestCommandHandler implements CommandHandler
{

    use DispatchableTrait, RaisableTrait;

    /**
     * @var array
     */
    private $events = [];

    private $sourcingRequests = [];

    /**
     *
     */
    public function __construct()
    {
        $this->events = ['Imported'];
    }

    /**
     * Create the Sourcing Request
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        if (! empty($command->assigned_to_id)) {
            $this->events[] = 'Imported';
        }

        foreach ($command->importfile as $row) {

            try {
                $request = new SourcingRequest([
                    'customer_id'                  => $command->customer_id,
                    'batch'                        => $command->batch,
                    'received_on'                  => Carbon::createFromFormat('d-m-Y', $command->received_on),
                    'customer_sku'                 => $row->customer_sku,
                    'customer_product_description' => $row->customer_product_description,
                    'customer_uom'                 => $row->customer_uom,
                    'customer_price'               => $row->customer_price,
                    'customer_price_currency'      => $row->customer_price_currency,
                    'updated_by_id'                => $command->user->id,
                    'status'                       => $command->status,
                    'assigned_to_id'               => $command->assigned_to_id ?: null,
                ]);

                $sourcingRequest = $command->user->sourcingRequests()->save($request);
                $this->sourcingRequests[] = $sourcingRequest;

            } catch (\Exception $e) {
                return $e;
            }
        }

        $sourcingRequest = new SourcingRequest();
        $sourcingRequest->raise(new SourcingRequestWasImported($this->sourcingRequests));
        $this->dispatchEventsFor($sourcingRequest);

        return $this->sourcingRequests;
    }

}