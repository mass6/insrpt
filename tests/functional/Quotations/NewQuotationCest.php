<?php
namespace Quotations;

use \FunctionalTester;
use Codeception\Util\Locator;

class NewQuotationCest
{
    protected $user;

    public function _before(FunctionalTester $I)
    {
        $user = $I->haveAnAccount();
        $I->amLoggedInAsAUser($user);
        $this->user = $user;
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function it_shows_the_quotations_menu_when_given_permission(FunctionalTester $I)
    {
        $I->am('a customer with permissions to supplier quotations');
        $I->wantTo('verify I can see the product request menu section when given permissions');

        $I->amOnRoute('home');
        $I->dontSeeLink('Quotation Requests', '/quotation-requests');
        $I->dontSeeLink('All Quotation', '/quotations');

        $I->haveAccessTo(['product-requests.edit', 'quotations.edit'], $this->user);
        $I->amOnRoute('home');
        $I->seeLink('Quotation Requests', '/quotation-requests');
        $I->seeLink('All Quotations', '/quotations');
    }

    private function it_creates_a_supplier_quotation_request(FunctionalTester $I)
    {
        $I->am('a customer with permissions to supplier quotations');
        $I->wantTo('verify I can see the product request menu section without propor permissions');
        $I->haveAccessTo('quotations.edit', $this->user);
        $I->dontSeeInDatabase('quotations', array('product_name' => 'AppleTV'));

        $I->amOnRoute('quotations.create');
        $I->see('New Supplier Quotation',  Locator::combine('h1','h2','h3','h4','h5'));

        $data = [
            'product_name' => 'AppleTV'
        ];

        $I->fillField('product_name', $data['product_description']);
//        $I->fillField('uom',  $data['uom']);
//        $I->selectOption('category', 'Default');
//        $I->selectOption("form select[name=purchase_recurrence]", 'One-time/Ad-hoc');
//        $I->fillField('volume_requested', $data['volume_requested']);
//        $I->fillField('sku', $data['sku']);
//        $I->fillField('current_price', $data['current_price']);
//        $I->selectOption("form select[name=current_price_currency]", 'AED');
//        $I->fillField('current_supplier', $data['current_supplier']);
//        $I->fillField('current_supplier_contact', 'test contact details');
//        $I->fillField('reference1', $data['reference1']);
//        $I->fillField('reference2', $data['reference2']);
//        $I->fillField('reference3', $data['reference3']);
//        $I->fillField('reference4', $data['reference4']);
//        $I->fillField('remarks', $data['remarks']);
//
//        $I->click('Save Draft');
//
//        $I->seeCurrentRouteIs('product-requests.index');
//        $I->seeCurrentUrlEquals("/product-requests");
//        $I->see('Product request draft has been saved successfully.');
//        $record = $I->grabRecord('product_requests', [
//            'product_description' => 'My Test Product',
//            'sku' => 'test sku',
//            'uom' => 'test uom',
//        ]);

//        codecept_debug($record);

//        $I->assertEquals($this->user->id, $record->created_by_id);
//        $I->assertEquals($this->user->company->id, $record->company_id);
//        $I->assertEquals($data['product_description'], $record->product_description);
//        $I->assertEquals($data['uom'], $record->uom);
//        $I->assertEquals($data['category'], $record->category);
//        $I->assertEquals($data['purchase_recurrence'], $record->purchase_recurrence);
//        $I->assertEquals($data['volume_requested'], $record->volume_requested);
//        $I->assertEquals($data['sku'], $record->sku);
//        $I->assertEquals($data['current_price'] * 100, $record->current_price);
//        $I->assertEquals($data['current_price_currency'], $record->current_price_currency);
//        $I->assertEquals($data['current_supplier'], $record->current_supplier);
//        $I->assertEquals($data['current_supplier_contact'], $record->current_supplier_contact);
//        $I->assertEquals($data['reference1'], $record->reference1);
//        $I->assertEquals($data['reference2'], $record->reference2);
//        $I->assertEquals($data['reference3'], $record->reference3);
//        $I->assertEquals($data['reference4'], $record->reference4);
//        $I->assertEquals($data['remarks'], $record->remarks);
//        $I->assertEquals($this->user->id, $record->updated_by_id);
    }
}