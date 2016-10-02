<?php
namespace Sourcing;
use Carbon\Carbon;
use \FunctionalTester;
use \Codeception\Util\Locator;
use Illuminate\Support\Facades\Input;

class SourcingRequestCreationCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAsSourcingRequestsUser();
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToCreateANewSourcingRequest(FunctionalTester $I)
    {
        $I->am('a user with permissions to sourcing requests');
        $I->wantTo('create a new sourcing request');

        $I->dontSeeRecord('sourcing_requests', array('tss_sku' => 'YY-zz-99-88'));

        $I->amOnRoute('sourcing-requests.create');
        $I->see('New Sourcing Request', Locator::combine('h1','h2','h3'));

        $company_option = $I->grabTextFrom('form select[name=customer_id] option:nth-child(2)');
        $I->selectOption("form select[name=customer_id]", $company_option);
        $I->fillField('received_on', '26-06-2015');
        $I->fillField('customer_sku', 'AA-bb-11');
        $I->fillField('customer_product_description', 'Sample product description');
        $I->fillField('customer_price', '45.50');
        $I->fillField('customer_price_currency', 'AED');
        $I->fillField('customer_uom', 'Each');

        $I->fillField('tss_sku', 'YY-zz-99-88');
        $I->fillField('tss_product_name', 'TSS Product Name');
        $I->fillField('tss_buy_price', '25.20');
        $I->fillField('tss_uom', 'Carton');
        $I->fillField('supplier_name', 'Acme Supply Company');
        $I->fillField('tss_buy_price_currency', 'AED');
        $I->fillField('tss_sell_price', '40.75');
        $I->fillField('tss_sell_price_currency', 'AED');
        $assigned_user_option = $I->grabTextFrom('form select[name=assigned_to_id] option:nth-child(2)');
        $I->selectOption("form select[name=assigned_to_id]", $assigned_user_option);

        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('sourcing-requests.index');
        $I->canSee('Sourcing Request was successfully created.');

        $I->seeRecord('sourcing_requests', array('tss_sku' => 'YY-zz-99-88'));
    }

    public function it_imports_a_file_and_creates_multiple_sourcing_requests(FunctionalTester $I)
    {
        $I->am('a user with permissions to sourcing requests');
        $I->wantTo('create multiple sourcing request via an import file');

        // before scenario
        $I->dontSeeRecord('sourcing_requests', array('batch'=>'batchsheet1', 'customer_sku' => '999888777A'));
        $I->dontSeeRecord('sourcing_requests', array('batch'=>'batchsheet1', 'customer_sku' => '999888777B'));

        // go to to import page
        $I->amOnRoute('sourcing-requests.import.create');
        $I->seeElement('select', ['name'=>'customer_id']);
        $I->seeElement('input', ['name' => 'importfile'] );

        // fill fields and submit form
        $customer_id_option = $I->grabTextFrom('form select[name=customer_id] option:nth-child(2)');
        $I->selectOption("form select[name=customer_id]", $customer_id_option);
        $I->fillField('batch', 'batchsheet1');
        $I->fillField('received_on', Carbon::today(getenv('APP_TIMEZONE'))->format('d-m-Y'));
        $I->attachFile(['name'=> "importfile"], 'sourcing-request-importfile.xlsx');
        $I->click('input[type=submit]');

        // verify file structure is correct (i.e. columns are correct)
        $I->seeCurrentActionIs('SourcingRequestsController@confirmImport');
        $I->seeCurrentRouteIs('sourcing-requests.import.store');
        $I->assertTrue(Input::hasFile('importfile'), 'Input data includes importfile');
        $I->seeElement('input', ['value' => 'Process File']);
        $I->click('input[type=submit]');

        $I->seeCurrentRouteIs('sourcing-requests.index');
        $I->see('2 Sourcing Requests were successfully imported.');

        $I->seeRecord('sourcing_requests', array('batch'=>'batchsheet1', 'customer_sku' => '999888777A'));
        $I->seeRecord('sourcing_requests', array('batch'=>'batchsheet1', 'customer_sku' => '999888777B'));

    }

}