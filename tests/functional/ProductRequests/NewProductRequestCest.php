<?php
namespace ProductRequests;

use Codeception\Util\Locator;
use \FunctionalTester;

class NewProductRequestCest
{
    protected $user;

    protected $product_request = [
        'company_id' => null,
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
        'updated_by_id' => null,
    ];

    public function _before(FunctionalTester $I)
    {
        $user = $I->haveAnAccount();
        $I->amLoggedInAsAUser($user);
        $this->user = $user;

        $this->product_request['company_id'] = $this->user->company->id;
        $this->product_request['updated_by_id'] = $this->user->id;
    }

    public function _after(FunctionalTester $I)
    {
        unset($this->user);
        $this->product_request['company_id'] = null;
        $this->product_request['updated_by_id'] = null;
    }

    // tests
    /**
     * @param FunctionalTester $I
     *
     * @throws \Exception
     */
    public function it_saves_a_new_product_request_draft(FunctionalTester $I)
    {
        $I->am('user with permissions to product requests');
        $I->wantTo('create a new product request');
        $I->haveAccessTo(['product-requests.create','product-requests.save_draft'], $this->user);

        $this->enableReferenceFields();

        $I->dontSeeInDatabase('product_requests', array('product_description' => '22 Inch Color Monitor'));
        $I->amOnRoute('product-requests.create');
        $I->seeCurrentUrlEquals("/product-requests/create");
        $I->see('New Product Request',  Locator::combine('h1','h2','h3','h4','h5'));

        $data = $this->product_request;

        $I->fillField('product_description', $data['product_description']);
        $I->fillField('uom',  $data['uom']);
        $I->selectOption('category', 'Default');
        $I->selectOption("form select[name=purchase_recurrence]", 'One-time/Ad-hoc');
        $I->fillField('first_time_order_quantity', $data['first_time_order_quantity']);
        $I->fillField('volume_requested', $data['volume_requested']);
        $I->fillField('sku', $data['sku']);
        $I->fillField('current_price', $data['current_price']);
        $I->selectOption("form select[name=current_price_currency]", 'AED');
        $I->fillField('current_supplier', $data['current_supplier']);
        $I->fillField('current_supplier_contact', 'test contact details');
        $I->fillField('reference1', $data['reference1']);
        $I->fillField('reference2', $data['reference2']);
        $I->fillField('reference3', $data['reference3']);
        $I->fillField('reference4', $data['reference4']);
        $I->fillField('remarks', $data['remarks']);

        $I->click('Save Draft');

        $I->seeCurrentRouteIs('product-requests.index');
        $I->seeCurrentUrlEquals("/product-requests");
        $I->see('Product request draft has been saved successfully.');
        $record = $I->grabRecord('product_requests', [
            'product_description' => 'My Test Product',
            'sku' => 'test sku',
            'uom' => 'test uom',
        ]);

//        codecept_debug($record);

        $I->assertEquals($this->user->id, $record->created_by_id);
        $I->assertEquals($this->user->company->id, $record->company_id);
        $I->assertEquals($data['product_description'], $record->product_description);
        $I->assertEquals($data['uom'], $record->uom);
        $I->assertEquals($data['category'], $record->category);
        $I->assertEquals($data['purchase_recurrence'], $record->purchase_recurrence);
        $I->assertEquals($data['first_time_order_quantity'], $record->first_time_order_quantity);
        $I->assertEquals($data['volume_requested'], $record->volume_requested);
        $I->assertEquals($data['sku'], $record->sku);
        $I->assertEquals($data['current_price'] * 100, $record->current_price);
        $I->assertEquals($data['current_price_currency'], $record->current_price_currency);
        $I->assertEquals($data['current_supplier'], $record->current_supplier);
        $I->assertEquals($data['current_supplier_contact'], $record->current_supplier_contact);
        $I->assertEquals($data['reference1'], $record->reference1);
        $I->assertEquals($data['reference2'], $record->reference2);
        $I->assertEquals($data['reference3'], $record->reference3);
        $I->assertEquals($data['reference4'], $record->reference4);
        $I->assertEquals($data['remarks'], $record->remarks);
        $I->assertEquals($this->user->id, $record->updated_by_id);
    }


    /**
     * @param FunctionalTester $I
     */
    public function it_checks_that_users_cannot_see_menu_section_without_permission(FunctionalTester $I)
    {
        $I->am('a customer without permissions to create product requests');
        $I->wantTo('verify I cannot see the product request menu section without proper permissions');

        $I->amOnPage('/');
        $I->seeCurrentUrlEquals('/');
        $I->dontSee('New Request', '/product-requests/create');
    }


    /**
     * @param FunctionalTester $I
     */
    public function its_shows_a_new_request_link_if_user_has_access_permissions(FunctionalTester $I)
    {
        $I->am('user with permissions to product requests');
        $I->wantTo('create a new product request');
        $I->haveAccessTo('product-requests.create', $this->user);

        $I->amOnRoute('home');
        $I->seeLink('New Request', '/product-requests/create');
    }


    /**
     * @param FunctionalTester $I
     *
     * @throws \Exception
     */
    public function it_checks_that_users_must_have_access_to_create_product_request(FunctionalTester $I)
    {
        $I->am('a customer without permissions to create product request');
        $I->wantTo('verify I cannot create product requests without proper permissions');

        $I->amOnRoute('product-requests.create');
        $I->dontSee("New Product Request");
        $I->seeCurrentUrlEquals('');
        $I->see('You do not have the appropriate privileges to view the requested page.');
    }


    /**
     * @param FunctionalTester $I
     */
    public function it_does_not_show_reference_fields_unless_enabled(FunctionalTester $I)
    {
        $I->am('user with permissions to product requests');
        $I->wantTo('verify I do not see reference fields if not enabled');
        $I->haveAccessTo(['product-requests.create','product-requests.save_draft'], $this->user);

        $I->amOnRoute('product-requests.create');
        $I->seeCurrentUrlEquals("/product-requests/create");

        $I->dontSeeElement('#reference_1_input');
        $I->dontSeeElement('#reference_2_input');
        $I->dontSeeElement('#reference_3_input');
        $I->dontSeeElement('#reference_4_input');

    }


    /**
     *
     */
    private function enableReferenceFields()
    {
        $settings = '{
          "product-requests": {
            "references": {
             "enabled":"true"
            },
            "reference1": {
              "enabled":"true",
              "required":"true",
              "label":"Label 1"
            },
            "reference2":{
              "enabled":"true",
              "required":"true",
              "label":"Label 2"
            },
            "reference3":{
              "enabled":"true",
              "required":"true",
              "label":"Label 3"
            },
              "reference4":{
                "enabled":"true",
                "required":"true",
                "label":"Label 4"
            }
          }
        }';

        $this->user->company->settings = $settings;
        $this->user->company->save();
    }
}