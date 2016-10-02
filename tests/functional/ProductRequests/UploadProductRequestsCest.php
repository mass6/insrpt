<?php
namespace ProductRequests;
use Codeception\Util\Locator;
use \FunctionalTester;
use Illuminate\Support\Facades\Input;

class UploadProductRequestsCest
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
            'product-request-lists.create',
        ], $user);
        $this->user = $user;

        $this->product_request['company_id'] = $this->user->company->id;
        $this->product_request['updated_by_id'] = $this->user->id;
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function it_imports_a_file_and_creates_multiple_product_requests(FunctionalTester $I)
    {
        $I->am('a user with permissions to upload product requests');
        $I->wantTo('create multiple product requests via an import file');

        // go to to import page
        $I->amOnRoute('product-request-lists.create');
        $I->seeCurrentActionIs('ProductRequestListsController@create');
        $I->see('Product Request Upload Wizard', Locator::combine('h1', 'h2', 'h3', 'h4')   );
        $I->see('Step 1');
        $I->seeElement('input', ['name' => 'uploadfile'] );

        // fill fields and submit form
        $I->attachFile(['name'=> "uploadfile"], 'product-requests-uploadfile.xlsx');
        $I->fillField(['name'=> "name"], 'test list 1');
        $I->click('input[type=submit]');

        // verify file structure is correct (i.e. columns are correct)
        $I->seeCurrentActionIs('ProductRequestListsController@confirm');
        $I->seeCurrentRouteIs('product-request-lists.confirm');
        $I->seeCurrentUrlEquals('/product-request-lists/confirm');
        $I->assertTrue(Input::hasFile('uploadfile'), 'Input data includes uploadfile');

        $I->see('Product Request Upload Wizard', Locator::combine('h1', 'h2', 'h3', 'h4'));
        $I->see('Step 2');
        $I->seeElement('input', ['value' => 'Process & Submit']);
        $I->click('input[type=submit]');
        $I->seeCurrentRouteIs('product-request-lists.show', 1);
        $I->see('2 Product Requests were successfully uploaded.');
        $I->seeRecord('product_request_lists', ['name'=>'test list 1', 'company_id' => $this->user->company->id]);
        $I->seeRecord('product_requests', ['product_description'=>'Product Alpha']);
        $I->seeRecord('product_requests', ['product_description'=>'Product Bravo']);
    }

}