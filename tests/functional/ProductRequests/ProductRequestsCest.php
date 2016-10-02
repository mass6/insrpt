<?php
namespace ProductRequests;

use Codeception\Util\Locator;
use \FunctionalTester;
use Insight\ProductRequests\ProductRequest;

class ProductRequestsCest
{
    protected $user;

    protected $product_request = [
        'product_description' => 'My Test Product',
        'sku' => 'test sku',
        'uom' => 'test uom',
        'category' => 'default',
        'purchase_recurrence' => 'AHC',
        'first_time_order_quantity' => 5,
        'volume_requested' => 10,
        'current_supplier' => 'test supplier',
        'current_supplier_contact' => 'test contact details',
        'current_price' => 40,
        'current_price_currency' => 'AED',
        'reference1' => 'test ref 1',
        'reference2' => 'test ref 2',
        'reference3' => 'test ref 2',
        'reference4' => 'test ref 2',
        'remarks' => 'test remarks',
        'state' => 'DRA',
    ];

    public function _before(FunctionalTester $I)
    {
        $user = $I->haveAnAccount();
        $I->amLoggedInAsAUser($user);
        $I->haveAccessTo([
            'product-requests.create',
            'product-requests.edit',
            'product-requests.save_draft',
        ], $user);
        $this->user = $user;
        $this->product_request['request_id'] = 'ZZZZ-9999';
        $this->product_request['company_id'] = $this->user->company->id;
        $this->product_request['updated_by_id'] = $this->user->id;
        $this->product_request['created_by_id'] = $this->user->id;
        $this->product_request['requested_by_id'] = $this->user->id;
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function it_shows_the_all_requests_page(FunctionalTester $I)
    {
        $I->am('a user with permissions to product requests');
        $I->wantTo('view the all product requests page');

        $I->amOnRoute('home');
        $I->seeLink('View All', '/product-requests');
        $I->click('View All');
        $I->amOnRoute('product-requests.index');
        $I->seeCurrentUrlEquals("/product-requests");
        $I->see('All Product Requests');
    }

    /**
     * @param FunctionalTester $I
     * @throws \Exception
     */
    public function it_displays_the_product_request_details_page(FunctionalTester $I)
    {
        $I->am('a user with permissions to product requests');
        $I->wantTo('view an individual product request');

        $I->haveRecord('product_requests', $this->product_request);
        $record = $I->grabRecord('product_requests', $this->product_request);

        $I->amOnRoute('product-requests.show', $record->request_id);

        $I->seeCurrentUrlEquals("/product-requests/{$record->request_id}");
        $I->see('Product Request Details');
        $I->see($record->request_id);
        $I->see($record->product_description);
        $I->see($record->uom);
        $I->see($record->first_time_order_quantity);
        $I->see($record->first_time_order_quantity);
        $I->see($record->volume_requested);
        $I->see($record->current_supplier);
        $I->see($record->current_price);
        $I->see($record->current_price_currency);
        $I->see($this->user->name());
        $I->see('Draft');
    }

    //public function it_lists_all_product_requests(FunctionalTester $I)
    //{
    //    $I->am('a user with permissions to product requests');
    //    $I->wantTo('view a list of all product requests');
    //
    //    $I->haveRecord('product_requests', $this->product_request);
    //    $record = ProductRequest::where('product_description','My Test Product')->first();
    //    $numRecords = ProductRequest::all()->count();
    //
    //    $I->amOnRoute('product-requests.index');
    //    $I->seeCurrentUrlEquals("/product-requests");
    //
    //    $I->seeElement('#product-requests-table');
    //    $I->see($record->request_id);
    //    $I->see($record->product_description, 'tbody tr td');
    //    $I->see($record->uom, 'tbody tr td');
    //    $I->see($record->company->name, 'tbody tr td');
    //    $I->see($record->created_at, 'tbody tr td');
    //    $I->see($record->first_time_order_quantity, 'tbody tr td');
    //    $I->see($record->volume_requested, 'tbody tr td');
    //    $I->see($record->currentStateLabel(), 'tbody tr td');
    //
    //    $I->see("{$numRecords} total records.");
    //}


}