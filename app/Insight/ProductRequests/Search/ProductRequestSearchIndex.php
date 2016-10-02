<?php

namespace Insight\ProductRequests\Search;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Insight\ProductRequests\ProductRequest;
use Insight\Search\AlgoliaSearch;

class ProductRequestSearchIndex
{
    const INDEX_NAME = 'product_requests';

    /**
     * @var AlgoliaSearch
     */
    private $client;


    public function __construct(AlgoliaSearch $client)
    {
        $this->client = $client;
        $this->client->setIndex(getenv('PRODUCT_REQUESTS_INDEX') ?: self::INDEX_NAME);
    }

    public function buildInitialIndex()
    {
        // increase memory limit for large indexes
        ini_set('memory_limit','2048M');
        DB::connection()->disableQueryLog();
        $productRequests = ProductRequest::all();
        $this->update($productRequests);
        $this->setSettings();
    }

    public function update($productRequest)
    {
        $this->save($productRequest);
    }

    protected function save($records)
    {
        if (count($records)) {
            $batch = [];
            $count = 0;
            foreach ($records as $record) {
                if ($count < 100 ) {
                    $batch[] = $record;
                    $count++;
                    continue;
                }
                $this->client->saveMany($this->mapFields($batch));
                $count = 0;
                $batch = [];
            }
        }
        $this->client->saveMany($this->mapFields($records));
    }

    public function deleteRecord(ProductRequest $productRequest)
    {
        $this->client->delete($productRequest);
    }

    protected function setSettings()
    {
        $this->client->setSettings([
            "attributesToIndex" => ["productDescription", "cataloguingProductName", "category"],
            "attributesForFaceting" => ["company", "company_code", "id"]
        ]);
    }

    protected function mapFields($records) {
        if (! is_array($records) && ! $records instanceof Collection) {
            $records = [$records];
        }
        $productRequests = [];
        foreach ($records as $record) {
            $productRequest['id'] = $record->id;
            $productRequest['requestId'] = $record->request_id;
            $productRequest['listId'] = $record->list_id;
            $productRequest['listName'] = $record->productRequestList ? $record->productRequestList->name : null;
            $productRequest['requestedBy'] = $record->requestedBy->name();
            $productRequest['company_code'] = $record->requestedBy->company->settings()->get('portal.dataGroup');
            $productRequest['company'] = $record->requestedBy->company->name;
            $productRequest['productDescription'] = $record->product_description;
            $productRequest['sku'] = $record->sku;
            $productRequest['uom'] = $record->uom;
            $productRequest['category'] = $record->category;
            $productRequest['purchase_recurrence'] = ProductRequest::$purchaseRecurrence[$record->purchase_recurrence];
            $productRequest['firstTimeOrderQuantity'] = $record->first_time_order_quantity;
            $productRequest['purchaseRecurrenceQuantity'] = $record->volume_requested;
            $productRequest['currentSupplier'] = $record->current_supplier;
            $productRequest['currentPrice'] = (float) str_replace(',', '', $record->current_price);
            $productRequest['currentPriceCurrency'] = $record->current_price_currency;
            $productRequest['currentPriceFormatted'] = $record->current_price_currency ? $record->current_price_currency . ' ' . $record->current_price : $record->current_price;
            $productRequest['status'] = $record->currentStateLabel();
            $productRequest['reference1'] = $record->reference1;
            $productRequest['reference2'] = $record->reference2;
            $productRequest['reference3'] = $record->reference3;
            $productRequest['reference4'] = $record->reference4;
            $productRequest['createdAt'] = $record->created_at->toDateTimeString();
            $productRequest['createdBy'] = $record->createdBy->name();
            $productRequest['updatedAt'] = $record->updated_at->toDateTimeString();
            $productRequest['updatedBy'] = $record->updatedBy->name();
            $productRequest['deletedAt'] = $record->deleted_at ? $record->deleted_at->toDateTimeString() : null;
            $productRequest['completedAt'] = $record->completed_at ? $record->completed_at->toDateTimeString() : null;
            $productRequest['closedAt'] = $record->closed_at;
            $productRequest['reason_closed'] = $record->reason_closed ? ProductRequest::$reasonsClosed[$record->reason_closed] : null;
            $productRequest['cataloguingItemCode'] = $record->cataloguing_item_code;
            $productRequest['cataloguingProductName'] = $record->cataloguing_product_name;
            $productRequests[] = $productRequest;
        }

        return $productRequests;
    }
}
